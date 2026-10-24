<?php

namespace App\Services;

use App\Mail\LeadConfirmationMail;
use App\Mail\NewLeadReceivedMail;
use App\Models\Lead;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class LeadNotificationService
{
    /**
     * Procesa y despacha las notificaciones asociadas a un nuevo lead.
     */
    public static function notify(Lead $lead): void
    {
        $meta = $lead->metadata ?? [];
        $meta['notifications'] = [];

        // ── 1. Notificación por Correo Electrónico con Resend / Mailer ───────
        $emailEnabled = (string) Setting::get('automation_email_enabled', '1') === '1';
        $recipientEmail = Setting::get('automation_email_recipient', config('services.kosta.lead_notification_email', 'hola@kostastudio.com'));
        $ccEmailsRaw = Setting::get('automation_email_cc', '');
        $ccList = filled($ccEmailsRaw) ? array_filter(array_map('trim', explode(',', (string) $ccEmailsRaw))) : [];

        if ($emailEnabled && filled($recipientEmail)) {
            try {
                $rawSubject = Setting::get('email_tpl_kosta_subject', '🚀 Nuevo Lead: {{nombre}} ({{servicio}}) — KOSTA Studio');
                $subject = self::replaceMetaPlaceholders($rawSubject, $lead);

                $htmlContent = view('emails.new-lead', ['lead' => $lead])->render();
                $fallbackMailable = new NewLeadReceivedMail($lead);

                $result = ResendEmailService::send(
                    to: $recipientEmail,
                    subject: $subject,
                    htmlContent: $htmlContent,
                    replyTo: $lead->email,
                    cc: $ccList,
                    fallbackMailable: $fallbackMailable
                );

                $meta['notifications']['email'] = [
                    'status' => $result['success'] ? 'sent' : 'failed',
                    'provider' => filled(Setting::get('resend_api_key', env('RESEND_API_KEY'))) ? 'resend_api' : 'laravel_mailer',
                    'resend_id' => $result['id'] ?? null,
                    'recipient' => $recipientEmail,
                    'cc' => $ccEmailsRaw ?: null,
                    'error' => $result['error'] ?? null,
                    'sent_at' => now()->toIso8601String(),
                ];

                Log::info("Lead notification email processed for lead #{$lead->id} (Status: {$result['status']})");
            } catch (Throwable $e) {
                $meta['notifications']['email'] = [
                    'status' => 'failed',
                    'recipient' => $recipientEmail,
                    'error' => $e->getMessage(),
                    'attempted_at' => now()->toIso8601String(),
                ];
                Log::warning("Could not send lead notification email to {$recipientEmail}: ".$e->getMessage());
            }
        } else {
            $meta['notifications']['email'] = [
                'status' => 'disabled',
                'reason' => 'Email notifications disabled or recipient empty',
            ];
        }

        // ── 2. Respuesta Automática al Cliente (Auto-responder) ───────────────
        $autoResponderEnabled = (string) Setting::get('automation_auto_responder_enabled', '1') === '1';
        if ($autoResponderEnabled && filled($lead->email)) {
            try {
                $rawAutoSubject = Setting::get('email_tpl_client_subject', '¡Hemos recibido tu mensaje! — Kosta Studio & Films');
                $autoSubject = self::replaceMetaPlaceholders($rawAutoSubject, $lead);
                $autoHtml = view('emails.lead-confirmation', ['lead' => $lead])->render();
                $autoFallback = new LeadConfirmationMail($lead);

                $autoResult = ResendEmailService::send(
                    to: $lead->email,
                    subject: $autoSubject,
                    htmlContent: $autoHtml,
                    replyTo: $recipientEmail,
                    cc: [],
                    fallbackMailable: $autoFallback
                );

                $meta['notifications']['auto_responder'] = [
                    'status' => $autoResult['success'] ? 'sent' : 'failed',
                    'resend_id' => $autoResult['id'] ?? null,
                    'recipient' => $lead->email,
                    'error' => $autoResult['error'] ?? null,
                    'sent_at' => now()->toIso8601String(),
                ];
                Log::info("Lead auto-responder email processed for {$lead->email}");
            } catch (Throwable $e) {
                $meta['notifications']['auto_responder'] = [
                    'status' => 'failed',
                    'recipient' => $lead->email,
                    'error' => $e->getMessage(),
                    'attempted_at' => now()->toIso8601String(),
                ];
                Log::warning("Could not send lead auto-responder to {$lead->email}: ".$e->getMessage());
            }
        }

        // ── 3. Alertas de WhatsApp (Meta Cloud API & Webhook / Link) ─────────
        $whatsappEnabled = (string) Setting::get('automation_whatsapp_enabled', '1') === '1';
        $kostaWhatsApp = Setting::get('automation_whatsapp_number', config('services.kosta.whatsapp_number', '573113894136'));
        $waMessage = self::formatWhatsAppMessage($lead);
        $waAlertLink = 'https://wa.me/'.$kostaWhatsApp.'?text='.urlencode($waMessage);

        $meta['notifications']['whatsapp'] = [
            'enabled' => $whatsappEnabled,
            'target_phone' => $kostaWhatsApp,
            'direct_alert_url' => $waAlertLink,
            'prepared_at' => now()->toIso8601String(),
            'status' => $whatsappEnabled ? 'prepared' : 'disabled',
        ];

        if ($whatsappEnabled) {
            // A. Envío automático vía Meta WhatsApp Cloud API (Meta Business Developer)
            $metaPhoneId = Setting::get('whatsapp_meta_phone_number_id', env('WHATSAPP_PHONE_NUMBER_ID'));
            $metaToken = Setting::get('whatsapp_meta_access_token', env('WHATSAPP_ACCESS_TOKEN'));

            if (filled($metaPhoneId) && filled($metaToken)) {
                $metaResult = MetaWhatsAppService::sendMessage($kostaWhatsApp, $waMessage);
                $meta['notifications']['whatsapp']['meta_cloud'] = [
                    'status' => $metaResult['status'],
                    'message_id' => $metaResult['message_id'],
                    'error' => $metaResult['error'],
                ];
                if ($metaResult['success']) {
                    $meta['notifications']['whatsapp']['status'] = 'sent_meta_cloud';
                }
            }

            // B. Envío vía Webhook adicional (Make, Zapier, n8n, etc.) si está configurado
            $webhookUrl = Setting::get('automation_whatsapp_webhook_url', config('services.kosta.whatsapp_webhook_url'));
            $webhookToken = Setting::get('automation_whatsapp_webhook_token');

            if (filled($webhookUrl)) {
                try {
                    $client = Http::timeout(6);
                    if (filled($webhookToken)) {
                        $client = $client->withToken($webhookToken);
                    }

                    $response = $client->post($webhookUrl, [
                        'event' => 'lead.created',
                        'lead' => $lead->toArray(),
                        'whatsapp' => [
                            'recipient' => $kostaWhatsApp,
                            'message' => $waMessage,
                            'direct_url' => $waAlertLink,
                        ],
                    ]);

                    $meta['notifications']['whatsapp']['webhook_status'] = $response->successful() ? 'dispatched' : 'error';
                    $meta['notifications']['whatsapp']['webhook_code'] = $response->status();
                    if ($response->successful() && $meta['notifications']['whatsapp']['status'] !== 'sent_meta_cloud') {
                        $meta['notifications']['whatsapp']['status'] = 'sent_webhook';
                    }
                } catch (Throwable $e) {
                    $meta['notifications']['whatsapp']['webhook_status'] = 'failed';
                    $meta['notifications']['whatsapp']['webhook_error'] = $e->getMessage();
                    Log::warning('WhatsApp webhook notification failed: '.$e->getMessage());
                }
            }
        }

        $lead->updateQuietly([
            'metadata' => $meta,
        ]);
    }

    /**
     * Da formato al mensaje de WhatsApp estructurado con todos los datos del lead.
     */
    public static function formatWhatsAppMessage(Lead $lead): string
    {
        $fecha = $lead->created_at?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i');

        return "🚀 *NUEVO PROSPECTO EN KOSTA STUDIO & FILMS*\n\n"
            ."👤 *Nombre:* {$lead->nombre}\n"
            .'🏢 *Tipo de cliente:* '.($lead->tipo_cliente ?: 'No indicado')."\n"
            ."📧 *Email:* {$lead->email}\n"
            .'📱 *Teléfono:* '.($lead->telefono ?: 'No especificado')."\n"
            ."🎯 *Servicio:* {$lead->servicio}\n"
            .'💰 *Presupuesto:* '.($lead->presupuesto ?: 'Sin definir')."\n"
            .'🌐 *Nos conoció por:* '.($lead->como_nos_conocio ?: 'No indicado')."\n"
            ."📅 *Fecha:* {$fecha}\n\n"
            .'📝 *Mensaje:* '.($lead->mensaje ? "\n\"{$lead->mensaje}\"" : 'Sin mensaje adicional')."\n\n"
            .'🔗 *Ver en panel:* '.url('/leads');
    }

    /**
     * Reemplaza las variables dinámicas / metacampos dentro de los textos de plantilla.
     */
    public static function replaceMetaPlaceholders(?string $text, Lead $lead): string
    {
        if (blank($text)) {
            return '';
        }

        $replacements = [
            '{{nombre}}' => (string) ($lead->nombre ?? ''),
            '{{email}}' => (string) ($lead->email ?? ''),
            '{{telefono}}' => (string) ($lead->telefono ?: 'No especificado'),
            '{{servicio}}' => (string) ($lead->servicio ?? ''),
            '{{tipo_cliente}}' => (string) ($lead->tipo_cliente ?: 'No indicado'),
            '{{presupuesto}}' => (string) ($lead->presupuesto ?: 'Sin definir'),
            '{{como_nos_conocio}}' => (string) ($lead->como_nos_conocio ?: 'No indicado'),
            '{{mensaje}}' => (string) ($lead->mensaje ?: ''),
            '{{fecha}}' => $lead->created_at?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i'),
            '{{url_panel}}' => url('/leads'),
            '{{url_whatsapp}}' => $lead->whatsapp_url ?? '#',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $text);
    }
}
