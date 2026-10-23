<?php

namespace App\Http\Controllers;

use App\Mail\NewLeadReceivedMail;
use App\Models\Lead;
use App\Models\Setting;
use App\Services\LeadNotificationService;
use App\Services\MetaWhatsAppService;
use App\Services\ResendEmailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
            // Correo & Resend
            'automation_email_enabled' => Setting::get('automation_email_enabled', '1') === '1',
            'automation_email_recipient' => Setting::get('automation_email_recipient', config('services.kosta.lead_notification_email', 'hola@kostastudio.com')),
            'automation_email_cc' => Setting::get('automation_email_cc', ''),
            'automation_auto_responder_enabled' => Setting::get('automation_auto_responder_enabled', '1') === '1',
            'resend_api_key' => Setting::get('resend_api_key', env('RESEND_API_KEY', config('services.resend.key', ''))),
            'resend_from_email' => Setting::get('resend_from_email', env('MAIL_FROM_ADDRESS', 'hola@kostastudio.com')),
            'resend_from_name' => Setting::get('resend_from_name', env('MAIL_FROM_NAME', 'Kosta Studio')),

            // WhatsApp & Meta Business Developer
            'automation_whatsapp_enabled' => Setting::get('automation_whatsapp_enabled', '1') === '1',
            'automation_whatsapp_number' => Setting::get('automation_whatsapp_number', config('services.kosta.whatsapp_number', '573113894136')),
            'whatsapp_meta_phone_number_id' => Setting::get('whatsapp_meta_phone_number_id', env('WHATSAPP_PHONE_NUMBER_ID', '')),
            'whatsapp_meta_access_token' => Setting::get('whatsapp_meta_access_token', env('WHATSAPP_ACCESS_TOKEN', '')),
            'whatsapp_meta_business_account_id' => Setting::get('whatsapp_meta_business_account_id', env('WHATSAPP_BUSINESS_ACCOUNT_ID', '')),

            // Webhook opcional
            'automation_whatsapp_webhook_url' => Setting::get('automation_whatsapp_webhook_url', config('services.kosta.whatsapp_webhook_url', '')),
            'automation_whatsapp_webhook_token' => Setting::get('automation_whatsapp_webhook_token', ''),
        ];

        // Estado del servicio de correo
        $hasResendKey = filled($settings['resend_api_key']);
        $hasMetaCredentials = filled($settings['whatsapp_meta_phone_number_id']) && filled($settings['whatsapp_meta_access_token']);

        $mailInfo = [
            'driver' => $hasResendKey ? 'resend (api)' : config('mail.default'),
            'from_address' => $settings['resend_from_email'],
            'from_name' => $settings['resend_from_name'],
            'has_resend_key' => $hasResendKey,
            'is_live' => $hasResendKey || ! in_array(config('mail.default'), ['log', 'array'], true),
        ];

        $whatsappInfo = [
            'has_meta' => $hasMetaCredentials,
            'phone_number_id' => $settings['whatsapp_meta_phone_number_id'],
            'has_webhook' => filled($settings['automation_whatsapp_webhook_url']),
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
            'whatsappInfo',
            'recentNotifications',
            'sampleLead',
            'sampleWaMessage'
        ));
    }

    /**
     * Guarda la configuración de automatizaciones (Resend y Meta Cloud API).
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // Correo & Resend
            'automation_email_enabled' => 'nullable|boolean',
            'automation_email_recipient' => 'required|email|max:150',
            'automation_email_cc' => 'nullable|string|max:255',
            'automation_auto_responder_enabled' => 'nullable|boolean',
            'resend_api_key' => 'nullable|string|max:255',
            'resend_from_email' => 'nullable|email|max:150',
            'resend_from_name' => 'nullable|string|max:100',

            // WhatsApp & Meta
            'automation_whatsapp_enabled' => 'nullable|boolean',
            'automation_whatsapp_number' => 'required|string|max:30',
            'whatsapp_meta_phone_number_id' => 'nullable|string|max:100',
            'whatsapp_meta_access_token' => 'nullable|string|max:1000',
            'whatsapp_meta_business_account_id' => 'nullable|string|max:100',

            // Webhook opcional
            'automation_whatsapp_webhook_url' => 'nullable|url|max:2000',
            'automation_whatsapp_webhook_token' => 'nullable|string|max:500',
        ]);

        // Guardar email & Resend
        Setting::set('automation_email_enabled', $request->boolean('automation_email_enabled') ? '1' : '0');
        Setting::set('automation_email_recipient', trim($validated['automation_email_recipient']));
        Setting::set('automation_email_cc', trim((string) ($validated['automation_email_cc'] ?? '')));
        Setting::set('automation_auto_responder_enabled', $request->boolean('automation_auto_responder_enabled') ? '1' : '0');

        if (isset($validated['resend_api_key'])) {
            Setting::set('resend_api_key', trim((string) $validated['resend_api_key']));
        }
        if (filled($validated['resend_from_email'] ?? '')) {
            Setting::set('resend_from_email', trim($validated['resend_from_email']));
        }
        if (filled($validated['resend_from_name'] ?? '')) {
            Setting::set('resend_from_name', trim($validated['resend_from_name']));
        }

        // Guardar WhatsApp & Meta Cloud API
        Setting::set('automation_whatsapp_enabled', $request->boolean('automation_whatsapp_enabled') ? '1' : '0');
        $cleanPhone = preg_replace('/\D+/', '', (string) $validated['automation_whatsapp_number']);
        Setting::set('automation_whatsapp_number', $cleanPhone ?: $validated['automation_whatsapp_number']);

        if (isset($validated['whatsapp_meta_phone_number_id'])) {
            Setting::set('whatsapp_meta_phone_number_id', trim((string) $validated['whatsapp_meta_phone_number_id']));
        }
        if (isset($validated['whatsapp_meta_access_token'])) {
            Setting::set('whatsapp_meta_access_token', trim((string) $validated['whatsapp_meta_access_token']));
        }
        if (isset($validated['whatsapp_meta_business_account_id'])) {
            Setting::set('whatsapp_meta_business_account_id', trim((string) $validated['whatsapp_meta_business_account_id']));
        }

        // Webhook
        Setting::set('automation_whatsapp_webhook_url', trim((string) ($validated['automation_whatsapp_webhook_url'] ?? '')));
        Setting::set('automation_whatsapp_webhook_token', trim((string) ($validated['automation_whatsapp_webhook_token'] ?? '')));

        return redirect()->route('automations.index')
            ->with('status', '¡Configuración de Resend y Meta WhatsApp guardada exitosamente!');
    }

    /**
     * Envía un correo de prueba usando Resend o el mailer activo.
     */
    public function testEmail(Request $request): RedirectResponse
    {
        $recipient = $request->input('test_email') ?: Setting::get('automation_email_recipient', config('services.kosta.lead_notification_email', 'hola@kostastudio.com'));

        $testLead = new Lead([
            'id' => 999999,
            'nombre' => 'Test Lead (Resend Kosta Studio)',
            'email' => 'prospecto.prueba@kostastudio.com',
            'telefono' => '+57 311 000 0000',
            'tipo_cliente' => 'Test Resend API',
            'servicio' => 'Prueba de Entrega con Resend',
            'presupuesto' => '$5M - $10M COP',
            'como_nos_conocio' => 'Panel de Automatizaciones',
            'mensaje' => 'Este correo de prueba confirma que la integración con Resend y Render está funcionando y los correos llegan a tu bandeja.',
            'created_at' => now(),
        ]);

        $subject = '🚀 [TEST RESEND] Nuevo Lead de Prueba — Kosta Studio';
        $htmlContent = view('emails.new-lead', ['lead' => $testLead])->render();
        $fallbackMailable = new NewLeadReceivedMail($testLead);

        $result = ResendEmailService::send(
            to: $recipient,
            subject: $subject,
            htmlContent: $htmlContent,
            replyTo: 'hola@kostastudio.com',
            cc: [],
            fallbackMailable: $fallbackMailable
        );

        if ($result['success']) {
            $extra = $result['id'] ? " (Resend ID: {$result['id']})" : '';

            return redirect()->route('automations.index')
                ->with('status', "✅ Correo enviado con éxito a: {$recipient}{$extra}. Revisa tu bandeja de entrada o spam.");
        }

        return redirect()->route('automations.index')
            ->with('error', "❌ Error enviando correo: {$result['error']}");
    }

    /**
     * Prueba el canal de WhatsApp mediante Meta Cloud API o Webhook/Enlace.
     */
    public function testWhatsapp(Request $request): RedirectResponse
    {
        $kostaWhatsApp = Setting::get('automation_whatsapp_number', config('services.kosta.whatsapp_number', '573113894136'));
        $metaPhoneId = Setting::get('whatsapp_meta_phone_number_id', env('WHATSAPP_PHONE_NUMBER_ID'));
        $metaToken = Setting::get('whatsapp_meta_access_token', env('WHATSAPP_ACCESS_TOKEN'));
        $webhookUrl = Setting::get('automation_whatsapp_webhook_url');

        $testLead = new Lead([
            'id' => 999999,
            'nombre' => 'Test Lead (Meta WhatsApp)',
            'email' => 'test@kostastudio.com',
            'telefono' => '+57 311 389 4136',
            'tipo_cliente' => 'Prueba Meta Business',
            'servicio' => 'Desarrollo Web & Films',
            'presupuesto' => '$10M+',
            'como_nos_conocio' => 'Meta Developer Console',
            'mensaje' => 'Alerta de prueba enviada desde el Módulo de Automatizaciones vía Meta WhatsApp Cloud API.',
            'created_at' => now(),
        ]);

        $waMessage = LeadNotificationService::formatWhatsAppMessage($testLead);
        $directUrl = 'https://wa.me/'.$kostaWhatsApp.'?text='.urlencode($waMessage);

        // 1. Si Meta Cloud API está configurada, despachar llamada a Meta Graph API
        if (filled($metaPhoneId) && filled($metaToken)) {
            $metaResult = MetaWhatsAppService::sendMessage($kostaWhatsApp, $waMessage);

            if ($metaResult['success']) {
                return redirect()->route('automations.index')
                    ->with('status', "✅ Mensaje de WhatsApp enviado vía Meta Cloud API al +{$kostaWhatsApp} (Message ID: {$metaResult['message_id']}).");
            }

            return redirect()->route('automations.index')
                ->with('error', "❌ Error de Meta Business WhatsApp: {$metaResult['error']}");
        }

        // 2. Si hay Webhook, probar Webhook
        if (filled($webhookUrl)) {
            try {
                $webhookToken = Setting::get('automation_whatsapp_webhook_token');
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
                    ->with('error', "❌ Error al conectar con el Webhook: {$e->getMessage()}");
            }
        }

        // 3. Fallback: Enlace directo
        return redirect()->route('automations.index')
            ->with('status', "ℹ️ Enlace directo de alerta generado para WhatsApp (+{$kostaWhatsApp}).")
            ->with('open_whatsapp_url', $directUrl);
    }
}
