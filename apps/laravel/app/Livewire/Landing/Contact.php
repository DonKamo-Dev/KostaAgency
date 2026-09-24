<?php

namespace App\Livewire\Landing;

use App\Models\Lead;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Contact extends Component
{
    public string $nombre = '';

    public string $email = '';

    public string $telefono = '';

    public string $servicio = '';

    public string $presupuesto = '';

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
        'servicio' => 'required',
        'presupuesto' => 'nullable',
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
            $this->reset(['nombre', 'email', 'telefono', 'servicio', 'presupuesto', 'mensaje', 'website']);

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

        Lead::create([
            'nombre' => $this->nombre,
            'email' => $this->email,
            'telefono' => $this->telefono ?: null,
            'servicio' => $this->servicio,
            'presupuesto' => $this->presupuesto ?: null,
            'mensaje' => $this->mensaje ?: null,
        ]);

        $this->enviado = true;
        $this->reset(['nombre', 'email', 'telefono', 'servicio', 'presupuesto', 'mensaje', 'website']);
    }

    public function render()
    {
        return view('livewire.landing.contact')
            ->layout('layouts.landing');
    }
}
