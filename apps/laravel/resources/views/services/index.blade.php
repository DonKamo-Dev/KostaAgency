<x-layouts.app>
@include('partials.crud-app-script')
<div x-data="crudApp({
    listUrl:     '{{ route('services.index') }}',
    storeUrl:    '{{ route('services.store') }}',
    updateUrl:   (id) => `/services/${id}`,
    deleteUrl:   (id) => `/services/${id}`,
    blankForm:   () => ({ name:'', description:'', unit_price:'' }),
    rowMap:      (s) => ({ id:s.id, name:s.name, description:s.description, unit_price:s.unit_price }),
    initialRows: @js($initialServices ?? null),
    initialMeta: @js($initialMeta ?? null),
})" x-init="init()" aria-live="polite">

    <div class="crud-header">
        <div>
            <h1 class="crud-title">Servicios</h1>
            <p class="crud-subtitle">Catálogo de servicios de tu agencia</p>
        </div>
        <button @click="openNew()" class="btn btn-primary">
            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nuevo Servicio
        </button>
    </div>

    <div class="crud-toolbar">
        <div class="crud-search-wrapper">
            <svg class="crud-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" x-model="search" @input="onSearch()" placeholder="Buscar servicio..." class="crud-search-input" aria-label="Buscar servicio"/>
        </div>
    </div>

    {{-- Modal --}}
    <div role="dialog" aria-modal="true" aria-labelledby="modal-title" x-show="open" class="modal-backdrop" @click.self="close()" style="display:none;"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="modal-card" @click.stop
             x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="modal-header">
                <h2 id="modal-title" class="modal-title" x-text="editingId ? 'Editar Servicio' : 'Nuevo Servicio'"></h2>
                <button @click="close()" class="modal-close" aria-label="Cerrar"><svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nombre del servicio <span class="required">*</span></label>
                    <input type="text" x-model="form.name" class="form-input" placeholder="Ej: Diseño web, SEO, etc."/>
                    <span x-show="errors.name" x-text="errors.name" class="form-error"></span>
                </div>
                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea x-model="form.description" rows="2" class="form-textarea" placeholder="Descripción breve del servicio"></textarea>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Precio unitario (COP) <span class="required">*</span></label>
                    <input type="number" step="0.01" min="0" x-model="form.unit_price" class="form-input" placeholder="0"/>
                    <span x-show="errors.unit_price" x-text="errors.unit_price" class="form-error"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button @click="close()" class="btn-modal-secondary">Cancelar</button>
                <button @click="save()" :disabled="saving" class="btn-modal-primary">
                    <span x-show="!saving" x-text="editingId ? 'Actualizar' : 'Guardar Servicio'"></span>
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
                <th>Nombre</th><th>Descripción</th><th style="text-align:right;">Precio</th>
                <th style="text-align:right;">Acciones</th>
            </tr></thead>
            <tbody>
                <template x-if="loadingList">
                    <tr><td colspan="4" style="text-align:center;padding:40px;color:var(--text-muted);"><thinking-orb state="working" size="24" label="Cargando servicios..." pill></thinking-orb></td></tr>
                </template>
                <template x-if="!loadingList && rows.length === 0">
                    <tr><td colspan="4" class="empty-state-cell">
                        <div class="empty-state-title" x-text="search ? 'Sin resultados' : 'No hay servicios'"></div>
                        <div class="empty-state-desc" x-text="search ? 'Prueba otros términos' : 'Agrega tu primer servicio'"></div>
                    </td></tr>
                </template>
                <template x-if="!loadingList">
                    <template x-for="s in rows" :key="s.id">
                        <tr>
                            <td><div class="crud-row-name" x-text="s.name"></div></td>
                            <td class="muted" x-text="s.description ? s.description.substring(0,60)+(s.description.length>60?'…':'') : '—'"></td>
                            <td style="text-align:right;font-weight:700;font-family:'Space Grotesk',sans-serif;" x-text="fmt(s.unit_price)"></td>
                            <td style="text-align:right;white-space:nowrap;">
                                <button @click="openEdit(s)" class="action-btn" title="Editar">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button @click="del(s.id, '¿Eliminar este servicio?')" class="action-btn danger" title="Eliminar">
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
