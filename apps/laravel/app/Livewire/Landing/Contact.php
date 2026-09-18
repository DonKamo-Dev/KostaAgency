<?php

namespace App\Livewire\Landing;

use App\Models\Lead;
use Livewire\Component;

class Contact extends Component
{
    public string $nombre = '';

    public string $email = '';

    public string $telefono = '';

    public string $servicio = '';

    public string $presupuesto = '';

    public string $mensaje = '';

    public bool $enviado = false;

    protected array $rules = [
        'nombre' => 'required|min:2|max:100',
        'email' => 'required|email|max:150',
        'telefono' => 'nullable|max:30',
        'servicio' => 'required',
        'presupuesto' => 'nullable',
        'mensaje' => 'nullable|max:2000',
    ];

    protected array $messages = [
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'Ingresa un correo electrónico válido.',
        'servicio.required' => 'Selecciona un servicio de interés.',
    ];

    public function enviar(): void
    {
        $this->validate();

        Lead::create([
            'nombre' => $this->nombre,
            'email' => $this->email,
            'telefono' => $this->telefono ?: null,
            'servicio' => $this->servicio,
            'presupuesto' => $this->presupuesto ?: null,
            'mensaje' => $this->mensaje ?: null,
        ]);

        $this->enviado = true;
        $this->reset(['nombre', 'email', 'telefono', 'servicio', 'presupuesto', 'mensaje']);
    }

    public function render()
    {
        return view('livewire.landing.contact')
            ->layout('layouts.landing');
    }
}
