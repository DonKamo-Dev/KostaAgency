<?php

namespace App\Livewire\Landing;

use App\Models\Lead;
use App\Services\LeadNotificationService;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Contact extends Component
{
    public string $nombre = '';

    public string $email = '';

    public string $telefono = '';

    public string $tipo_cliente = '';

    public string $servicio = '';

    public string $presupuesto = '';

    public string $como_nos_conocio = '';

    public string $mensaje = '';

    public string $website = '';

    public bool $enviado = false;

    public function mount(): void
    {
        if (request()->has('servicio')) {
            $this->servicio = (string) request()->query('servicio');
        }
    }

    protected array $rules = [
        'nombre' => 'required|min:2|max:100',
        'email' => 'required|email|max:150',
        'telefono' => 'nullable|max:30',
        'tipo_cliente' => 'nullable|string|max:50',
        'servicio' => 'required',
        'presupuesto' => 'nullable',
        'como_nos_conocio' => 'nullable|string|max:50',
        'mensaje' => 'nullable|max:2000',
    ];

    protected function messages(): array
    {
        return [
            'nombre.required' => __('landing.contact.validation.name_required'),
            'nombre.min' => __('landing.contact.validation.name_min'),
            'email.required' => __('landing.contact.validation.email_required'),
            'email.email' => __('landing.contact.validation.email_valid'),
            'servicio.required' => __('landing.contact.validation.service_required'),
        ];
    }

    public function enviar(): void
    {
        if (filled($this->website)) {
            $this->enviado = true;
            $this->reset(['nombre', 'email', 'telefono', 'tipo_cliente', 'servicio', 'presupuesto', 'como_nos_conocio', 'mensaje', 'website']);

            return;
        }

        $this->validate();

        $rateLimitKey = 'contact-form:'.sha1(request()->ip().'|'.strtolower($this->email));
        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            throw ValidationException::withMessages([
                'form' => __('landing.contact.validation.too_many'),
            ]);
        }

        RateLimiter::hit($rateLimitKey, 300);

        $lead = Lead::create([
            'nombre' => $this->nombre,
            'email' => $this->email,
            'telefono' => $this->telefono ?: null,
            'tipo_cliente' => $this->tipo_cliente ?: null,
            'servicio' => $this->servicio,
            'presupuesto' => $this->presupuesto ?: null,
            'como_nos_conocio' => $this->como_nos_conocio ?: null,
            'mensaje' => $this->mensaje ?: null,
            'estado' => 'nuevo',
        ]);

        LeadNotificationService::notify($lead);

        $this->enviado = true;
        $this->reset(['nombre', 'email', 'telefono', 'tipo_cliente', 'servicio', 'presupuesto', 'como_nos_conocio', 'mensaje', 'website']);
    }

    public function render()
    {
        return view('livewire.landing.contact')
            ->layout('layouts.landing', [
                'title' => __('landing.contact.seo_title') ?? 'Hablemos de tu proyecto | Kosta Studio',
                'description' => __('landing.contact.seo_description') ?? 'Contáctanos para desarrollar tu sitio web, producción audiovisual o identidad de marca. Te responderemos en menos de 24 horas.',
            ]);
    }
}
