<?php

namespace Tests\Feature;

use App\Livewire\Landing\Contact;
use App\Mail\NewLeadReceivedMail;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\TestCase;

class ContactFormSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        RateLimiter::clear('contact-form:'.sha1('127.0.0.1|lead@example.com'));

        parent::tearDown();
    }

    public function test_honeypot_submission_is_discarded_silently(): void
    {
        Livewire::test(Contact::class)
            ->set('website', 'https://spam.example')
            ->call('enviar')
            ->assertSet('enviado', true);

        $this->assertDatabaseCount(Lead::class, 0);
    }

    public function test_contact_form_limits_repeated_valid_submissions(): void
    {
        for ($attempt = 1; $attempt <= 3; $attempt++) {
            $this->validContact()->call('enviar')->assertHasNoErrors();
        }

        $this->validContact()
            ->call('enviar')
            ->assertHasErrors('form');

        $this->assertDatabaseCount(Lead::class, 3);
    }

    public function test_contact_form_saves_full_lead_details_and_dispatches_notification(): void
    {
        Mail::fake();

        Livewire::test(Contact::class)
            ->set('nombre', 'Carlos Rodriguez')
            ->set('email', 'carlos@empresa.com')
            ->set('telefono', '+573113894136')
            ->set('tipo_cliente', 'Empresa')
            ->set('servicio', 'Desarrollo Web')
            ->set('presupuesto', '$1,500 - $3,000 USD')
            ->set('como_nos_conocio', 'Instagram')
            ->set('mensaje', 'Necesito un sitio web para mi empresa')
            ->call('enviar')
            ->assertHasNoErrors()
            ->assertSet('enviado', true);

        $this->assertDatabaseHas(Lead::class, [
            'nombre' => 'Carlos Rodriguez',
            'email' => 'carlos@empresa.com',
            'telefono' => '+573113894136',
            'tipo_cliente' => 'Empresa',
            'servicio' => 'Desarrollo Web',
            'presupuesto' => '$1,500 - $3,000 USD',
            'como_nos_conocio' => 'Instagram',
            'mensaje' => 'Necesito un sitio web para mi empresa',
            'estado' => 'nuevo',
        ]);

        Mail::assertSent(NewLeadReceivedMail::class, function ($mail) {
            return $mail->hasTo('kostastudioco@gmail.com') && $mail->lead->nombre === 'Carlos Rodriguez';
        });
    }

    public function test_authenticated_user_can_view_leads_index(): void
    {
        $user = User::factory()->create();
        $lead = Lead::create([
            'nombre' => 'Diana Morales',
            'email' => 'diana@negocio.com',
            'telefono' => '3001234567',
            'tipo_cliente' => 'Negocio',
            'servicio' => 'E-commerce',
            'como_nos_conocio' => 'Pagina Web',
            'estado' => 'nuevo',
        ]);

        $response = $this->actingAs($user)->get(route('leads.index'));

        $response->assertOk();
        $response->assertSee('Diana Morales');
        $response->assertSee('diana@negocio.com');
        $response->assertSee('Negocio');
    }

    private function validContact(): Testable
    {
        return Livewire::test(Contact::class)
            ->set('nombre', 'Lead Test')
            ->set('email', 'lead@example.com')
            ->set('servicio', 'web');
    }
}
