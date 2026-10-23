<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\Setting;
use App\Models\User;
use App\Services\LeadNotificationService;
use App\Services\MetaWhatsAppService;
use App\Services\ResendEmailService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AutomationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_automations_subpages(): void
    {
        $response = $this->get(route('automations.email'));
        $response->assertRedirect(route('login'));

        $responseWa = $this->get(route('automations.whatsapp'));
        $responseWa->assertRedirect(route('login'));
    }

    public function test_automations_index_redirects_to_email_subpage(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('automations.index'));
        $response->assertRedirect(route('automations.email'));
    }

    public function test_authenticated_user_can_view_email_subpage(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('automations.email'));

        $response->assertOk();
        $response->assertSee('Automatizaciones: Correo');
        $response->assertSee('Configuración de Resend');
        $response->assertSee('Auto-responder de cortesía');
        $response->assertDontSee('Meta Phone Number ID');
    }

    public function test_authenticated_user_can_view_whatsapp_subpage(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('automations.whatsapp'));

        $response->assertOk();
        $response->assertSee('Automatizaciones: WhatsApp');
        $response->assertSee('Meta WhatsApp Cloud API');
        $response->assertSee('Meta Phone Number ID');
        $response->assertDontSee('API Key de Resend');
    }

    public function test_user_can_update_email_settings_on_dedicated_subpage(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('automations.email.update'), [
            'automation_email_enabled' => 1,
            'automation_email_recipient' => 'notificaciones@kostastudio.com',
            'automation_email_cc' => 'socio@kostastudio.com',
            'automation_auto_responder_enabled' => 1,
            'resend_api_key' => 're_test_key_12345',
            'resend_from_email' => 'hola@kostastudio.com',
            'resend_from_name' => 'Kosta Studio',
        ]);

        $response->assertRedirect(route('automations.email'));
        $response->assertSessionHas('status');

        $this->assertEquals('notificaciones@kostastudio.com', Setting::get('automation_email_recipient'));
        $this->assertEquals('re_test_key_12345', Setting::get('resend_api_key'));
        $this->assertEquals('hola@kostastudio.com', Setting::get('resend_from_email'));
    }

    public function test_user_can_update_whatsapp_settings_on_dedicated_subpage(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('automations.whatsapp.update'), [
            'automation_whatsapp_enabled' => 1,
            'automation_whatsapp_number' => '+57 311 389 4136',
            'whatsapp_meta_phone_number_id' => '105934529384729',
            'whatsapp_meta_access_token' => 'EAAGm0PX4ZCBO_test_token',
            'whatsapp_meta_business_account_id' => 'waba_999999',
            'automation_whatsapp_webhook_url' => 'https://hook.make.com/custom',
        ]);

        $response->assertRedirect(route('automations.whatsapp'));
        $response->assertSessionHas('status');

        $this->assertEquals('573113894136', Setting::get('automation_whatsapp_number'));
        $this->assertEquals('105934529384729', Setting::get('whatsapp_meta_phone_number_id'));
        $this->assertEquals('EAAGm0PX4ZCBO_test_token', Setting::get('whatsapp_meta_access_token'));
    }

    public function test_resend_email_service_dispatches_http_call_when_api_key_present(): void
    {
        Http::fake([
            'https://api.resend.com/emails' => Http::response(['id' => 're_msg_123456'], 200),
        ]);

        Setting::set('resend_api_key', 're_valid_key');
        Setting::set('resend_from_email', 'hola@kostastudio.com');

        $result = ResendEmailService::send(
            to: 'yohan@kostastudio.com',
            subject: 'Test Subject',
            htmlContent: '<p>Hola</p>'
        );

        $this->assertTrue($result['success']);
        $this->assertEquals('re_msg_123456', $result['id']);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.resend.com/emails'
                && $request->hasHeader('Authorization', 'Bearer re_valid_key')
                && $request['subject'] === 'Test Subject';
        });
    }

    public function test_meta_whatsapp_service_dispatches_http_call(): void
    {
        Http::fake([
            'https://graph.facebook.com/v21.0/105934529384729/messages' => Http::response([
                'messaging_product' => 'whatsapp',
                'contacts' => [['input' => '573113894136', 'wa_id' => '573113894136']],
                'messages' => [['id' => 'wamid.HBgTEST123456']],
            ], 200),
        ]);

        Setting::set('whatsapp_meta_phone_number_id', '105934529384729');
        Setting::set('whatsapp_meta_access_token', 'EAAGm0_TEST_TOKEN');

        $result = MetaWhatsAppService::sendMessage('573113894136', 'Alerta de prueba');

        $this->assertTrue($result['success']);
        $this->assertEquals('wamid.HBgTEST123456', $result['message_id']);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), '105934529384729/messages')
                && $request->hasHeader('Authorization', 'Bearer EAAGm0_TEST_TOKEN')
                && $request['to'] === '573113894136'
                && $request['text']['body'] === 'Alerta de prueba';
        });
    }

    public function test_lead_creation_triggers_resend_and_meta_cloud_api(): void
    {
        Http::fake([
            'https://api.resend.com/emails' => Http::response(['id' => 're_msg_success'], 200),
            'https://graph.facebook.com/v21.0/105934529384729/messages' => Http::response([
                'messages' => [['id' => 'wamid.HBgLEAD_ALERT']],
            ], 200),
        ]);

        Setting::set('automation_email_enabled', '1');
        Setting::set('automation_email_recipient', 'direccion@kostastudio.com');
        Setting::set('resend_api_key', 're_valid_api_key');
        Setting::set('automation_auto_responder_enabled', '1');

        Setting::set('automation_whatsapp_enabled', '1');
        Setting::set('automation_whatsapp_number', '573113894136');
        Setting::set('whatsapp_meta_phone_number_id', '105934529384729');
        Setting::set('whatsapp_meta_access_token', 'EAAGm0_TEST_TOKEN');

        $lead = Lead::create([
            'nombre' => 'Mateo Gómez',
            'email' => 'mateo@startup.co',
            'telefono' => '3001234567',
            'servicio' => 'Desarrollo Web & E-commerce',
            'mensaje' => 'Quiero cotizar una plataforma web completa.',
        ]);

        LeadNotificationService::notify($lead);

        $lead->refresh();
        $this->assertEquals('sent', $lead->metadata['notifications']['email']['status']);
        $this->assertEquals('re_msg_success', $lead->metadata['notifications']['email']['resend_id']);
        $this->assertEquals('sent_meta_cloud', $lead->metadata['notifications']['whatsapp']['status']);
        $this->assertEquals('wamid.HBgLEAD_ALERT', $lead->metadata['notifications']['whatsapp']['meta_cloud']['message_id']);
    }
}
