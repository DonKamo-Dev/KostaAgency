<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __('Se ha restablecido la contraseña exitosamente.'));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div class="auth-wrapper">
    <h1 class="font-display text-2xl font-bold mb-6" style="color: var(--text-primary);">
        Restablecer contraseña
    </h1>

    <form wire:submit="resetPassword">
        <!-- Email Address -->
        <div>
            <label for="email" class="custom-label">Correo electrónico</label>
            <input wire:model="email" id="email" type="email" name="email" required autofocus placeholder="tu@email.com" class="custom-input" />
            @if ($errors->get('email'))
                <p class="error-message">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="custom-label">Nueva contraseña</label>
            <input wire:model="password" id="password" type="password" name="password" required autocomplete="new-password" class="custom-input" />
            @if ($errors->get('password'))
                <p class="error-message">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <label for="password_confirmation" class="custom-label">Confirmar contraseña</label>
            <input wire:model="password_confirmation" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="custom-input" />
            @if ($errors->get('password_confirmation'))
                <p class="error-message">{{ $errors->first('password_confirmation') }}</p>
            @endif
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="btn-primary-red">
                Restablecer contraseña
            </button>
        </div>
    </form>
</div>