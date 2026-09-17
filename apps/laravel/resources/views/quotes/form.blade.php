<x-layouts.app>
@include('partials.doc-form-page-script')
@php($defaultNotes = "1. Se requiere el pago del 50% para iniciar con el servicio y el otro 50% al finalizar.\n\n2. El pago puede realizarlo a través de Bancolombia o Nequi. Si lo hará con tarjeta, se le proporciona link de pago con Wompi.\n\nBancolombia CTA: 67817145938\nNequi CTA: 3158396238\n\n3. Tiempo de entrega: 3 Semanas.")
<div x-data="documentFormPage({
    mode:        @js($mode),
    document:    @js($document),
    clients:     @js($clients),
    services:    @js($services),
    payments:    @js($payments),
    storeUrl:    '{{ route('quotes.store') }}',
    updateUrl:   @js($document ? route('quotes.update', $document['id']) : ''),
    showBaseUrl: '/quotes/',
    paymentUrl:  null,
    defaultNotes: @js($defaultNotes),
    clientStoreUrl:  '{{ route('clients.store') }}',
    clientReloadUrl: '{{ route('quotes.form-data') }}',
})" x-init="init()" aria-live="polite">

    <div class="crud-header">
        <div>
            <h1 class="crud-title" x-text="mode === 'create' ? 'Nueva Cotización' : (readOnly ? 'Ver Cotización' : 'Editar Cotización')"></h1>
            <p class="crud-subtitle" x-text="docNumber ? docNumber + ' · Cotización' : 'Describe el alcance y los servicios a cotizar'"></p>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <template x-if="readOnly && docStatus === 'pending'">
                <a :href="'/quotes/' + docId + '/edit'" class="btn btn-secondary">
                    <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Editar
                </a>
            </template>
            <template x-if="readOnly">
                <a :href="'/documents/' + docId + '/pdf'" target="_blank" class="btn btn-primary" style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                    <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Ver PDF
                </a>
            </template>
            <a href="{{ route('quotes.index') }}" class="btn btn-secondary">← Volver</a>
        </div>
    </div>

    <div style="max-width:980px;">
        <div style="background:#161616;border:1px solid var(--border-default);border-radius:16px;padding:24px;">
            @include('partials.doc-form-fields')

            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:24px;padding-top:20px;border-top:1px solid var(--border-subtle);">
                <a href="{{ route('quotes.index') }}" class="btn-modal-secondary" x-text="readOnly ? 'Volver' : 'Cancelar'">Volver</a>
                <template x-if="!readOnly">
                    <button type="button" @click="save()" :disabled="saving" class="btn-modal-primary"
                            :style="saving ? 'opacity:.7;cursor:not-allowed;' : ''">
                        <span x-show="!saving" x-text="mode === 'edit' ? 'Actualizar Cotización' : 'Guardar Cotización'"></span>
                        <span x-show="saving" style="display:inline-flex;align-items:center;gap:8px;">
                            <thinking-orb state="working" size="16"></thinking-orb>
                            <span>Guardando...</span>
                        </span>
                    </button>
                </template>
            </div>
        </div>

        {{-- Notas / Términos: card aparte --}}
        <div style="background:#161616;border:1px solid var(--border-default);border-radius:16px;padding:24px;margin-top:16px;">
            <h3 style="margin:0 0 12px 0;font-size:15px;font-weight:700;color:var(--text-primary);">Notas / Términos</h3>
            <p style="margin:0 0 12px 0;font-size:12px;color:var(--text-muted);">Condiciones de pago, plazo de entrega, garantías u otros términos del acuerdo.</p>
            <div class="form-group" style="margin-bottom:0;">
                <textarea x-model="form.notes" :disabled="readOnly" rows="5" class="form-textarea"
                          placeholder="Términos de pago, condiciones..."></textarea>
            </div>
        </div>
    </div>

    {{-- Creación rápida de cliente --}}
    @include('partials.quick-client-modal')

    {{-- Toast --}}
    <div x-show="toast.show" x-transition
         style="position:fixed;bottom:24px;right:24px;z-index:9999;background:#1C1C1C;border:1px solid var(--border-default);border-radius:12px;padding:14px 20px;color:var(--text-primary);font-size:14px;font-weight:600;box-shadow:0 8px 32px rgba(0,0,0,.5);display:none;">
        <span x-text="toast.message"></span>
    </div>
</div>
</x-layouts.app>