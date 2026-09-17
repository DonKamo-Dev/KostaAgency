<x-layouts.app>
<div x-data="invoicesListApp()" x-init="init()" aria-live="polite">

    <div class="crud-header">
        <div>
            <h1 class="crud-title">Facturas</h1>
            <p class="crud-subtitle">Registro de facturación y pagos</p>
        </div>
        <a href="{{ route('invoices.create') }}" class="btn btn-primary" style="text-decoration:none;display:inline-flex;align-items:center;gap:8px;">
            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nueva Factura
        </a>
    </div>

    <div class="crud-toolbar">
        <div class="crud-search-wrapper">
            <svg class="crud-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" x-model="search" @input="onSearch()" placeholder="Buscar por cliente o número..." class="crud-search-input" aria-label="Buscar factura"/>
        </div>
    </div>

    {{-- Toast --}}
    <div x-show="toast.show" x-transition style="position:fixed;bottom:24px;right:24px;z-index:9999;background:#1C1C1C;border:1px solid var(--border-default);border-radius:12px;padding:14px 20px;color:var(--text-primary);font-size:14px;font-weight:600;box-shadow:0 8px 32px rgba(0,0,0,.5);display:none;">
        <span x-text="toast.message"></span>
    </div>

    {{-- Tabla --}}
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
                    <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted);"><thinking-orb state="working" size="24" label="Cargando facturas..." pill></thinking-orb></td></tr>
                </template>
                <template x-if="!loadingList && rows.length === 0">
                    <tr><td colspan="7" class="empty-state-cell">
                        <div class="empty-state-title" x-text="search ? 'Sin resultados' : 'No hay facturas'"></div>
                        <div class="empty-state-desc" x-text="search ? 'Prueba otros términos' : 'Crea tu primera factura'"></div>
                    </td></tr>
                </template>
                <template x-if="!loadingList">
                    <template x-for="inv in rows" :key="inv.id">
                        <tr>
                            <td><a :href="`/invoices/${inv.id}`" style="text-decoration:none;color:inherit;"><div class="crud-row-name" x-text="inv.doc_number"></div></a></td>
                            <td x-text="inv.client_name"></td>
                            <td class="muted" x-text="inv.date"></td>
                            <td style="text-align:right;font-weight:700;font-family:'Space Grotesk',sans-serif;" x-text="fmt(inv.total)"></td>
                            <td style="text-align:right;font-weight:600;" :style="inv.balance > 0 ? 'color:var(--warning)' : 'color:var(--positive)'" x-text="fmt(inv.balance)"></td>
                            <td>
                                <span :class="`badge badge-${inv.status}`"
                                      x-text="{pending:'Pendiente',paid:'Pagada',cancelled:'Cancelada'}[inv.status]||inv.status"></span>
                            </td>
                            <td style="text-align:right;white-space:nowrap;">
                                <a :href="`/documents/${inv.id}/pdf`" target="_blank" class="action-btn" title="PDF" style="color:#0EA5E9;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </a>
                                <a :href="`/invoices/${inv.id}`" class="action-btn" title="Ver" style="color:#10B981;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a x-show="inv.status === 'pending' && inv.paid === 0" :href="`/invoices/${inv.id}/edit`" class="action-btn" title="Editar" style="display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <button x-show="inv.status === 'pending' && inv.paid === 0" @click="cancel(inv.id)" class="action-btn" title="Anular" style="color:#6B7280;">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                </button>
                                <button x-show="inv.status === 'pending' && inv.paid === 0" @click="del(inv.id)" class="action-btn danger" title="Eliminar">
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

<script>
function invoicesListApp() {
    const TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';

    return {
        rows        : @js($initialInvoices ?? []),
        meta        : @js($initialMeta ?? ['current_page' => 1, 'last_page' => 1, 'total' => 0]),
        search      : '',
        searchTimer : null,
        loadingList : false,
        toast       : { show: false, message: '', timer: null },

        init() {
            if (new URLSearchParams(window.location.search).get('create') === '1') {
                window.location.replace('{{ route('invoices.create') }}');
            }
        },

        async request(url, options = {}) {
            const response = await fetch(url, options);
            const isJson = response.headers.get('content-type')?.includes('application/json');
            const data = isJson ? await response.json() : {};

            if (response.status === 401 || response.status === 419) {
                window.location.assign('/login');
                throw new Error('Tu sesión expiró. Inicia sesión nuevamente.');
            }

            if (!response.ok) {
                const error = new Error(data.message || 'No se pudo completar la solicitud.');
                error.data = data;
                throw error;
            }

            return data;
        },

        async loadInvoices(page = 1) {
            this.loadingList = true;
            try {
                const data = await this.request(`{{ route("invoices.index") }}?search=${encodeURIComponent(this.search)}&page=${page}`, { headers: { Accept: 'application/json' } });
                this.rows = data.data;
                this.meta = data.meta;
            } catch (error) {
                this.notify(error.message);
            } finally {
                this.loadingList = false;
            }
        },

        onSearch() {
            clearTimeout(this.searchTimer);
            this.searchTimer = setTimeout(() => this.loadInvoices(1), 400);
        },

        goPage(p) {
            if (p < 1 || p > this.meta.last_page) return;
            this.loadInvoices(p);
        },

        async cancel(id) {
            if (!confirm('¿Anular esta factura?')) return;
            try {
                const d = await this.request(`/invoices/${id}/cancel`, {
                    method: 'POST', headers: { Accept: 'application/json', 'X-CSRF-TOKEN': TOKEN },
                });
                this.notify(d.message);
                this.loadInvoices(this.meta.current_page);
            } catch (error) { this.notify(error.message); }
        },

        async del(id) {
            if (!confirm('¿Eliminar esta factura? No se puede deshacer.')) return;
            try {
                const d = await this.request(`/invoices/${id}`, {
                    method: 'DELETE', headers: { Accept: 'application/json', 'X-CSRF-TOKEN': TOKEN },
                });
                this.notify(d.message);
                this.loadInvoices(this.meta.current_page);
            } catch (error) { this.notify(error.message); }
        },

        fmt(v) { return '$' + new Intl.NumberFormat('es-CO', { maximumFractionDigits: 0 }).format(v || 0); },

        notify(msg, ms = 3000) {
            clearTimeout(this.toast.timer);
            this.toast.message = msg;
            this.toast.show    = true;
            this.toast.timer   = setTimeout(() => this.toast.show = false, ms);
        },
    };
}
</script>
</x-layouts.app>