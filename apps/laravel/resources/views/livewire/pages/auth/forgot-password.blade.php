<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __('Se ha enviado un enlace para restablecer tu contraseña al correo electrónico.'));

    }
}; ?>

<div>
    <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        ¿Olvidaste tu contraseña? No problem. Solo dnos tu correo electrónico y te enviaremos un enlace para elegir una nueva.
    </p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink">
        <!-- Email Address -->
        <div>
            <label for="email" class="custom-label">Correo electrónico</label>
            <input wire:model="email" id="email" type="email" name="email" required autofocus placeholder="tu@email.com" class="custom-input" />
            @if ($errors->get('email'))
                <p class="error-message">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="btn-primary-red">
                Enviar enlace de restablecimiento
            </button>
        </div>
    </form>
</div>
