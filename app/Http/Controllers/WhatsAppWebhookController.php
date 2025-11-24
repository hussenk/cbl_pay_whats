<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\SystemConfig;
use App\Models\Webhook;
use App\Services\OtpService;
use App\Services\SessionService;
use App\Services\TransferService;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class WhatsAppWebhookController extends Controller
{
    public function __construct(
        protected WhatsAppService $wa,
        protected SessionService $sessionService,
        protected OtpService $otpService,
        protected TransferService $transferService
    ) {
    }

    public function __invoke(Request $request): Response|JsonResponse
    {


        Webhook::create([
            'request_payload' => $request->all(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'status' => 'received',
        ]);


        if ($request->isMethod('get')) {
            $verifyToken = $request->input('hub.verify_token') ?? $request->input('hub_verify_token');
            $challenge = $request->input('hub.challenge') ?? $request->input('hub_challenge');
            $expected = SystemConfig::getValue('facebook_verify_token') ?? config('services.whatsapp.verify_token');
            if ($verifyToken && $expected && hash_equals((string) $expected, (string) $verifyToken)) {
                return response($challenge, 200);
            }
            return response('Forbidden', 403);
        }

        // Handle message notifications
        $changes = Arr::get($request->all(), 'entry.0.changes.0.value', []);
        $messages = Arr::get($changes, 'messages', []);
        if (empty($messages)) {
            return response()->json(['status' => 'ignored'], 200);
        }

        $message = $messages[0];
        $from = $message['from'] ?? Arr::get($message, 'contacts.0.wa_id');
        $type = $message['type'] ?? 'text';
        $text = '';
        $payloadId = null;

        if ($type === 'text') {
            $text = trim($message['text']['body'] ?? '');
        } elseif ($type === 'interactive') {
            $interactiveType = $message['interactive']['type'] ?? null;
            if ($interactiveType === 'button_reply') {
                $payloadId = $message['interactive']['button_reply']['id'] ?? null;
                $text = $message['interactive']['button_reply']['title'] ?? '';
            } elseif ($interactiveType === 'list_reply') {
                $payloadId = $message['interactive']['list_reply']['id'] ?? null;
                $text = $message['interactive']['list_reply']['title'] ?? '';
            }
        }

        $session = $this->sessionService->getOrCreate($from);
        $lower = mb_strtolower($text);

        // Authentication flow
        if ($session->current_step === null) {
            // Start OTP
            $otp = $this->otpService->generate($from);
            $this->wa->sendText($from, "أهلاً بك، تم إرسال رمز التحقق\nرمزك هو: {$otp->code}\nصلاحيته 5 دقائق.");
            $this->sessionService->setStep($session, 'awaiting_otp');
            return response()->json(['status' => 'otp_sent']);
        }

        if ($session->current_step === 'awaiting_otp') {
            if (!preg_match('/^[0-9]{6}$/', $lower)) {
                $this->wa->sendText($from, "الرجاء إدخال رمز تحقق صحيح مكوّن من 6 أرقام.");
                return response()->json(['status' => 'awaiting_otp']);
            }

            $valid = $this->otpService->validate($from, $lower);
            if (!$valid) {
                $this->wa->sendText($from, "انتهت صلاحية رمز التحقق أو غير صحيح.");
                return response()->json(['status' => 'otp_failed']);
            }

            $this->sessionService->setStep($session, 'main_menu');
            $this->sendMainMenu($from);
            return response()->json(['status' => 'menu_sent']);
        }

        // Handle main menu selection
        if ($session->current_step === 'main_menu') {
            $id = $payloadId ?? $lower;
            if (in_array($id, ['internal_transfer', 'التحويل الداخلي'])) {
                $this->sessionService->setStep($session, 'internal.await_account');
                $this->wa->sendText($from, "يرجى إدخال رقم حساب المستفيد.");
                return response()->json(['status' => 'await_account']);
            }

            if ($id === 'logout' || $lower === 'تسجيل الخروج') {
                $this->sessionService->clear($session);
                $this->wa->sendText($from, "تم تسجيل الخروج. أرسل مرحبا لبدء جلسة جديدة.");
                return response()->json(['status' => 'logged_out']);
            }

            // For other menu items (not implemented)
            $this->wa->sendText($from, "لا يمكن إتمام العملية حالياً. اختر خياراً آخر.");
            $this->sendMainMenu($from);
            return response()->json(['status' => 'not_implemented']);
        }

        // Internal transfer flow
        if ($session->current_step === 'internal.await_account') {
            $account = Account::where('number', $text)->first();
            if (!$account) {
                $this->wa->sendText($from, "الرجاء إدخال رقم حساب صحيح.");
                return response()->json(['status' => 'invalid_account']);
            }
            $this->sessionService->putTemp($session, 'transfer.to_account_id', $account->id);
            $this->sessionService->setStep($session, 'internal.await_amount');
            $this->wa->sendText($from, "يرجى إدخال المبلغ.");
            return response()->json(['status' => 'await_amount']);
        }

        if ($session->current_step === 'internal.await_amount') {
            $amount = (float) str_replace([',', ' '], ['', ''], $text);
            if ($amount <= 0) {
                $this->wa->sendText($from, "الرجاء إدخال مبلغ صحيح.");
                return response()->json(['status' => 'invalid_amount']);
            }

            $toId = $this->sessionService->getTemp($session, 'transfer.to_account_id');
            $to = $toId ? Account::find($toId) : null;
            $this->sessionService->putTemp($session, 'transfer.amount', $amount);

            $body = "التأكيد على عملية التحويل الداخلي:\n"
                ."إلى: {$to?->number} - {$to?->name}\n"
                ."المبلغ: {$amount}";
            $this->sessionService->setStep($session, 'internal.await_confirm');
            $this->wa->sendButtons($from, $body, [
                ['id' => 'confirm_transfer', 'title' => 'تأكيد'],
                ['id' => 'cancel_transfer', 'title' => 'إلغاء'],
            ]);
            return response()->json(['status' => 'await_confirm']);
        }

        if ($session->current_step === 'internal.await_confirm') {
            $id = $payloadId ?? $lower;
            if ($id === 'cancel_transfer' || $lower === 'إلغاء') {
                $this->sessionService->setStep($session, 'main_menu');
                $this->wa->sendText($from, "تم إلغاء العملية.");
                $this->sendMainMenu($from);
                return response()->json(['status' => 'cancelled']);
            }

            if ($id === 'confirm_transfer' || $lower === 'تأكيد') {
                // Locate sender account by phone for demo: pick first account of a user whose phone matches
                $sender = Account::whereHas('user', function ($q) use ($from) {
                    $q->where('email', $from.'@example.com')->orWhere('name', 'like', '%'.$from.'%');
                })->first() ?? Account::first();

                $to = Account::find($this->sessionService->getTemp($session, 'transfer.to_account_id'));
                $amount = (float) $this->sessionService->getTemp($session, 'transfer.amount');

                try {
                    $result = $this->transferService->internalTransfer($sender, $to, $amount, $sender->currency);
                } catch (\Throwable $e) {
                    Log::error('Transfer failed', ['e' => $e]);
                    $this->wa->sendText($from, "لا يمكن إتمام العملية حالياً");
                    $this->sessionService->setStep($session, 'main_menu');
                    $this->sendMainMenu($from);
                    return response()->json(['status' => 'transfer_failed']);
                }

                $transaction = $result['transaction'];
                $pdfPath = $result['pdf_path'];
                $this->wa->sendText($from, "تمت العملية بنجاح. رقم العملية: {$transaction->id}");
                $this->wa->sendDocumentFromPath($from, $pdfPath, 'receipt.pdf', 'إيصال التحويل');
                $this->sessionService->setStep($session, 'main_menu');
                $this->sendMainMenu($from);
                return response()->json(['status' => 'transfer_success']);
            }
        }

        // Fallback
        $this->wa->sendText($from, "لم أفهم رسالتك. يرجى اختيار من القائمة.");
        $this->sendMainMenu($from);
        return response()->json(['status' => 'fallback']);
    }

    protected function sendMainMenu(string $to): void
    {
        $sections = [
            [
                'title' => 'المعاملات المالية',
                'rows' => [
                    ['id' => 'internal_transfer', 'title' => 'التحويل الداخلي'],
                    ['id' => 'external_transfer', 'title' => 'التحويل الخارجي'],
                    ['id' => 'prepaid_cards', 'title' => 'بطاقات الدفع المسبق'],
                    ['id' => 'international_cards', 'title' => 'البطاقات الدولية'],
                ],
            ],
            [
                'title' => 'الاستعلامات',
                'rows' => [
                    ['id' => 'statements', 'title' => 'كشف الحسابات'],
                    ['id' => 'forms', 'title' => 'النماذج'],
                ],
            ],
            [
                'title' => 'الملف الشخصي',
                'rows' => [
                    ['id' => 'settings', 'title' => 'الإعدادات'],
                    ['id' => 'cb_data', 'title' => 'بيانات المصرف المركزي'],
                    ['id' => 'secure_account', 'title' => 'تأمين حساب الخدمة'],
                    ['id' => 'logout', 'title' => 'تسجيل الخروج'],
                ],
            ],
        ];

        $this->wa->sendList($to, 'استكشف الخدمة', 'اختر خدمة من القائمة:', $sections, 'القائمة');
    }
}


