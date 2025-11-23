<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\DatabaseManager;
use Illuminate\Validation\ValidationException;

class TransferService
{
    public function __construct(
        protected DatabaseManager $db,
        protected PdfService $pdfService
    ) {
    }

    /**
     * Simulate an internal transfer and generate receipt.
     *
     * @return array{transaction: Transaction, pdf_path: string}
     */
    public function internalTransfer(Account $from, Account $to, float $amount, string $currency = 'USD'): array
    {
        if ($from->id === $to->id) {
            throw ValidationException::withMessages(['to' => 'لا يمكن التحويل لنفس الحساب.']);
        }

        if ($amount <= 0) {
            throw ValidationException::withMessages(['amount' => 'الرجاء إدخال مبلغ صحيح.']);
        }

        if ($from->currency !== $to->currency) {
            throw ValidationException::withMessages(['currency' => 'العملة غير متطابقة.']);
        }

        $transaction = $this->db->transaction(function () use ($from, $to, $amount, $currency) {
            // For simulator: allow overdraft but still deduct/add
            $from->balance = (float) $from->balance - $amount;
            $from->save();

            $to->balance = (float) $to->balance + $amount;
            $to->save();

            return Transaction::create([
                'sender_account_id' => $from->id,
                'receiver_account_id' => $to->id,
                'type' => 'internal',
                'amount' => $amount,
                'currency' => $currency,
                'status' => 'success',
                'reference' => 'SIM-'.now()->format('YmdHis'),
            ]);
        });

        $pdfPath = $this->pdfService->generateTransferReceipt($transaction);

        return ['transaction' => $transaction, 'pdf_path' => $pdfPath];
    }
}


