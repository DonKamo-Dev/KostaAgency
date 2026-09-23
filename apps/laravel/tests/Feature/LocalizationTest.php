<?php

namespace Tests\Feature;

use App\Livewire\Landing\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_locale_is_spanish(): void
    {
        $response = $this->get(route('landing'));

        $response->assertOk();
        $this->assertEquals('es', app()->getLocale());
        $response->assertSee('Empezar proyecto');
        $response->assertSee('Desarrollo Web');
        $response->assertSee('Todos los derechos reservados');
    }

    public function test_switching_locale_updates_session_and_cookie(): void
    {
        $response = $this->get(route('locale.switch', 'en'));

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'en');
        $response->assertCookie('locale', 'en');
    }

    public function test_switching_to_invalid_locale_defaults_to_spanish(): void
    {
        $response = $this->get(route('locale.switch', 'fr'));

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'es');
    }

    public function test_landing_page_renders_in_english_when_session_locale_is_en(): void
    {
        $response = $this->withSession(['locale' => 'en'])->get(route('landing'));

        $response->assertOk();
        $response->assertSee('Start project');
        $response->assertSee('Web Development');
        $response->assertSee('All rights reserved');
    }

    public function test_portfolio_page_renders_in_english(): void
    {
        $response = $this->withSession(['locale' => 'en'])->get(route('portfolio'));

        $response->assertOk();
        $response->assertSee('+50 delivered projects');
        $response->assertSee('Websites');
        $response->assertSee('Delivered projects');
        $response->assertSee('Is your brand next?');
    }

    public function test_contact_page_renders_in_english(): void
    {
        $response = $this->withSession(['locale' => 'en'])->get(route('contact'));

        $response->assertOk();
        $response->assertSee('Let’s talk about your');
        $response->assertSee('Tell us about your project');
        $response->assertSee('Full name');
        $response->assertSee('Send message');
        $response->assertSee('Guaranteed reply in under 24 hours');
    }

    public function test_service_detail_page_renders_in_english(): void
    {
        $response = $this->withSession(['locale' => 'en'])->get(route('services.show', 'desarrollo-web'));

        $response->assertOk();
        $response->assertSee('Web Development');
        $response->assertSee('Home');
        $response->assertSee('Request a Quote for Web Development');
        $response->assertSee('Scope &amp; Deliverables', false);
        $response->assertSee('Tech Stack &amp; Tooling', false);
    }

    public function test_contact_form_validation_messages_are_translated(): void
    {
        app()->setLocale('en');

        Livewire::test(Contact::class)
            ->set('nombre', '')
            ->set('email', '')
            ->set('servicio', '')
            ->call('enviar')
            ->assertHasErrors([
                'nombre' => 'Your full name is required.',
                'email' => 'Your email address is required.',
                'servicio' => 'Please select a service of interest.',
            ]);
    }

    public function test_privacy_page_renders_in_english(): void
    {
        $response = $this->withSession(['locale' => 'en'])->get(route('privacy'));

        $response->assertOk();
        $response->assertSee('Privacy Policy');
        $response->assertSee('Return to contact');
    }

    public function test_bento_page_renders_in_spanish_by_default(): void
    {
        $response = $this->get(route('kamo'));

        $response->assertOk();
        $response->assertSee('Volver a Kosta');
        $response->assertSee('Últimos Proyectos');
        $response->assertSee('Métricas &amp; Hitos', false);
        $response->assertSee('Disponible para contratación');
        $response->assertSee('Reserva tu cita');
    }

    public function test_bento_page_renders_in_english_when_session_locale_is_en(): void
    {
        $response = $this->withSession(['locale' => 'en'])->get(route('kamo'));

        $response->assertOk();
        $response->assertSee('Back to Kosta');
        $response->assertSee('Latest Projects');
        $response->assertSee('Metrics &amp; Milestones', false);
        $response->assertSee('Available for hire');
        $response->assertSee('Book a call');
    }
}
