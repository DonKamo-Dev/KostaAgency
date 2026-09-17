<x-layouts.app>
<div x-data="quotesListApp()" x-init="init()" aria-live="polite">

    <!-- Header -->
    <div class="crud-header">
        <div>
            <h1 class="crud-title">Cotizaciones</h1>
            <p class="crud-subtitle">Crea y convierte cotizaciones a facturas</p>
        </div>
        <a href="{{ route('quotes.create') }}" class="btn btn-primary" style="text-decoration:none;display:inline-flex;align-items:center;gap:8px;">
            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva Cotización
        </a>
    </div>

    <!-- Search -->
    <div class="crud-toolbar">
        <div class="crud-search-wrapper">
            <svg class="crud-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" x-model="search" @input="onSearch()"
                   placeholder="Buscar por cliente o número..."
                   class="crud-search-input"
                   aria-label="Buscar cotización"/>
        </div>
    </div>

    <!-- ── TOAST ──────────────────────────────────────────────────────── -->
    <div x-show="toast.show" x-transition
         style="position:fixed;bottom:24px;right:24px;z-index:9999;background:#1C1C1C;border:1px solid var(--border-default);border-radius:12px;padding:14px 20px;color:var(--text-primary);font-size:14px;font-weight:600;box-shadow:0 8px 32px rgba(0,0,0,.5);display:none;">
        <span x-text="toast.message"></span>
    </div>

    <!-- ── TABLA ──────────────────────────────────────────────────────── -->
    <div class="crud-table-wrapper">
        <table class="crud-table">
            <thead>
                <tr>
                    <th>Número</th><th>Cliente</th><th>Fecha</th>
                    <th style="text-align:right;">Total</th>
                    <th>Estado</th>
                    <th style="text-align:right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loading skeleton -->
                <template x-if="loadingList">
                    <tr>
                        <td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted);">
                            <thinking-orb state="working" size="24" label="Cargando cotizaciones..." pill></thinking-orb>
                        </td>
                    </tr>
                </template>

                <!-- Sin resultados -->
                <template x-if="!loadingList && quotes.length === 0">
                    <tr>
                        <td colspan="6" class="empty-state-cell">
                            <div class="empty-state-icon">
                                <svg style="width:28px;height:28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="empty-state-title" x-text="search ? 'Sin resultados' : 'No hay cotizaciones'"></div>
                            <div class="empty-state-desc" x-text="search ? 'Prueba otros términos' : 'Crea tu primera cotización'"></div>
                        </td>
                    </tr>
                </template>

                <!-- Filas -->
                <template x-if="!loadingList">
                    <template x-for="q in quotes" :key="q.id">
                        <tr>
                            <td><a :href="`/quotes/${q.id}`" style="text-decoration:none;color:inherit;"><div class="crud-row-name" x-text="q.doc_number"></div></a></td>
                            <td x-text="q.client_name"></td>
                            <td class="muted" x-text="q.date"></td>
                            <td style="text-align:right;font-weight:700;font-family:'Space Grotesk',sans-serif;" x-text="fmt(q.total)"></td>
                            <td>
                                <span :class="`badge badge-${q.status}`"
                                      x-text="{pending:'Pendiente',paid:'Pagada',converted:'Convertida',cancelled:'Cancelada'}[q.status] || q.status">
                                </span>
                            </td>
                            <td style="text-align:right;white-space:nowrap;">
                                <template x-if="q.status === 'pending'">
                                    <button @click="convert(q.id)" class="action-btn" title="Convertir a Factura" style="color:var(--positive);">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                    </button>
                                </template>
                                <a :href="`/documents/${q.id}/pdf`" target="_blank" class="action-btn" title="Ver PDF"
                                   style="color:#0EA5E9;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </a>
                                <a :href="`/quotes/${q.id}`" class="action-btn" title="Ver" style="color:#10B981;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a x-show="q.status === 'pending'" :href="`/quotes/${q.id}/edit`" class="action-btn" title="Editar" style="display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <template x-if="q.status === 'pending'">
                                    <button @click="cancel(q.id)" class="action-btn" title="Cancelar"
                                            style="color:#6B7280;">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                    </button>
                                </template>
                                <button x-show="q.status === 'pending'" @click="duplicate(q.id)" class="action-btn" title="Duplicar"
                                        style="color:#F59E0B;font-size:13px;font-weight:700;">
                                    ⧉
                                </button>
                                <button x-show="q.status === 'pending'" @click="del(q.id)" class="action-btn danger" title="Eliminar">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div x-show="meta.last_page > 1" style="display:flex;justify-content:center;align-items:center;gap:8px;margin-top:20px;flex-wrap:wrap;">
        <button @click="goPage(meta.current_page - 1)" :disabled="meta.current_page <= 1"
                class="btn btn-secondary" style="padding:8px 14px;font-size:13px;" :style="meta.current_page <= 1 ? 'opacity:.4;cursor:not-allowed;' : ''">
            ← Anterior
        </button>
        <span style="font-size:13px;color:var(--text-muted);">
            Página <strong style="color:var(--text-primary);" x-text="meta.current_page"></strong>
            de <strong style="color:var(--text-primary);" x-text="meta.last_page"></strong>
        </span>
        <button @click="goPage(meta.current_page + 1)" :disabled="meta.current_page >= meta.last_page"
                class="btn btn-secondary" style="padding:8px 14px;font-size:13px;" :style="meta.current_page >= meta.last_page ? 'opacity:.4;cursor:not-allowed;' : ''">
            Siguiente →
        </button>
    </div>
