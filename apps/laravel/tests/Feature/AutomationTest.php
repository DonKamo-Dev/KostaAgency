<?php

namespace Tests\Feature;

use App\Mail\LeadConfirmationMail;
use App\Mail\NewLeadReceivedMail;
use App\Models\Lead;
use App\Models\Setting;
use App\Models\User;
use App\Services\LeadNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AutomationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_automations_module(): void
    {
        $response = $this->get(route('automations.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_automations_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('automations.index'));

        $response->assertOk();
        $response->assertSee('Automatizaciones');
        $response->assertSee('Notificación a tu Bandeja de Correo');
        $response->assertSee('Alerta Instantánea a WhatsApp');
    }

    public function test_user_can_update_automation_settings(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('automations.update'), [
            'automation_email_enabled' => 1,
            'automation_email_recipient' => 'notificaciones@kostastudio.com',
            'automation_email_cc' => 'socio@kostastudio.com',
            'automation_auto_responder_enabled' => 1,
            'automation_whatsapp_enabled' => 1,
            'automation_whatsapp_number' => '+57 311 389 4136',
            'automation_whatsapp_webhook_url' => 'https://hook.make.com/custom-webhook',
            'automation_whatsapp_webhook_token' => 'token123',
        ]);

        $response->assertRedirect(route('automations.index'));
        $response->assertSessionHas('status');

        $this->assertEquals('notificaciones@kostastudio.com', Setting::get('automation_email_recipient'));
        $this->assertEquals('socio@kostastudio.com', Setting::get('automation_email_cc'));
        $this->assertEquals('573113894136', Setting::get('automation_whatsapp_number'));
        $this->assertEquals('https://hook.make.com/custom-webhook', Setting::get('automation_whatsapp_webhook_url'));
        $this->assertEquals('token123', Setting::get('automation_whatsapp_webhook_token'));
    }

    public function test_user_can_dispatch_test_email(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        Setting::set('automation_email_recipient', 'test@kostastudio.com');

        $response = $this->actingAs($user)->post(route('automations.test-email'));

        $response->assertRedirect(route('automations.index'));
        $response->assertSessionHas('status');

        Mail::assertSent(NewLeadReceivedMail::class, function ($mail) {
            return $mail->hasTo('test@kostastudio.com');
        });
    }

    public function test_user_can_test_whatsapp_webhook(): void
    {
        Http::fake([
            'https://hook.make.com/*' => Http::response(['success' => true], 200),
        ]);

        $user = User::factory()->create();
        Setting::set('automation_whatsapp_webhook_url', 'https://hook.make.com/test-endpoint');
        Setting::set('automation_whatsapp_number', '573113894136');

        $response = $this->actingAs($user)->post(route('automations.test-whatsapp'));

        $response->assertRedirect(route('automations.index'));
        $response->assertSessionHas('status');

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'hook.make.com')
                && $request['event'] === 'automation.test'
                && isset($request['whatsapp']['recipient']);
        });
    }

    public function test_lead_creation_notifies_configured_email_and_client_auto_responder(): void
    {
        Mail::fake();

        Setting::set('automation_email_enabled', '1');
        Setting::set('automation_email_recipient', 'direccion@kostastudio.com');
        Setting::set('automation_email_cc', 'operaciones@kostastudio.com');
        Setting::set('automation_auto_responder_enabled', '1');

        $lead = Lead::create([
            'nombre' => 'Mateo Gómez',
            'email' => 'mateo@startup.co',
            'telefono' => '3001234567',
            'servicio' => 'Desarrollo Web & E-commerce',
            'mensaje' => 'Quiero cotizar una plataforma web completa.',
        ]);

        LeadNotificationService::notify($lead);

        // 1. Notificación al estudio
        Mail::assertSent(NewLeadReceivedMail::class, function ($mail) {
            return $mail->hasTo('direccion@kostastudio.com')
                && $mail->hasCc('operaciones@kostastudio.com');
        });

        // 2. Auto-responder al cliente
        Mail::assertSent(LeadConfirmationMail::class, function ($mail) {
            return $mail->hasTo('mateo@startup.co');
        });

        // 3. Metadatos de notificación en el lead
        $lead->refresh();
        $this->assertEquals('sent', $lead->metadata['notifications']['email']['status']);
        $this->assertEquals('sent', $lead->metadata['notifications']['auto_responder']['status']);
        $this->assertEquals('573113894136', $lead->metadata['notifications']['whatsapp']['target_phone']);
    }
}
