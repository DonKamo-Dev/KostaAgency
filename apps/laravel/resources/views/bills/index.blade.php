<x-layouts.app>
@include('partials.doc-app-script')
<div x-data="docApp({
    listUrl:     '{{ route('bills.index') }}',
    formDataUrl: '{{ route('bills.form-data') }}',
    editDataUrl: (id) => `/bills/${id}/edit-data`,
    storeUrl:    '{{ route('bills.store') }}',
    updateUrl:   (id) => `/bills/${id}`,
    deleteUrl:   (id) => `/bills/${id}`,
    paymentUrl:  (id) => `/bills/${id}/payment`,
    initialRows: @js($initialBills ?? null),
    initialMeta: @js($initialMeta ?? null),
})" x-init="init()" aria-live="polite">

    <div class="crud-header">
        <div>
            <h1 class="crud-title">Cuentas de Cobro</h1>
            <p class="crud-subtitle">Documenta y gestiona tus cobros por servicios prestados</p>
        </div>
        <button @click="openNew()" class="btn btn-primary">
            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nueva Cuenta de Cobro
        </button>
    </div>

    <div class="crud-toolbar">
        <div class="crud-search-wrapper">
            <svg class="crud-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" x-model="search" @input="onSearch()" placeholder="Buscar por cliente o número..." class="crud-search-input" aria-label="Buscar cuenta de cobro"/>
        </div>
    </div>

    @include('partials.doc-items-modal', ['titleNew' => 'Nueva Cuenta de Cobro', 'titleBase' => 'Cuenta de Cobro', 'btnSave' => 'Guardar Cuenta de Cobro'])

    {{-- Modal de pago --}}
    <div x-show="payOpen" class="modal-backdrop" @click.self="closePayment()" style="display:none;"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="modal-card" style="max-width:460px;" @click.stop
             x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="modal-header">
                <h2 class="modal-title">Registrar Pago</h2>
                <button @click="closePayment()" class="modal-close"><svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="modal-body">
                <div x-show="payingDoc" style="background:rgba(230,57,70,.08);border:1px solid var(--border-red);border-radius:10px;padding:12px 16px;margin-bottom:18px;">
                    <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">Saldo pendiente</div>
                    <div x-text="fmt(payingDoc?.balance)" style="font-size:24px;font-weight:700;color:var(--red-primary);font-family:'Space Grotesk',sans-serif;"></div>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Monto <span class="required">*</span></label>
                        <input type="number" step="0.01" x-model="payForm.amount" class="form-input"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fecha <span class="required">*</span></label>
                        <input type="date" x-model="payForm.date" class="form-input" style="color-scheme:dark;"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Método</label>
                    <select x-model="payForm.method" class="form-select">
                        <option value="transfer">Transferencia</option>
                        <option value="nequi">Nequi</option>
                        <option value="cash">Efectivo</option>
                        <option value="card">Tarjeta</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Notas</label>
                    <input type="text" x-model="payForm.notes" class="form-input" placeholder="Opcional..."/>
                </div>
            </div>
            <div class="modal-footer">
                <button @click="closePayment()" class="btn-modal-secondary">Cancelar</button>
                <button @click="savePayment()" :disabled="savingPay" class="btn-modal-primary">
                    <span x-show="!savingPay">Confirmar Pago</span>
                    <span x-show="savingPay" style="display:inline-flex;align-items:center;gap:8px;">
                        <thinking-orb state="working" size="16"></thinking-orb>
                        <span>Guardando...</span>
                    </span>
                </button>
            </div>
        </div>
    </div>

    <div x-show="toast.show" x-transition style="position:fixed;bottom:24px;right:24px;z-index:9999;background:#1C1C1C;border:1px solid var(--border-default);border-radius:12px;padding:14px 20px;color:var(--text-primary);font-size:14px;font-weight:600;box-shadow:0 8px 32px rgba(0,0,0,.5);display:none;">
        <span x-text="toast.message"></span>
    </div>

    <div class="crud-table-wrapper">
        <table class="crud-table">
            <thead><tr>
                <th>Número</th><th>Cliente</th><th>Fecha</th>
                <th style="text-align:right;">Total</th>
                <th style="text-align:right;">Saldo</th>
                <th>Estado</th>
                <th style="text-align:right;">Acciones</th>
            </tr></thead>
            <tbody>
                <template x-if="loadingList">
                    <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted);"><thinking-orb state="working" size="24" label="Cargando cuentas de cobro..." pill></thinking-orb></td></tr>
                </template>
                <template x-if="!loadingList && rows.length === 0">
                    <tr><td colspan="7" class="empty-state-cell">
                        <div class="empty-state-title" x-text="search ? 'Sin resultados' : 'No hay cuentas de cobro'"></div>
                        <div class="empty-state-desc" x-text="search ? 'Prueba otros términos' : 'Crea tu primera cuenta de cobro'"></div>
                    </td></tr>
                </template>
                <template x-if="!loadingList">
                    <template x-for="b in rows" :key="b.id">
                        <tr>
                            <td><div class="crud-row-name" x-text="b.doc_number"></div></td>
                            <td x-text="b.client_name"></td>
                            <td class="muted" x-text="b.date"></td>
                            <td style="text-align:right;font-weight:700;font-family:'Space Grotesk',sans-serif;" x-text="fmt(b.total)"></td>
                            <td style="text-align:right;font-weight:600;" :style="b.balance > 0 ? 'color:var(--warning)' : 'color:var(--positive)'" x-text="fmt(b.balance)"></td>
                            <td>
                                <span :class="`badge badge-${b.status}`"
                                      x-text="{pending:'Pendiente',paid:'Pagada',cancelled:'Cancelada'}[b.status]||b.status"></span>
                            </td>
                            <td style="text-align:right;white-space:nowrap;">
                                <template x-if="b.status === 'pending'">
                                    <button @click="openPayment(b)" class="action-btn" title="Registrar pago" style="color:var(--positive);">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </button>
                                </template>
                                <a :href="`/documents/${b.id}/pdf`" target="_blank" class="action-btn" title="PDF" style="color:#0EA5E9;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </a>
                                <button @click="openView(b.id)" class="action-btn" title="Ver" style="color:#10B981;">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                <button x-show="b.status === 'pending' && b.paid === 0" @click="openEdit(b.id)" class="action-btn" title="Editar">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button x-show="b.status === 'pending' && b.paid === 0" @click="del(b.id)" class="action-btn danger" title="Eliminar">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                </template>
            </tbody>
        </table>
    </div>
    <div x-show="meta.last_page > 1" style="display:flex;justify-content:center;gap:8px;margin-top:20px;">
        <button @click="goPage(meta.current_page-1)" :disabled="meta.current_page<=1" class="btn btn-secondary" style="padding:8px 14px;font-size:13px;" :style="meta.current_page<=1?'opacity:.4;cursor:not-allowed;':''">← Anterior</button>
        <span style="font-size:13px;color:var(--text-muted);align-self:center;">Página <strong x-text="meta.current_page"></strong> de <strong x-text="meta.last_page"></strong></span>
        <button @click="goPage(meta.current_page+1)" :disabled="meta.current_page>=meta.last_page" class="btn btn-secondary" style="padding:8px 14px;font-size:13px;" :style="meta.current_page>=meta.last_page?'opacity:.4;cursor:not-allowed;':''">Siguiente →</button>
    </div>
</div>

</x-layouts.app>