</div>

<script>
function quotesListApp() {
    const TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';

    return {
        quotes      : @js($initialQuotes ?? []),
        meta        : @js($initialMeta ?? ['current_page' => 1, 'last_page' => 1, 'total' => 0]),
        search      : '',
        searchTimer : null,
        loadingList : false,
        toast       : { show: false, message: '', timer: null },

        init() {
            if (new URLSearchParams(window.location.search).get('create') === '1') {
                window.location.replace('{{ route('quotes.create') }}');
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

        async loadQuotes(page = 1) {
            this.loadingList = true;
            try {
                const data = await this.request(`{{ route("quotes.index") }}?search=${encodeURIComponent(this.search)}&page=${page}`, { headers: { Accept: 'application/json' } });
                this.quotes = data.data;
                this.meta   = data.meta;
            } catch (error) {
                this.notify(error.message);
            } finally {
                this.loadingList = false;
            }
        },

        onSearch() {
            clearTimeout(this.searchTimer);
            this.searchTimer = setTimeout(() => this.loadQuotes(1), 400);
        },

        goPage(p) {
            if (p < 1 || p > this.meta.last_page) return;
            this.loadQuotes(p);
        },

        async convert(id) {
            if (!confirm('¿Convertir esta cotización a factura?')) return;
            try {
                const d = await this.request(`/quotes/${id}/convert`, {
                    method: 'POST', headers: { Accept: 'application/json', 'X-CSRF-TOKEN': TOKEN },
                });
                this.notify(d.message);
                this.loadQuotes(this.meta.current_page);
            } catch (error) { this.notify(error.message); }
        },

        async cancel(id) {
            if (!confirm('¿Cancelar esta cotización? Quedará marcada como cancelada.')) return;
            try {
                const d = await this.request(`/quotes/${id}/cancel`, {
                    method: 'POST', headers: { Accept: 'application/json', 'X-CSRF-TOKEN': TOKEN },
                });
                this.notify(d.message);
                this.loadQuotes(this.meta.current_page);
            } catch (error) { this.notify(error.message); }
        },

        async duplicate(id) {
            if (!confirm('¿Duplicar esta cotización?')) return;
            try {
                const d = await this.request(`/quotes/${id}/duplicate`, {
                    method: 'POST', headers: { Accept: 'application/json', 'X-CSRF-TOKEN': TOKEN },
                });
                this.notify(d.message);
                this.loadQuotes(this.meta.current_page);
            } catch (error) { this.notify(error.message); }
        },

        async del(id) {
            if (!confirm('¿Eliminar esta cotización? No se puede deshacer.')) return;
            try {
                const d = await this.request(`/quotes/${id}`, {
                    method: 'DELETE', headers: { Accept: 'application/json', 'X-CSRF-TOKEN': TOKEN },
                });
                this.notify(d.message);
                this.loadQuotes(this.meta.current_page);
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