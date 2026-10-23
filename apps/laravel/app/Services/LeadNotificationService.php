<?php

namespace App\Services;

use App\Mail\LeadConfirmationMail;
use App\Mail\NewLeadReceivedMail;
use App\Models\Lead;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

        // 1. Notificación por Correo Electrónico al Estudio
        $emailEnabled = (string) Setting::get('automation_email_enabled', '1') === '1';
        $recipientEmail = Setting::get('automation_email_recipient', config('services.kosta.lead_notification_email', 'hola@kostastudio.com'));
        $ccEmailsRaw = Setting::get('automation_email_cc', '');

        if ($emailEnabled && filled($recipientEmail)) {
            try {
                $mailable = new NewLeadReceivedMail($lead);
                $pendingMail = Mail::to($recipientEmail);

                if (filled($ccEmailsRaw)) {
                    $ccList = array_filter(array_map('trim', explode(',', (string) $ccEmailsRaw)));
                    if (! empty($ccList)) {
                        $pendingMail->cc($ccList);
                    }
                }

                $pendingMail->send($mailable);

                $meta['notifications']['email'] = [
                    'status' => 'sent',
                    'recipient' => $recipientEmail,
                    'cc' => $ccEmailsRaw ?: null,
                    'sent_at' => now()->toIso8601String(),
                ];
                Log::info("Lead notification email dispatched to {$recipientEmail} for lead #{$lead->id}");
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
                'reason' => 'Email notifications are disabled or recipient is empty',
            ];
        }

        // 2. Respuesta Automática al Cliente (Auto-responder)
        $autoResponderEnabled = (string) Setting::get('automation_auto_responder_enabled', '1') === '1';
        if ($autoResponderEnabled && filled($lead->email)) {
            try {
                Mail::to($lead->email)->send(new LeadConfirmationMail($lead));
                $meta['notifications']['auto_responder'] = [
                    'status' => 'sent',
                    'recipient' => $lead->email,
                    'sent_at' => now()->toIso8601String(),
                ];
                Log::info("Lead auto-responder email dispatched to client {$lead->email}");
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

        // 3. Notificación y Alerta de WhatsApp
        $whatsappEnabled = (string) Setting::get('automation_whatsapp_enabled', '1') === '1';
        $kostaWhatsApp = Setting::get('automation_whatsapp_number', config('services.kosta.whatsapp_number', '573113894136'));
        $waMessage = self::formatWhatsAppMessage($lead);
        $waAlertLink = 'https://wa.me/'.$kostaWhatsApp.'?text='.urlencode($waMessage);

        $meta['notifications']['whatsapp'] = [
            'enabled' => $whatsappEnabled,
            'target_phone' => $kostaWhatsApp,
            'message_preview' => $waMessage,
            'direct_alert_url' => $waAlertLink,
            'prepared_at' => now()->toIso8601String(),
            'status' => $whatsappEnabled ? 'prepared' : 'disabled',
        ];

        // Si hay webhook configurado para automatización externa (Make, Zapier, n8n, Evolution API, Meta Cloud)
        $webhookUrl = Setting::get('automation_whatsapp_webhook_url', config('services.kosta.whatsapp_webhook_url'));
        $webhookToken = Setting::get('automation_whatsapp_webhook_token');

        if ($whatsappEnabled && filled($webhookUrl)) {
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
                $meta['notifications']['whatsapp']['status'] = $response->successful() ? 'sent' : 'webhook_failed';
            } catch (Throwable $e) {
                $meta['notifications']['whatsapp']['webhook_status'] = 'failed';
                $meta['notifications']['whatsapp']['webhook_error'] = $e->getMessage();
                Log::warning('WhatsApp webhook notification failed: '.$e->getMessage());
            }
        }

        Log::info("WhatsApp lead alert prepared for {$kostaWhatsApp}: {$waAlertLink}");

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
}
