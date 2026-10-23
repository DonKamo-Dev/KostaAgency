<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ResendEmailService
{
    /**
     * Endpoint oficial de la API de Resend.
     */
    public const API_URL = 'https://api.resend.com/emails';

    /**
     * Envía un correo electrónico usando la API directa de Resend o Laravel Mailer.
     *
     * @param  string  $to  Destinatario
     * @param  string  $subject  Asunto
     * @param  string  $htmlContent  Contenido HTML
     * @param  string|null  $replyTo  Correo de respuesta (opcional)
     * @param  array  $cc  Correos en copia (opcional)
     * @param  Mailable|null  $fallbackMailable  Mailable para envío nativo si no hay API key
     * @return array{success: bool, status: string, id: ?string, error: ?string}
     */
    public static function send(
        string $to,
        string $subject,
        string $htmlContent,
        ?string $replyTo = null,
        array $cc = [],
        ?Mailable $fallbackMailable = null
    ): array {
        $apiKey = trim((string) Setting::get(
            'resend_api_key',
            env('RESEND_API_KEY', config('services.resend.key', ''))
        ));

        $fromAddress = trim((string) Setting::get(
            'resend_from_email',
            env('MAIL_FROM_ADDRESS', 'hola@kostastudio.com')
        ));

        $fromName = trim((string) Setting::get(
            'resend_from_name',
            env('MAIL_FROM_NAME', 'Kosta Studio')
        ));

        // 1. Si tenemos API Key de Resend, enviamos vía HTTP API directa (100% confiable y sin dependencias)
        if (filled($apiKey)) {
            try {
                $payload = [
                    'from' => "{$fromName} <{$fromAddress}>",
                    'to' => array_map('trim', explode(',', $to)),
                    'subject' => $subject,
                    'html' => $htmlContent,
                ];

                if (filled($replyTo)) {
                    $payload['reply_to'] = $replyTo;
                }

                if (! empty($cc)) {
                    $payload['cc'] = array_values(array_filter($cc));
                }

                $response = Http::withToken($apiKey)
                    ->timeout(10)
                    ->acceptJson()
                    ->post(self::API_URL, $payload);

                if ($response->successful()) {
                    $id = $response->json('id');
                    Log::info("Email sent via Resend API to {$to}. ID: {$id}");

                    return [
                        'success' => true,
                        'status' => 'sent',
                        'id' => $id,
                        'error' => null,
                    ];
                }

                $errorMsg = $response->json('message') ?? 'Error de respuesta de Resend API';
                Log::warning("Resend API returned error [{$response->status()}]: {$errorMsg}");

                return [
                    'success' => false,
                    'status' => 'error',
                    'id' => null,
                    'error' => "Resend API ({$response->status()}): {$errorMsg}",
                ];
            } catch (Throwable $e) {
                Log::error("Exception sending email via Resend API: ".$e->getMessage());

                return [
                    'success' => false,
                    'status' => 'exception',
                    'id' => null,
                    'error' => $e->getMessage(),
                ];
            }
        }

        // 2. Fallback: Despacho a través del mailer configurado en Laravel (SMTP, Log, etc.)
        if ($fallbackMailable) {
            try {
                $pending = Mail::to($to);
                if (! empty($cc)) {
                    $pending->cc($cc);
                }
                $pending->send($fallbackMailable);

                return [
                    'success' => true,
                    'status' => 'sent_via_mailer',
                    'id' => null,
                    'error' => null,
                ];
            } catch (Throwable $e) {
                return [
                    'success' => false,
                    'status' => 'mailer_failed',
                    'id' => null,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return [
            'success' => false,
            'status' => 'unconfigured',
            'id' => null,
            'error' => 'No se ha configurado la API Key de Resend ni un transporte de correo alternativo.',
        ];
    }
}
