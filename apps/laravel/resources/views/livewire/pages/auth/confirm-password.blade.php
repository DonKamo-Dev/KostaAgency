<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="auth-wrapper">
    <h1 class="font-display text-2xl font-bold mb-6" style="color: var(--text-primary);">
        Confirmar contraseña
    </h1>

    <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        Esta es una zona segura de la aplicación. Por favor confirma tu contraseña antes de continuar.
    </p>

    <form wire:submit="confirmPassword">
        <!-- Password -->
        <div>
            <label for="password" class="custom-label">Contraseña</label>

            <input wire:model="password"
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="current-password" />

            @if ($errors->get('password'))
                <p class="error-message">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <div class="flex justify-end mt-4">
            <button type="submit" class="btn-primary-red">
                Confirmar
            </button>
        </div>
    </form>
</div>