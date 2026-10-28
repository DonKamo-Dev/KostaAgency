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
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AutomationController extends Controller
{
    /**
     * Redirige al módulo de correo (primera subpágina).
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('automations.email');
    }

    /**
     * Subpágina 1: Configuración de Correo Electrónico, Resend y Editor de Plantillas.
     */
    public function email(): View
    {
        $settings = [
            // Resend & Enrutamiento
            'automation_email_enabled' => Setting::get('automation_email_enabled', '1') === '1',
            'automation_email_recipient' => Setting::get('automation_email_recipient', config('services.kosta.lead_notification_email', 'hola@kostastudio.com')),
            'automation_email_cc' => Setting::get('automation_email_cc', ''),
            'automation_auto_responder_enabled' => Setting::get('automation_auto_responder_enabled', '1') === '1',
            'resend_api_key' => Setting::get('resend_api_key', env('RESEND_API_KEY', config('services.resend.key', ''))),
            'resend_from_email' => Setting::get('resend_from_email', env('MAIL_FROM_ADDRESS', 'hola@kostastudio.com')),
            'resend_from_name' => Setting::get('resend_from_name', env('MAIL_FROM_NAME', 'Kosta Studio')),

            // Plantilla 1: Notificación para Kosta
            'email_tpl_kosta_subject' => Setting::get('email_tpl_kosta_subject', '🚀 Nuevo Lead: {{nombre}} ({{servicio}}) — KOSTA Studio'),
            'email_tpl_kosta_badge' => Setting::get('email_tpl_kosta_badge', 'KOSTA STUDIO & FILMS · NUEVO PROSPECTO'),
            'email_tpl_kosta_title' => Setting::get('email_tpl_kosta_title', '¡Nuevo contacto recibido en la web!'),
            'email_tpl_kosta_intro' => Setting::get('email_tpl_kosta_intro', 'Se ha registrado una nueva solicitud desde el formulario de contacto.'),
            'email_tpl_kosta_footer' => Setting::get('email_tpl_kosta_footer', 'Notificación automática generada por KOSTA Platform'),

            // Plantilla 2: Auto-responder para el Cliente
            'email_tpl_client_subject' => Setting::get('email_tpl_client_subject', '¡Hemos recibido tu mensaje! — Kosta Studio & Films'),
            'email_tpl_client_badge' => Setting::get('email_tpl_client_badge', 'KOSTA STUDIO & FILMS'),
            'email_tpl_client_title' => Setting::get('email_tpl_client_title', '¡Hola, {{nombre}}!'),
            'email_tpl_client_subtitle' => Setting::get('email_tpl_client_subtitle', 'Hemos recibido tu mensaje con éxito'),
            'email_tpl_client_p1' => Setting::get('email_tpl_client_p1', 'Gracias por ponerte en contacto con nosotros. Nuestro equipo creativo y técnico ya está revisando los detalles de tu consulta sobre {{servicio}}.'),
            'email_tpl_client_p2' => Setting::get('email_tpl_client_p2', 'Analizamos cada reto de manera personalizada para proponerte la mejor dirección técnica, creativa y comercial. Nos pondremos en contacto contigo en menos de 24 horas laborables.'),
            'email_tpl_client_wa_text' => Setting::get('email_tpl_client_wa_text', '¿Necesitas atención inmediata o tienes un proyecto urgente?'),
            'email_tpl_client_wa_btn' => Setting::get('email_tpl_client_wa_btn', 'Escríbenos directamente por WhatsApp'),
            'email_tpl_client_footer' => Setting::get('email_tpl_client_footer', 'KOSTA STUDIO & FILMS · Creando marcas que se ven, historias que conectan y experiencias que venden.'),
        ];

        $hasResendKey = filled($settings['resend_api_key']);

        $mailInfo = [
            'driver' => $hasResendKey ? 'resend (api)' : config('mail.default'),
            'from_address' => $settings['resend_from_email'],
            'from_name' => $settings['resend_from_name'],
            'has_resend_key' => $hasResendKey,
            'is_live' => $hasResendKey || ! in_array(config('mail.default'), ['log', 'array'], true),
        ];

        $recentNotifications = collect();
        try {
            if (Schema::hasTable('leads') && Schema::hasColumn('leads', 'metadata')) {
                $recentNotifications = Lead::whereNotNull('metadata')
                    ->latest()
                    ->limit(10)
                    ->get();
            }
        } catch (\Throwable $e) {
            $recentNotifications = collect();
        }

        $sampleLead = $this->getSampleLead();

        return view('automations.email', compact(
            'settings',
            'mailInfo',
            'recentNotifications',
            'sampleLead'
        ));
    }

    /**
     * Previsualización HTML en vivo de una plantilla de correo.
     */
    public function previewEmail(string $template): Response
    {
        $sampleLead = $this->getSampleLead();

        if ($template === 'client') {
            $html = view('emails.lead-confirmation', ['lead' => $sampleLead])->render();
        } else {
            $html = view('emails.new-lead', ['lead' => $sampleLead])->render();
        }

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    }

    /**
     * Subpágina 2: Configuración de WhatsApp y Meta Business Developer.
     */
    public function whatsapp(): View
    {
        $settings = [
            'automation_whatsapp_enabled' => Setting::get('automation_whatsapp_enabled', '1') === '1',
            'automation_whatsapp_number' => Setting::get('automation_whatsapp_number', config('services.kosta.whatsapp_number', '573113894136')),
            'whatsapp_meta_phone_number_id' => Setting::get('whatsapp_meta_phone_number_id', env('WHATSAPP_PHONE_NUMBER_ID', '')),
            'whatsapp_meta_access_token' => Setting::get('whatsapp_meta_access_token', env('WHATSAPP_ACCESS_TOKEN', '')),
            'whatsapp_meta_business_account_id' => Setting::get('whatsapp_meta_business_account_id', env('WHATSAPP_BUSINESS_ACCOUNT_ID', '')),
            'automation_whatsapp_webhook_url' => Setting::get('automation_whatsapp_webhook_url', config('services.kosta.whatsapp_webhook_url', '')),
            'automation_whatsapp_webhook_token' => Setting::get('automation_whatsapp_webhook_token', ''),
        ];

        $hasMetaCredentials = filled($settings['whatsapp_meta_phone_number_id']) && filled($settings['whatsapp_meta_access_token']);

        $whatsappInfo = [
            'has_meta' => $hasMetaCredentials,
            'phone_number_id' => $settings['whatsapp_meta_phone_number_id'],
            'has_webhook' => filled($settings['automation_whatsapp_webhook_url']),
        ];

        $recentNotifications = collect();
        try {
            if (Schema::hasTable('leads') && Schema::hasColumn('leads', 'metadata')) {
                $recentNotifications = Lead::whereNotNull('metadata')
                    ->latest()
                    ->limit(10)
                    ->get();
            }
        } catch (\Throwable $e) {
            $recentNotifications = collect();
        }

        $sampleLead = $this->getSampleLead();
        $sampleWaMessage = LeadNotificationService::formatWhatsAppMessage($sampleLead);

        return view('automations.whatsapp', compact(
            'settings',
            'whatsappInfo',
            'recentNotifications',
            'sampleLead',
            'sampleWaMessage'
        ));
    }

    /**
     * Guarda la configuración de Correo, Resend y Plantillas editadas.
     */
    public function updateEmail(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // Credenciales y Enrutamiento
            'automation_email_enabled' => 'nullable|boolean',
            'automation_email_recipient' => 'required|email|max:150',
            'automation_email_cc' => 'nullable|string|max:255',
            'automation_auto_responder_enabled' => 'nullable|boolean',
            'resend_api_key' => 'nullable|string|max:255',
            'resend_from_email' => 'nullable|email|max:150',
            'resend_from_name' => 'nullable|string|max:100',

            // Plantilla Kosta
            'email_tpl_kosta_subject' => 'nullable|string|max:255',
            'email_tpl_kosta_badge' => 'nullable|string|max:100',
            'email_tpl_kosta_title' => 'nullable|string|max:200',
            'email_tpl_kosta_intro' => 'nullable|string|max:500',
            'email_tpl_kosta_footer' => 'nullable|string|max:300',

            // Plantilla Cliente
            'email_tpl_client_subject' => 'nullable|string|max:255',
            'email_tpl_client_badge' => 'nullable|string|max:100',
            'email_tpl_client_title' => 'nullable|string|max:200',
            'email_tpl_client_subtitle' => 'nullable|string|max:200',
            'email_tpl_client_p1' => 'nullable|string|max:2000',
            'email_tpl_client_p2' => 'nullable|string|max:2000',
            'email_tpl_client_wa_text' => 'nullable|string|max:300',
            'email_tpl_client_wa_btn' => 'nullable|string|max:100',
            'email_tpl_client_footer' => 'nullable|string|max:300',
        ]);

        // Guardar enrutamiento & Resend
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

        // Guardar Plantilla Kosta
        $kostaKeys = ['subject', 'badge', 'title', 'intro', 'footer'];
        foreach ($kostaKeys as $k) {
            $formKey = "email_tpl_kosta_{$k}";
            if (isset($validated[$formKey])) {
                Setting::set($formKey, trim((string) $validated[$formKey]));
            }
        }

        // Guardar Plantilla Cliente
        $clientKeys = ['subject', 'badge', 'title', 'subtitle', 'p1', 'p2', 'wa_text', 'wa_btn', 'footer'];
        foreach ($clientKeys as $k) {
            $formKey = "email_tpl_client_{$k}";
            if (isset($validated[$formKey])) {
                Setting::set($formKey, trim((string) $validated[$formKey]));
            }
        }

        $activeTab = in_array($request->input('active_tab'), ['kosta', 'client', 'settings'], true)
            ? $request->input('active_tab')
            : 'kosta';

        return redirect()->route('automations.email', ['tpl' => $activeTab])
            ->with('status', '¡Configuración y plantillas de correo guardadas con éxito!');
    }

    /**
     * Guarda la configuración de WhatsApp y Meta Business Developer.
     */
    public function updateWhatsapp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'automation_whatsapp_enabled' => 'nullable|boolean',
            'automation_whatsapp_number' => 'required|string|max:30',
            'whatsapp_meta_phone_number_id' => 'nullable|string|max:100',
            'whatsapp_meta_access_token' => 'nullable|string|max:1000',
            'whatsapp_meta_business_account_id' => 'nullable|string|max:100',
            'automation_whatsapp_webhook_url' => 'nullable|url|max:2000',
            'automation_whatsapp_webhook_token' => 'nullable|string|max:500',
        ]);

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

        Setting::set('automation_whatsapp_webhook_url', trim((string) ($validated['automation_whatsapp_webhook_url'] ?? '')));
        Setting::set('automation_whatsapp_webhook_token', trim((string) ($validated['automation_whatsapp_webhook_token'] ?? '')));

        return redirect()->route('automations.whatsapp')
            ->with('status', '¡Configuración de Meta WhatsApp Cloud API guardada con éxito!');
    }

    /**
     * Envía un correo de prueba usando Resend o el mailer activo.
     */
    public function testEmail(Request $request): RedirectResponse
    {
        $recipient = $request->input('test_email') ?: Setting::get('automation_email_recipient', config('services.kosta.lead_notification_email', 'hola@kostastudio.com'));
        $testLead = $this->getSampleLead();

        $subject = LeadNotificationService::replaceMetaPlaceholders(
            Setting::get('email_tpl_kosta_subject', '🚀 Nuevo Lead: {{nombre}} ({{servicio}}) — KOSTA Studio'),
            $testLead
        );
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

            return redirect()->route('automations.email', ['tpl' => 'settings'])
                ->with('status', "✅ Correo enviado con éxito a: {$recipient}{$extra}. Revisa tu bandeja de entrada.");
        }

        return redirect()->route('automations.email', ['tpl' => 'settings'])
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

        $testLead = $this->getSampleLead();
        $waMessage = LeadNotificationService::formatWhatsAppMessage($testLead);
        $directUrl = 'https://wa.me/'.$kostaWhatsApp.'?text='.urlencode($waMessage);

        if (filled($metaPhoneId) && filled($metaToken)) {
            $metaResult = MetaWhatsAppService::sendMessage($kostaWhatsApp, $waMessage);

            if ($metaResult['success']) {
                return redirect()->route('automations.whatsapp')
                    ->with('status', "✅ Mensaje de WhatsApp enviado vía Meta Cloud API al +{$kostaWhatsApp} (Message ID: {$metaResult['message_id']}).");
            }

            return redirect()->route('automations.whatsapp')
                ->with('error', "❌ Error de Meta Business WhatsApp: {$metaResult['error']}");
        }

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
                    return redirect()->route('automations.whatsapp')
                        ->with('status', "✅ Webhook de WhatsApp ejecutado con éxito (HTTP {$response->status()}).");
                }

                return redirect()->route('automations.whatsapp')
                    ->with('error', "⚠️ El webhook respondió con error HTTP {$response->status()}: ".\Illuminate\Support\Str::limit($response->body(), 200));
            } catch (Throwable $e) {
                return redirect()->route('automations.whatsapp')
                    ->with('error', "❌ Error al conectar con el Webhook: {$e->getMessage()}");
            }
        }

        return redirect()->route('automations.whatsapp')
            ->with('status', "ℹ️ Enlace directo de alerta generado para WhatsApp (+{$kostaWhatsApp}).")
            ->with('open_whatsapp_url', $directUrl);
    }

    /**
     * Retorna un lead modelo con datos realistas para previsualizaciones.
     */
    protected function getSampleLead(): Lead
    {
        try {
            if (Schema::hasTable('leads')) {
                $latest = Lead::latest()->first();
                if ($latest) {
                    return $latest;
                }
            }
        } catch (\Throwable $e) {
            // Fallback to in-memory sample lead
        }

        return new Lead([
            'id' => 999999,
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
    }
}
