<?php

namespace Tests\Feature;

use App\Livewire\Landing\Contact;
use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    private function validContact(): Testable
    {
        return Livewire::test(Contact::class)
            ->set('nombre', 'Lead Test')
            ->set('email', 'lead@example.com')
            ->set('servicio', 'web');
    }
}
