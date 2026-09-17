<x-layouts.app>
@include('partials.doc-form-page-script')
@php($defaultNotes = "")
<div x-data="documentFormPage({
    mode:        @js($mode),
    document:    @js($document),
    clients:     @js($clients),
    services:    @js($services),
    payments:    @js($payments),
    storeUrl:    '{{ route('invoices.store') }}',
    updateUrl:   @js($document ? route('invoices.update', $document['id']) : ''),
    showBaseUrl: '/invoices/',
    paymentUrl:  @js($mode === 'show' ? route('invoices.payment', $document['id'] ?? 0) : ''),
    defaultNotes: @js($defaultNotes),
    clientStoreUrl:  '{{ route('clients.store') }}',
    clientReloadUrl: '{{ route('invoices.form-data') }}',
})" x-init="init()" aria-live="polite">

    <div class="crud-header">
        <div>
            <h1 class="crud-title" x-text="mode === 'create' ? 'Nueva Factura' : (readOnly ? 'Ver Factura' : 'Editar Factura')"></h1>
            <p class="crud-subtitle" x-text="docNumber ? docNumber + ' · Factura' : 'Detalla los servicios facturados'"></p>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <template x-if="readOnly && docStatus === 'pending' && docPaid === 0">
                <a :href="'/invoices/' + docId + '/edit'" class="btn btn-secondary">
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
            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">← Volver</a>
        </div>
    </div>

    <div style="max-width:980px;">
        <div style="background:#161616;border:1px solid var(--border-default);border-radius:16px;padding:24px;">
            @include('partials.doc-form-fields')

            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:24px;padding-top:20px;border-top:1px solid var(--border-subtle);">
                <a href="{{ route('invoices.index') }}" class="btn-modal-secondary" x-text="readOnly ? 'Volver' : 'Cancelar'">Volver</a>
                <template x-if="!readOnly">
                    <button type="button" @click="save()" :disabled="saving" class="btn-modal-primary"
                            :style="saving ? 'opacity:.7;cursor:not-allowed;' : ''">
                        <span x-show="!saving" x-text="mode === 'edit' ? 'Actualizar Factura' : 'Guardar Factura'"></span>
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

        {{-- Detalle financiero + pagos (solo modo ver) --}}
        <template x-if="mode === 'show'">
            <div style="margin-top:16px;">
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;">
                    <div style="background:#161616;border:1px solid var(--border-subtle);border-radius:12px;padding:16px 18px;">
                        <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">Total facturado</div>
                        <div x-text="fmt(docTotal)" style="font-size:22px;font-weight:700;font-family:'Space Grotesk',sans-serif;color:var(--text-primary);"></div>
                    </div>
                    <div style="background:#161616;border:1px solid var(--border-subtle);border-radius:12px;padding:16px 18px;">
                        <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">Pagado</div>
                        <div x-text="fmt(docPaid)" style="font-size:22px;font-weight:700;font-family:'Space Grotesk',sans-serif;color:var(--positive);"></div>
                    </div>
                    <div style="background:#161616;border:1px solid var(--border-subtle);border-radius:12px;padding:16px 18px;">
                        <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">Saldo pendiente</div>
                        <div x-text="fmt(balance)" style="font-size:22px;font-weight:700;font-family:'Space Grotesk',sans-serif;" :style="balance > 0 ? 'color:var(--warning);' : 'color:var(--positive);'"></div>
                    </div>
                </div>

                <div style="background:#161616;border:1px solid var(--border-default);border-radius:16px;padding:20px 24px;margin-top:16px;">
                    <h3 style="margin:0 0 14px 0;font-size:16px;font-weight:700;color:var(--text-primary);">Pagos registrados</h3>

                    <template x-if="payments.length === 0">
                        <p style="margin:0;font-size:13px;color:var(--text-muted);">Aún no hay pagos registrados para esta factura.</p>
                    </template>

                    <template x-if="payments.length > 0">
                        <div style="border:1px solid var(--border-subtle);border-radius:12px;overflow:hidden;">
                            <table style="width:100%;border-collapse:collapse;font-size:14px;">
                                <thead>
                                    <tr style="background:rgba(255,255,255,0.025);text-align:left;">
                                        <th style="padding:10px 14px;font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);">Fecha</th>
                                        <th style="padding:10px 14px;font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);">Método</th>
                                        <th style="padding:10px 14px;font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);">Notas</th>
                                        <th style="padding:10px 14px;font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);text-align:right;">Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="p in payments" :key="p.id">
                                        <tr style="border-top:1px solid var(--border-subtle);">
                                            <td style="padding:10px 14px;" x-text="p.date"></td>
                                            <td style="padding:10px 14px;" x-text="methodLabel(p.method)"></td>
                                            <td style="padding:10px 14px;color:var(--text-muted);" x-text="p.notes || '—'"></td>
                                            <td style="padding:10px 14px;text-align:right;font-weight:700;color:var(--positive);" x-text="fmt(p.amount)"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </template>
                </div>

                {{-- Registrar pago --}}
                <template x-if="docStatus === 'pending' && balance > 0">
                    <div style="background:#161616;border:1px solid var(--border-default);border-radius:16px;padding:20px 24px;margin-top:16px;">
                        <h3 style="margin:0 0 14px 0;font-size:16px;font-weight:700;color:var(--text-primary);">Registrar Pago</h3>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label class="form-label">Monto <span class="required">*</span></label>
                                <input type="number" step="0.01" min="0.01" x-model="pay.amount" class="form-input"/>
                                <span x-show="payErrors.amount" x-text="payErrors.amount" class="form-error"></span>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Fecha <span class="required">*</span></label>
                                <input type="date" x-model="pay.date" class="form-input" style="color-scheme:dark;"/>
                                <span x-show="payErrors.date" x-text="payErrors.date" class="form-error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Método</label>
                            <select x-model="pay.method" class="form-select">
                                <option value="transfer">Transferencia</option>
                                <option value="nequi">Nequi</option>
                                <option value="cash">Efectivo</option>
                                <option value="card">Tarjeta</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Notas</label>
                            <input type="text" x-model="pay.notes" class="form-input" placeholder="Opcional..."/>
                        </div>
                        <div style="display:flex;justify-content:flex-end;margin-top:18px;">
                            <button type="button" @click="savePayment()" :disabled="savingPay" class="btn btn-primary"
                                    :style="savingPay ? 'opacity:.7;cursor:not-allowed;' : ''">
                                <span x-show="!savingPay">Confirmar Pago</span>
                                <span x-show="savingPay" style="display:inline-flex;align-items:center;gap:8px;">
                                    <thinking-orb state="working" size="16"></thinking-orb>
                                    <span>Guardando...</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </template>
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