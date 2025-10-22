<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WahaClient
{
    protected string $session;

    public function __construct(?string $session = null)
    {
        $this->session = $session ?: config('waha.session');
    }

    /**
     * Format nomor jadi chatId WhatsApp (contoh: 08123 → 628123@c.us)
     */
    public function toChatId(?string $phone): ?string
    {
        if (!$phone) return null;

        $digits = preg_replace('/\D+/', '', $phone);
        if (Str::startsWith($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        }

        return $digits . '@c.us';
    }

    /**
     * Kirim pesan teks ke WA melalui WAHA API
     */
    public function sendText(string $chatId, string $text): array
    {
        $payload = [
            "chatId" => $chatId,
            "reply_to" => null,
            "text" => $text,
            "linkPreview" => true,
            "linkPreviewHighQuality" => false,
            "session" => config('waha.session'),
        ];

        try {
            $resp = Http::withHeaders([
                    'accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Api-Key' => config('waha.api_key'),
                ])
                ->withOptions([
                    'verify' => false, // skip SSL check, sama seperti curl -k
                    'timeout' => 30,
                ])
                ->post(config('waha.base_url') . '/api/sendText', $payload);

            if ($resp->failed()) {
                return [
                    'ok' => false,
                    'status' => $resp->status(),
                    'body' => $resp->json() ?? $resp->body(),
                ];
            }

            return [
                'ok' => true,
                'status' => $resp->status(),
                'body' => $resp->json(),
            ];
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Cek status koneksi session WAHA
     */
    public function checkConnection(): array
    {
        try {
            $resp = Http::withHeaders([
                    'X-Api-Key' => config('waha.api_key'),
                ])
                ->withOptions(['verify' => false])
                ->get(config('waha.base_url') . '/api/sessions/' . $this->session);

            if ($resp->failed()) {
                return [
                    'ok' => false,
                    'status' => $resp->status(),
                    'body' => $resp->json() ?? $resp->body(),
                ];
            }

            return [
                'ok' => true,
                'status' => $resp->status(),
                'body' => $resp->json(),
            ];
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
