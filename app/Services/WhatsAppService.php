<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $baseUrl = 'https://whatify.in/api';
    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function sendTextMessage($phone, $message, $header = null, $footer = null, $buttons = [])
    {
        $payload = [
            'phone' => $phone,
            'message' => $message,
        ];

        if ($header) $payload['header'] = $header;
        if ($footer) $payload['footer'] = $footer;
        if (!empty($buttons)) $payload['buttons'] = $buttons;

        return $this->post('/send', $payload);
    }

    public function sendMedia($phone, $mediaType, $mediaUrl, $caption = null, $fileName = null)
    {
        $payload = [
            'phone' => $phone,
            'media_type' => $mediaType,
            'media_url' => $mediaUrl,
        ];

        if ($caption) $payload['caption'] = $caption;
        if ($fileName) $payload['file_name'] = $fileName;

        return $this->post('/send/media', $payload);
    }

    public function sendTemplate($phone, $templateData)
    {
        $payload = [
            'phone' => $phone,
            'template' => $templateData,
        ];

        return $this->post('/send/template', $payload);
    }

    protected function post($endpoint, $data)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . $endpoint, $data);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('WhatsApp API Error: ' . $e->getMessage());
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }
}
