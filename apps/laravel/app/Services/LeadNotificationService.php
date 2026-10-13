<?php

namespace App\Services;

use App\Mail\NewLeadReceivedMail;
use App\Models\Lead;
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

        // 1. Notificación por Correo Electrónico
        $recipientEmail = config('services.kosta.lead_notification_email', 'kostastudioco@gmail.com');
        try {
            Mail::to($recipientEmail)->send(new NewLeadReceivedMail($lead));
            $meta['notifications']['email'] = [
                'status' => 'sent',
                'recipient' => $recipientEmail,
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

        // 2. Base de Notificación por WhatsApp
        $kostaWhatsApp = config('services.kosta.whatsapp_number', '573113894136');
        $waMessage = self::formatWhatsAppMessage($lead);
        $waAlertLink = 'https://wa.me/'.$kostaWhatsApp.'?text='.urlencode($waMessage);

        $meta['notifications']['whatsapp'] = [
            'target_phone' => $kostaWhatsApp,
            'message_preview' => $waMessage,
            'direct_alert_url' => $waAlertLink,
            'prepared_at' => now()->toIso8601String(),
            'status' => 'prepared',
        ];

        // Si hay webhook configurado para integración automatizada (Evolution API, Twilio, Meta Cloud API, etc.)
        $webhookUrl = config('services.kosta.whatsapp_webhook_url');
        if (filled($webhookUrl)) {
            try {
                $response = Http::timeout(5)->post($webhookUrl, [
                    'event' => 'lead.created',
                    'lead' => $lead->toArray(),
                    'whatsapp' => [
                        'recipient' => $kostaWhatsApp,
                        'message' => $waMessage,
                    ],
                ]);

                $meta['notifications']['whatsapp']['webhook_status'] = $response->successful() ? 'dispatched' : 'error';
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
