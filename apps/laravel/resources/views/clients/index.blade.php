<x-layouts.app>
@include('partials.crud-app-script')
<div x-data="crudApp({
    listUrl:     '{{ route('clients.index') }}',
    storeUrl:    '{{ route('clients.store') }}',
    updateUrl:   (id) => `/clients/${id}`,
    deleteUrl:   (id) => `/clients/${id}`,
    blankForm:   () => ({ name:'', tax_id:'', email:'', phone:'', address:'' }),
    rowMap:      (c) => ({ id:c.id, name:c.name, tax_id:c.tax_id, email:c.email, phone:c.phone, address:c.address }),
    initialRows: @js($initialClients ?? null),
    initialMeta: @js($initialMeta ?? null),
})" x-init="init()" aria-live="polite">

    <div class="crud-header">
        <div>
            <h1 class="crud-title">Clientes</h1>
            <p class="crud-subtitle">Gestiona la información de tus clientes</p>
        </div>
        <button @click="openNew()" class="btn btn-primary">
            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nuevo Cliente
        </button>
    </div>

    <div class="crud-toolbar">
        <div class="crud-search-wrapper">
            <svg class="crud-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" x-model="search" @input="onSearch()" placeholder="Buscar por nombre, email o NIT..." class="crud-search-input" aria-label="Buscar cliente"/>
        </div>
    </div>

    {{-- Modal --}}
    <div role="dialog" aria-modal="true" aria-labelledby="modal-title" x-show="open" class="modal-backdrop" @click.self="close()" style="display:none;"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="modal-card" @click.stop
             x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="modal-header">
                <h2 id="modal-title" class="modal-title" x-text="editingId ? 'Editar Cliente' : 'Nuevo Cliente'"></h2>
                <button @click="close()" class="modal-close" aria-label="Cerrar"><svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nombre / Empresa <span class="required">*</span></label>
                    <input type="text" x-model="form.name" class="form-input" placeholder="Empresa ABC S.A.S"/>
                    <span x-show="errors.name" x-text="errors.name" class="form-error"></span>
                </div>
                <div class="form-group">
                    <label class="form-label">ID / Cédula / NIT</label>
                    <input type="text" x-model="form.tax_id" class="form-input" placeholder="123456789-0"/>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" x-model="form.email" class="form-input" placeholder="contacto@empresa.com"/>
                        <span x-show="errors.email" x-text="errors.email" class="form-error"></span>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Teléfono</label>
                        <input type="text" x-model="form.phone" class="form-input" placeholder="+57 300 123 4567"/>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Dirección</label>
                    <textarea x-model="form.address" rows="2" class="form-textarea" placeholder="Calle 123 #45-67, Bogotá"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button @click="close()" class="btn-modal-secondary">Cancelar</button>
                <button @click="save()" :disabled="saving" class="btn-modal-primary">
                    <span x-show="!saving" x-text="editingId ? 'Actualizar' : 'Guardar Cliente'"></span>
                    <span x-show="saving" style="display:inline-flex;align-items:center;gap:8px;">
                        <thinking-orb state="working" size="16"></thinking-orb>
                        <span>Guardando...</span>
                    </span>
                </button>
            </div>
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
                <th>Nombre / NIT</th><th>Email</th><th>Teléfono</th><th>Dirección</th>
                <th style="text-align:right;">Acciones</th>
            </tr></thead>
            <tbody>
                @foreach ($initialClients ?? [] as $client)
                    <tr x-show="!hydrated">
                        <td>
                            <div class="crud-row-name">{{ $client['name'] }}</div>
                            @if ($client['tax_id'])
                                <div class="crud-row-meta">{{ $client['tax_id'] }}</div>
                            @endif
                        </td>
                        <td class="muted">{{ $client['email'] ?: '—' }}</td>
                        <td class="muted">{{ $client['phone'] ?: '—' }}</td>
                        <td class="muted">{{ $client['address'] ?: '—' }}</td>
                        <td></td>
                    </tr>
                @endforeach
                <template x-if="loadingList">
                    <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--text-muted);"><thinking-orb state="working" size="24" label="Cargando clientes..." pill></thinking-orb></td></tr>
                </template>
                <template x-if="!loadingList && rows.length === 0">
                    <tr><td colspan="5" class="empty-state-cell">
                        <div class="empty-state-title" x-text="search ? 'Sin resultados' : 'No hay clientes'"></div>
                        <div class="empty-state-desc" x-text="search ? 'Prueba otros términos' : 'Agrega tu primer cliente'"></div>
                    </td></tr>
                </template>
                <template x-if="!loadingList">
                    <template x-for="c in rows" :key="c.id">
                        <tr>
                            <td>
                                <div class="crud-row-name" x-text="c.name"></div>
                                <div x-show="c.tax_id" class="crud-row-meta" x-text="c.tax_id"></div>
                            </td>
                            <td class="muted" x-text="c.email || '—'"></td>
                            <td class="muted" x-text="c.phone || '—'"></td>
                            <td class="muted" x-text="c.address ? c.address.substring(0,30) + (c.address.length>30?'…':'') : '—'"></td>
                            <td style="text-align:right;white-space:nowrap;">
                                <button @click="openEdit(c)" class="action-btn" title="Editar">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button @click="del(c.id, '¿Eliminar este cliente?')" class="action-btn danger" title="Eliminar">
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
