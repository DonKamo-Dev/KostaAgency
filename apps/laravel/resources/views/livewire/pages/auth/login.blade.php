<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="auth-wrapper">
    <!-- Logo Section -->
    <div class="flex flex-col items-center mb-8">
        <div class="logo-container mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>
        <h1 class="font-display text-2xl font-bold" style="color: var(--text-primary);">
            Bienvenido de vuelta
        </h1>
        <p class="mt-2 text-sm" style="color: var(--text-muted);">
            Accede a tu panel de agencia
        </p>
    </div>

    <!-- Login Card -->
    <div class="login-card">
        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-6 p-4 rounded-xl" style="background: rgba(255, 255, 255, 0.08); border: 1px solid var(--border-subtle);">
                <p class="text-sm" style="color: var(--text-secondary);">{{ session('status') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="space-y-6">
            @csrf
            <!-- Email Address -->
            <div>
                <label for="email" class="custom-label">
                    Correo electrónico
                </label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="tu@email.com"
                    class="custom-input"
                />
                @error('email')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="custom-label">
                    Contraseña
                </label>
                <div style="position:relative;">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="custom-input"
                        style="padding-right: 48px;"
                    />
                    <button
                        type="button"
                        id="togglePassword"
                        onclick="(function(){var i=document.getElementById('password'),b=document.getElementById('togglePassword');if(i.type==='password'){i.type='text';b.querySelector('.eye-off').style.display='none';b.querySelector('.eye-on').style.display='block';}else{i.type='password';b.querySelector('.eye-on').style.display='none';b.querySelector('.eye-off').style.display='block';}})()"
                        style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:4px;color:rgba(245,245,245,0.4);transition:color .2s;"
                        onmouseenter="this.style.color='rgba(245,245,245,0.85)'"
                        onmouseleave="this.style.color='rgba(245,245,245,0.4)'"
                        aria-label="Mostrar u ocultar contraseña"
                    >
                        <!-- Ojo cerrado (estado inicial) -->
                        <svg class="eye-off" style="width:20px;height:20px;display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                        <!-- Ojo abierto (cuando se muestra) -->
                        <svg class="eye-on" style="width:20px;height:20px;display:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <label for="remember" class="flex items-center cursor-pointer">
                    <input
                        id="remember"
                        type="checkbox"
                        name="remember"
                        value="1"
                        @checked(old('remember'))
                        class="custom-checkbox"
                    />
                    <span class="ml-2.5 text-sm" style="color: var(--text-secondary);">
                        Recordarme
                    </span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" wire:navigate class="custom-link hover:text-[var(--red-primary)]">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="btn-primary-red w-full flex items-center justify-center gap-2"
            >
                <span style="display:inline-flex;align-items:center;gap:8px;">
                    <span>Iniciar sesión</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </span>
            </button>
        </form>

    </div>

    <!-- Footer -->
    <div class="mt-8 text-center">
        <p class="text-xs" style="color: var(--text-muted);">
            {{ config('app.name', 'Kosta') }} © {{ date('Y') }}
        </p>
    </div>
</div>
