<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    public function __construct(
        protected ?string $token = null,
        protected ?string $phoneNumberId = null,
        protected ?string $apiBase = null,
    ) {
        $this->token = $this->token ?? (string) config('services.whatsapp.token');
        $this->phoneNumberId = $this->phoneNumberId ?? (string) config('services.whatsapp.phone_number_id');
        $this->apiBase = $this->apiBase ?? (string) config('services.whatsapp.base_uri', 'https://graph.facebook.com/v19.0');
    }

    protected function endpoint(string $path): string
    {
        return rtrim($this->apiBase, '/').'/'.$this->phoneNumberId.'/'.ltrim($path, '/');
    }

    protected function client()
    {
        return Http::withToken($this->token)
            ->acceptJson()
            ->asJson()
            ->timeout(20);
    }

    public function sendText(string $to, string $text): Response
    {
        return $this->client()->post($this->endpoint('messages'), [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'text',
            'text' => ['body' => $text],
        ]);
    }

    public function sendButtons(string $to, string $body, array $buttons): Response
    {
        // $buttons = [['id' => 'confirm', 'title' => 'تأكيد'], ...]
        return $this->client()->post($this->endpoint('messages'), [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'button',
                'body' => ['text' => $body],
                'action' => [
                    'buttons' => array_map(function ($b) {
                        return [
                            'type' => 'reply',
                            'reply' => ['id' => $b['id'], 'title' => $b['title']],
                        ];
                    }, $buttons),
                ],
            ],
        ]);
    }

    public function sendList(string $to, string $header, string $body, array $sections, string $buttonText = 'القائمة'): Response
    {
        // $sections = [['title' => 'المعاملات المالية', 'rows' => [['id'=>'internal_transfer','title'=>'التحويل الداخلي'], ...]]]
        return $this->client()->post($this->endpoint('messages'), [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'list',
                'header' => ['type' => 'text', 'text' => $header],
                'body' => ['text' => $body],
                'footer' => ['text' => 'Tap to select an item'],
                'action' => [
                    'button' => $buttonText,
                    'sections' => $sections,
                ],
            ],
        ]);
    }

    public function uploadMediaFromPath(string $filePath, string $mime): array
    {
        $response = Http::withToken($this->token)
            ->attach('file', fopen($filePath, 'r'), basename($filePath))
            ->post($this->endpoint('media'), [
                'messaging_product' => 'whatsapp',
                'type' => $mime,
            ]);

        return $response->json();
    }

    public function sendDocumentFromPath(string $to, string $filePath, string $filename, string $caption = ''): Response
    {
        $mime = mime_content_type($filePath) ?: 'application/pdf';
        $upload = $this->uploadMediaFromPath($filePath, $mime);
        $mediaId = $upload['id'] ?? null;

        return $this->client()->post($this->endpoint('messages'), [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'document',
            'document' => [
                'id' => $mediaId,
                'filename' => $filename,
                'caption' => $caption,
            ],
        ]);
    }
}


