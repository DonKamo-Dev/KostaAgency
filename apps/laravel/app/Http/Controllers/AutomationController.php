<?php

namespace App\Http\Controllers;

use App\Mail\NewLeadReceivedMail;
use App\Models\Lead;
use App\Models\Setting;
use App\Services\LeadNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class AutomationController extends Controller
{
    /**
     * Muestra la vista principal del módulo de automatizaciones.
     */
    public function index(): View
    {
        $settings = [
            'automation_email_enabled' => Setting::get('automation_email_enabled', '1') === '1',
            'automation_email_recipient' => Setting::get('automation_email_recipient', config('services.kosta.lead_notification_email', 'hola@kostastudio.com')),
            'automation_email_cc' => Setting::get('automation_email_cc', ''),
            'automation_auto_responder_enabled' => Setting::get('automation_auto_responder_enabled', '1') === '1',
            'automation_whatsapp_enabled' => Setting::get('automation_whatsapp_enabled', '1') === '1',
            'automation_whatsapp_number' => Setting::get('automation_whatsapp_number', config('services.kosta.whatsapp_number', '573113894136')),
            'automation_whatsapp_webhook_url' => Setting::get('automation_whatsapp_webhook_url', config('services.kosta.whatsapp_webhook_url', '')),
            'automation_whatsapp_webhook_token' => Setting::get('automation_whatsapp_webhook_token', ''),
        ];

        // Estado del servicio de correo
        $mailInfo = [
            'driver' => config('mail.default'),
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
            'is_live' => ! in_array(config('mail.default'), ['log', 'array'], true),
        ];

        // Últimos 10 leads con historial de notificación
        $recentNotifications = Lead::whereNotNull('metadata')
            ->latest()
            ->limit(10)
            ->get();

        // Lead modelo para previsualización
        $sampleLead = Lead::latest()->first() ?? new Lead([
            'nombre' => 'Carolina Mendoza',
            'email' => 'carolina@luxurytravel.co',
            'telefono' => '+57 300 123 4567',
            'tipo_cliente' => 'Empresa / Marca',
            'servicio' => 'Diseño & Desarrollo Web',
            'presupuesto' => '$5M - $10M COP',
            'como_nos_conocio' => 'Instagram',
            'mensaje' => 'Hola equipo de Kosta, queremos renovar nuestro portal de reservas exclusivo y nos encantó su trabajo con Classy Cartagena.',
            'created_at' => now(),
        ]);

        $sampleWaMessage = LeadNotificationService::formatWhatsAppMessage($sampleLead);

        return view('automations.index', compact(
            'settings',
            'mailInfo',
            'recentNotifications',
            'sampleLead',
            'sampleWaMessage'
        ));
    }

    /**
     * Guarda la configuración de automatizaciones.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'automation_email_enabled' => 'nullable|boolean',
            'automation_email_recipient' => 'required|email|max:150',
            'automation_email_cc' => 'nullable|string|max:255',
            'automation_auto_responder_enabled' => 'nullable|boolean',
            'automation_whatsapp_enabled' => 'nullable|boolean',
            'automation_whatsapp_number' => 'required|string|max:30',
            'automation_whatsapp_webhook_url' => 'nullable|url|max:2000',
            'automation_whatsapp_webhook_token' => 'nullable|string|max:500',
        ]);

        Setting::set('automation_email_enabled', $request->boolean('automation_email_enabled') ? '1' : '0');
        Setting::set('automation_email_recipient', trim($validated['automation_email_recipient']));
        Setting::set('automation_email_cc', trim((string) ($validated['automation_email_cc'] ?? '')));
        Setting::set('automation_auto_responder_enabled', $request->boolean('automation_auto_responder_enabled') ? '1' : '0');

        Setting::set('automation_whatsapp_enabled', $request->boolean('automation_whatsapp_enabled') ? '1' : '0');
        $cleanPhone = preg_replace('/\D+/', '', (string) $validated['automation_whatsapp_number']);
        Setting::set('automation_whatsapp_number', $cleanPhone ?: $validated['automation_whatsapp_number']);
        Setting::set('automation_whatsapp_webhook_url', trim((string) ($validated['automation_whatsapp_webhook_url'] ?? '')));
        Setting::set('automation_whatsapp_webhook_token', trim((string) ($validated['automation_whatsapp_webhook_token'] ?? '')));

        return redirect()->route('automations.index')
            ->with('status', '¡Configuración de automatizaciones guardada exitosamente!');
    }

    /**
     * Envía un correo de prueba para verificar la entrega a la bandeja.
     */
    public function testEmail(Request $request): RedirectResponse
    {
        $recipient = $request->input('test_email') ?: Setting::get('automation_email_recipient', config('services.kosta.lead_notification_email', 'hola@kostastudio.com'));

        $testLead = new Lead([
            'id' => 999999,
            'nombre' => 'Test Lead (Automatización Kosta)',
            'email' => 'prospecto.prueba@kostastudio.com',
            'telefono' => '+57 311 000 0000',
            'tipo_cliente' => 'Test Automatización',
            'servicio' => 'Prueba de Sistema de Notificaciones',
            'presupuesto' => '$3M - $5M COP',
            'como_nos_conocio' => 'Panel de Control',
            'mensaje' => 'Este es un mensaje de prueba generado desde el Módulo de Automatizaciones para verificar la llegada a tu bandeja de correo.',
            'created_at' => now(),
        ]);

        try {
            Mail::to($recipient)->send(new NewLeadReceivedMail($testLead));

            return redirect()->route('automations.index')
                ->with('status', "✅ Correo de prueba enviado con éxito a: {$recipient}. Revisa tu bandeja de entrada o spam.");
        } catch (Throwable $e) {
            return redirect()->route('automations.index')
                ->with('error', "❌ Error enviando correo a {$recipient}: {$e->getMessage()}");
        }
    }

    /**
     * Prueba el canal de WhatsApp (webhook o enlace directo).
     */
    public function testWhatsapp(Request $request): RedirectResponse
    {
        $kostaWhatsApp = Setting::get('automation_whatsapp_number', config('services.kosta.whatsapp_number', '573113894136'));
        $webhookUrl = Setting::get('automation_whatsapp_webhook_url');
        $webhookToken = Setting::get('automation_whatsapp_webhook_token');

        $testLead = new Lead([
            'id' => 999999,
            'nombre' => 'Prospecto de Prueba',
            'email' => 'test@kostastudio.com',
            'telefono' => '+57 311 389 4136',
            'tipo_cliente' => 'Prueba WhatsApp',
            'servicio' => 'Desarrollo Web & Films',
            'presupuesto' => '$10M+',
            'como_nos_conocio' => 'Prueba desde Panel',
            'mensaje' => 'Alerta de prueba enviada desde el Módulo de Automatizaciones.',
            'created_at' => now(),
        ]);

        $waMessage = LeadNotificationService::formatWhatsAppMessage($testLead);
        $directUrl = 'https://wa.me/'.$kostaWhatsApp.'?text='.urlencode($waMessage);

        if (filled($webhookUrl)) {
            try {
                $client = Http::timeout(6);
                if (filled($webhookToken)) {
                    $client = $client->withToken($webhookToken);
                }

                $response = $client->post($webhookUrl, [
                    'event' => 'automation.test',
                    'lead' => $testLead->toArray(),
                    'whatsapp' => [
                        'recipient' => $kostaWhatsApp,
                        'message' => $waMessage,
                        'direct_url' => $directUrl,
                    ],
                ]);

                if ($response->successful()) {
                    return redirect()->route('automations.index')
                        ->with('status', "✅ Webhook de WhatsApp ejecutado con éxito (HTTP {$response->status()}).");
                }

                return redirect()->route('automations.index')
                    ->with('error', "⚠️ El webhook respondió con error HTTP {$response->status()}: ".\Illuminate\Support\Str::limit($response->body(), 200));
            } catch (Throwable $e) {
                return redirect()->route('automations.index')
                    ->with('error', "❌ Error al conectar con el Webhook de WhatsApp: {$e->getMessage()}");
            }
        }

        // Si no hay webhook, redirigir abriendo el enlace directo
        return redirect()->route('automations.index')
            ->with('status', "ℹ️ Enlace directo de alerta generado para WhatsApp (+{$kostaWhatsApp}).")
            ->with('open_whatsapp_url', $directUrl);
    }
}
