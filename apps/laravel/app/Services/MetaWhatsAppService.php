<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class MetaWhatsAppService
{
    /**
     * Endpoint base de la API de WhatsApp Cloud (Meta Graph API).
     */
    public const GRAPH_API_VERSION = 'v21.0';

    /**
     * Envía un mensaje de texto mediante Meta WhatsApp Cloud API.
     *
     * @param  string  $to  Número receptor con código de país (ej. 573113894136)
     * @param  string  $message  Contenido del mensaje estructurado
     * @return array{success: bool, status: string, message_id: ?string, error: ?string, code: ?int}
     */
    public static function sendMessage(string $to, string $message): array
    {
        $phoneNumberId = trim((string) Setting::get(
            'whatsapp_meta_phone_number_id',
            env('WHATSAPP_PHONE_NUMBER_ID', '')
        ));

        $accessToken = trim((string) Setting::get(
            'whatsapp_meta_access_token',
            env('WHATSAPP_ACCESS_TOKEN', '')
        ));

        if (blank($phoneNumberId) || blank($accessToken)) {
            return [
                'success' => false,
                'status' => 'unconfigured',
                'message_id' => null,
                'error' => 'Faltan credenciales de Meta Business: Phone Number ID o Access Token no configurados.',
                'code' => null,
            ];
        }

        $cleanRecipient = preg_replace('/\D+/', '', $to);
        if (strlen($cleanRecipient) === 10 && str_starts_with($cleanRecipient, '3')) {
            $cleanRecipient = '57'.$cleanRecipient;
        }

        $url = "https://graph.facebook.com/".self::GRAPH_API_VERSION."/{$phoneNumberId}/messages";

        try {
            $response = Http::withToken($accessToken)
                ->timeout(8)
                ->acceptJson()
                ->post($url, [
                    'messaging_product' => 'whatsapp',
                    'recipient_type' => 'individual',
                    'to' => $cleanRecipient,
                    'type' => 'text',
                    'text' => [
                        'preview_url' => false,
                        'body' => $message,
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $messageId = $data['messages'][0]['id'] ?? null;

                Log::info("Meta WhatsApp Cloud API message sent to {$cleanRecipient}. ID: {$messageId}");

                return [
                    'success' => true,
                    'status' => 'sent',
                    'message_id' => $messageId,
                    'error' => null,
                    'code' => $response->status(),
                ];
            }

            $errorData = $response->json('error', []);
            $errorMessage = $errorData['message'] ?? 'Error desconocido de Meta Graph API';
            $errorCode = $errorData['code'] ?? $response->status();

            Log::warning("Meta WhatsApp Cloud API error: [{$errorCode}] {$errorMessage}");

            return [
                'success' => false,
                'status' => 'error',
                'message_id' => null,
                'error' => "Meta API ({$errorCode}): {$errorMessage}",
                'code' => $errorCode,
            ];
        } catch (Throwable $e) {
            Log::error("Exception in Meta WhatsApp Cloud API: ".$e->getMessage());

            return [
                'success' => false,
                'status' => 'exception',
                'message_id' => null,
                'error' => $e->getMessage(),
                'code' => null,
            ];
        }
    }
}
