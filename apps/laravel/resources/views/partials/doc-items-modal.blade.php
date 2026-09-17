{{--
  Partial: modal CRUD para documentos con ítems (Facturas, Cuentas de Cobro).
  Requiere que la vista padre tenga un componente Alpine con:
    open, loadingModal, editingId, readOnly, form, errors, saving, clients, services,
    grandTotal, filteredServices(), pickService(), calcRow(), addItem(), removeItem(), save(), close()
--}}
<div role="dialog" aria-modal="true" aria-labelledby="document-modal-title" x-show="open"
     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"  x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     class="modal-backdrop" @click.self="close()" style="display:none;">

    <div class="modal-card modal-xl"
         x-show="open"
         x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         @click.stop>

        <div class="modal-header">
            <h2 id="document-modal-title" class="modal-title">
                <span x-show="!editingId">{{ $titleNew }}</span>
                <span x-show="editingId && !readOnly">Editar {{ $titleBase }}</span>
                <span x-show="editingId && readOnly">Ver {{ $titleBase }}</span>
            </h2>
            <button @click="close()" class="modal-close"><svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>

        <!-- Spinner de carga -->
        <div x-show="loadingModal" class="modal-body" style="text-align:center;padding:60px 20px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;">
            <thinking-orb state="working" size="56" label="Cargando información..." large pill></thinking-orb>
        </div>

        <div x-show="!loadingModal">
            <!-- Cliente + Fechas -->
            <div class="modal-body" style="padding-bottom:0;">
                <div class="form-grid-2" style="margin-bottom:18px;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Cliente <span class="required">*</span></label>
                        <select x-model="form.client_id" class="form-select" :disabled="readOnly">
                            <option value="">Seleccionar cliente...</option>
                            <template x-for="c in clients" :key="c.id">
                                <option :value="c.id" x-text="c.name" :selected="form.client_id == c.id"></option>
                            </template>
                        </select>
                        <span x-show="errors.client_id" x-text="errors.client_id" class="form-error"></span>
                    </div>
                    <div class="form-grid-2" style="margin-bottom:0;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Fecha <span class="required">*</span></label>
                            <input type="date" x-model="form.date" class="form-input" style="color-scheme:dark;" :disabled="readOnly"/>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Vencimiento</label>
                            <input type="date" x-model="form.due_date" class="form-input" style="color-scheme:dark;" :disabled="readOnly"/>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de ítems -->
            <div class="modal-body" style="padding-top:0;padding-bottom:0;">
                <div style="border:1px solid var(--border-subtle);border-radius:12px;overflow:visible;margin-bottom:18px;">
                    <div style="display:grid;grid-template-columns:28px 1fr 90px 130px 110px 32px;gap:0;background:rgba(255,255,255,0.025);border-bottom:1px solid var(--border-subtle);padding:10px 16px;border-radius:12px 12px 0 0;">
                        <span></span>
                        <span style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em;font-weight:700;">Servicio</span>
                        <span style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em;font-weight:700;text-align:center;">Cant.</span>
                        <span style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em;font-weight:700;text-align:right;">P. Unit.</span>
                        <span style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em;font-weight:700;text-align:right;">Subtotal</span>
                        <span></span>
                    </div>
                    <div style="padding:6px 0;">
                        <template x-for="(item, i) in form.items" :key="i">
                            <div style="display:grid;grid-template-columns:28px 1fr 90px 130px 110px 32px;gap:8px;align-items:center;padding:6px 16px;">
                                <span x-text="i+1" style="color:var(--text-subtle);font-size:12px;font-weight:600;text-align:center;"></span>
                                <div x-data="{ ddOpen:false }" @click.away="ddOpen=false" style="position:relative;">
                                    <input type="text" x-model="item.service_name" @focus="ddOpen=true" @input="item.service_id=null" @keydown.escape="ddOpen=false" :disabled="readOnly"
                                           placeholder="Escribir o seleccionar..." class="form-input" style="font-size:13px;padding:9px 12px;" autocomplete="off"/>
                                    <div x-show="ddOpen && filteredServices(item.service_name).length && !readOnly" x-transition:enter="transition ease-out duration-100"
                                         style="position:absolute;z-index:500;width:100%;top:calc(100% + 4px);background:#1C1C1C;border:1px solid var(--border-default);border-radius:10px;box-shadow:0 16px 40px rgba(0,0,0,.6);overflow:hidden;display:none;">
                                        <div style="max-height:200px;overflow-y:auto;">
                                            <template x-for="svc in filteredServices(item.service_name)" :key="svc.id">
                                                <button type="button" @mousedown.prevent="pickService(i,svc);ddOpen=false"
                                                        style="width:100%;text-align:left;padding:10px 14px;background:transparent;border:none;border-bottom:1px solid var(--border-subtle);cursor:pointer;"
                                                        @mouseenter="$el.style.background='rgba(230,57,70,.1)'" @mouseleave="$el.style.background='transparent'">
                                                    <div style="font-size:13px;font-weight:600;color:var(--text-primary);" x-text="svc.name"></div>
                                                    <div style="font-size:11px;color:var(--text-muted);margin-top:2px;" x-text="'$'+new Intl.NumberFormat('es-CO',{maximumFractionDigits:0}).format(svc.unit_price||0)"></div>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <input type="number" step="0.01" min="0.01" x-model="item.quantity" @input="calcRow(i)" :disabled="readOnly" class="form-input" style="font-size:13px;padding:9px 10px;text-align:center;" placeholder="1"/>
                                <input type="number" step="0.01" min="0" x-model="item.unit_price" @input="calcRow(i)" :disabled="readOnly" class="form-input" style="font-size:13px;padding:9px 10px;text-align:right;" placeholder="0"/>
                                <div style="text-align:right;">
                                    <span x-text="'$'+new Intl.NumberFormat('es-CO',{maximumFractionDigits:0}).format(item.subtotal||0)"
                                          style="font-size:14px;font-weight:700;color:var(--text-primary);font-family:'Space Grotesk',sans-serif;"></span>
                                </div>
                                <div>
                                    <button type="button" x-show="form.items.length > 1 && !readOnly" @click="removeItem(i)" class="action-btn danger" style="width:28px;height:28px;">
                                        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;border-top:1px solid var(--border-subtle);background:rgba(0,0,0,.12);border-radius:0 0 12px 12px;">
                        <button type="button" x-show="!readOnly" @click="addItem()"
                                style="display:inline-flex;align-items:center;gap:6px;background:transparent;border:1px dashed var(--border-red);color:var(--red-primary);padding:7px 14px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;"
                                @mouseenter="$el.style.background='var(--red-soft)'" @mouseleave="$el.style.background='transparent'">
                            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Agregar ítem
                        </button>
                        <div style="display:flex;align-items:baseline;gap:12px;">
                            <span style="font-size:12px;color:var(--text-muted);font-weight:700;letter-spacing:.05em;">TOTAL COP</span>
                            <span x-text="'$'+new Intl.NumberFormat('es-CO',{maximumFractionDigits:0}).format(grandTotal)"
                                  style="font-size:28px;font-weight:700;color:var(--red-primary);font-family:'Space Grotesk',sans-serif;line-height:1;"></span>
                        </div>
                    </div>
                </div>
                <span x-show="errors.items" x-text="errors.items" class="form-error" style="display:block;margin-bottom:12px;"></span>
            </div>

            <!-- Notas -->
            <div class="modal-body" style="padding-top:0;">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Notas / Términos</label>
                    <textarea x-model="form.notes" :disabled="readOnly" rows="3" class="form-textarea" placeholder="Términos de pago, condiciones..."></textarea>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button @click="close()" class="btn-modal-secondary" x-text="readOnly ? 'Cerrar' : 'Cancelar'"></button>
                <template x-if="editingId && readOnly">
                    <a :href="`/documents/${editingId}/pdf`" target="_blank" class="btn-modal-primary"
                       style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                        <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        Ver PDF
                    </a>
                </template>
                <template x-if="!readOnly">
                    <button @click="save()" :disabled="saving" class="btn-modal-primary" :style="saving?'opacity:.7;cursor:not-allowed;':''">
                        <span x-show="!saving" x-text="editingId ? 'Actualizar' : '{{ $btnSave }}'"></span>
                        <span x-show="saving" style="display:inline-flex;align-items:center;gap:8px;">
                            <svg style="width:15px;height:15px;animation:spin 1s linear infinite;" fill="none" viewBox="0 0 24 24">
                                <circle style="opacity:.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path style="opacity:.75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Guardando...
                        </span>
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>
<style>@keyframes spin{to{transform:rotate(360deg)}}</style>
