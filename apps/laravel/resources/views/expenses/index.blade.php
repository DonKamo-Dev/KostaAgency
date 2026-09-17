<x-layouts.app>
<div x-data="expensesApp()" x-init="init()" aria-live="polite">

    <div class="crud-header">
        <div>
            <h1 class="crud-title">Gastos</h1>
            <p class="crud-subtitle">Registro de gastos operacionales</p>
        </div>
        <button @click="openNew()" class="btn btn-primary">
            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Registrar Gasto
        </button>
    </div>

    <!-- Resumen del mes -->
    <div style="background:linear-gradient(135deg,rgba(230,57,70,.1),rgba(139,26,37,.05));border:1px solid var(--border-red);border-radius:16px;padding:20px 28px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div>
            <div style="font-size:12px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em;font-weight:700;margin-bottom:6px;">Gastos del mes actual</div>
            <div x-text="fmt(monthlyTotal)" style="font-size:36px;font-weight:700;color:var(--red-primary);font-family:'Space Grotesk',sans-serif;line-height:1;"></div>
        </div>
        <div style="font-size:13px;color:var(--text-muted);">{{ now()->format('F Y') }}</div>
    </div>

    <div class="crud-toolbar">
        <div class="crud-search-wrapper">
            <svg class="crud-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" x-model="search" @input="onSearch()" placeholder="Buscar por categoría o descripción..." class="crud-search-input" aria-label="Buscar gasto"/>
        </div>
    </div>

    {{-- Modal --}}
    <div role="dialog" aria-modal="true" aria-labelledby="modal-title" x-show="open" class="modal-backdrop" @click.self="close()" style="display:none;"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="modal-card" @click.stop
             x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="modal-header">
                <h2 id="modal-title" class="modal-title" x-text="editingId ? 'Editar Gasto' : 'Registrar Gasto'"></h2>
                <button @click="close()" class="modal-close" aria-label="Cerrar"><svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="modal-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Fecha <span class="required">*</span></label>
                        <input type="date" x-model="form.date" class="form-input" style="color-scheme:dark;"/>
                        <span x-show="errors.date" x-text="errors.date" class="form-error"></span>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Monto (COP) <span class="required">*</span></label>
                        <input type="number" step="0.01" min="0.01" x-model="form.amount" class="form-input" placeholder="0"/>
                        <span x-show="errors.amount" x-text="errors.amount" class="form-error"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Categoría <span class="required">*</span></label>
                    <input type="text" x-model="form.category" class="form-input" list="categories-list" placeholder="Software, Publicidad, Servicios..."/>
                    <datalist id="categories-list">
                        <template x-for="c in categories" :key="c"><option :value="c"></option></template>
                    </datalist>
                    <span x-show="errors.category" x-text="errors.category" class="form-error"></span>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Descripción</label>
                    <textarea x-model="form.description" rows="2" class="form-textarea" placeholder="Detalle del gasto..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button @click="close()" class="btn-modal-secondary">Cancelar</button>
                <button @click="save()" :disabled="saving" class="btn-modal-primary">
                    <span x-show="!saving" x-text="editingId ? 'Actualizar' : 'Guardar Gasto'"></span>
                    <span x-show="saving" style="display:inline-flex;align-items:center;gap:8px;">
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
                <th>Fecha</th><th>Categoría</th><th>Descripción</th>
                <th style="text-align:right;">Monto</th><th style="text-align:right;">Acciones</th>
            </tr></thead>
            <tbody>
                <template x-if="loadingList">
                    <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--text-muted);"><thinking-orb state="working" size="24" label="Cargando gastos..." pill></thinking-orb></td></tr>
                </template>
                <template x-if="!loadingList && rows.length === 0">
                    <tr><td colspan="5" class="empty-state-cell">
                        <div class="empty-state-title" x-text="search ? 'Sin resultados' : 'No hay gastos registrados'"></div>
                    </td></tr>
                </template>
                <template x-if="!loadingList">
                    <template x-for="e in rows" :key="e.id">
                        <tr>
                            <td class="muted" x-text="e.date"></td>
                            <td><div class="crud-row-name" x-text="e.category"></div></td>
                            <td class="muted" x-text="e.description || '—'"></td>
                            <td style="text-align:right;font-weight:700;font-family:'Space Grotesk',sans-serif;color:var(--red-primary);" x-text="fmt(e.amount)"></td>
                            <td style="text-align:right;white-space:nowrap;">
                                <button @click="openEdit(e)" class="action-btn" title="Editar">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button @click="del(e.id)" class="action-btn danger" title="Eliminar">
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
function expensesApp() {
    const TOKEN   = document.querySelector('meta[name="csrf-token"]').content;
    const listUrl = '{{ route('expenses.index') }}';

    const blank = () => ({ date: new Date().toISOString().slice(0,10), category:'', description:'', amount:'' });

    return {
        rows: @js($initialExpenses ?? []),
        meta: @js($initialMeta ?? ['current_page' => 1, 'last_page' => 1, 'total' => 0]),
        search:'', searchTimer:null,
        loadingList: false,
        open:false, editingId:null, saving:false, form:blank(), errors:{},
        monthlyTotal: {{ $monthlyTotal }},
        categories:   @js($categories),
        toast:{ show:false, message:'', timer:null },

        init() {
            if (new URLSearchParams(window.location.search).get('create') === '1') this.openNew();
        },

        async load(page=1) {
            this.loadingList=true;
            const r = await fetch(`${listUrl}?search=${encodeURIComponent(this.search)}&page=${page}`, { headers:{ Accept:'application/json' } });
            const d = await r.json();
            this.rows = d.data; this.meta = d.meta;
            this.monthlyTotal = d.monthly_total;
            if (d.categories) this.categories = d.categories;
            this.loadingList=false;
        },

        onSearch() { clearTimeout(this.searchTimer); this.searchTimer=setTimeout(()=>this.load(1),400); },
        goPage(p) { if(p>=1&&p<=this.meta.last_page) this.load(p); },

        openNew()    { this.editingId=null; this.form=blank(); this.errors={}; this.open=true; },
        openEdit(e)  { this.editingId=e.id; this.form={ date:e.date_raw, category:e.category, description:e.description, amount:e.amount }; this.errors={}; this.open=true; },
        close()      { this.open=false; this.editingId=null; this.errors={}; },

        async save() {
            this.errors={};
            this.saving=true;
            const method = this.editingId ? 'PUT' : 'POST';
            const url    = this.editingId ? `/expenses/${this.editingId}` : '/expenses';
            const r = await fetch(url, { method, headers:{'Content-Type':'application/json',Accept:'application/json','X-CSRF-TOKEN':TOKEN}, body:JSON.stringify(this.form) });
            this.saving=false;
            if(r.ok){ const d=await r.json(); this.notify(d.message); this.close(); this.load(this.meta.current_page); }
            else{ const d=await r.json(); this.errors=d.errors||{}; this.notify('Error: '+(d.message||'Verifica los datos')); }
        },

        async del(id) {
            if(!confirm('¿Eliminar este gasto? No se puede deshacer.')) return;
            const r = await fetch(`/expenses/${id}`, { method:'DELETE', headers:{Accept:'application/json','X-CSRF-TOKEN':TOKEN} });
            const d = await r.json(); this.notify(d.message);
            if(r.ok) this.load(this.meta.current_page);
        },

        fmt(v) { return '$'+new Intl.NumberFormat('es-CO',{maximumFractionDigits:0}).format(v||0); },
        notify(msg,ms=3000) { clearTimeout(this.toast.timer); this.toast.message=msg; this.toast.show=true; this.toast.timer=setTimeout(()=>this.toast.show=false,ms); },
    };
}
</script>
</x-layouts.app>
