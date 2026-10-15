<x-layouts.app>
    <div style="max-width: 900px; margin: 0 auto;">
        <div style="margin-bottom: 24px;">
            <h1 class="crud-title">Perfil</h1>
            <p class="crud-subtitle">Actualiza tus datos de acceso y seguridad.</p>
        </div>

        <div class="doc-page-card" style="margin-bottom: 20px;">
            <div class="max-w-xl">
                <livewire:profile.update-profile-information-form />
            </div>
        </div>

        <div class="doc-page-card" style="margin-bottom: 20px;">
            <div class="max-w-xl">
                <livewire:profile.update-password-form />
            </div>
        </div>

        <div class="doc-page-card" style="margin-bottom: 20px;">
            <div class="max-w-xl">
                <livewire:profile.delete-user-form />
            </div>
        </div>
    </div>
</x-layouts.app>
