{{--
  Partial: campos compartidos del formulario de documentos con ítems (Cotizaciones, Facturas).
  Se incluye dentro de un componente Alpine `documentFormPage` y espera:
    form, readOnly, clients, errors, filteredServices(), pickService(), calcRow(),
    addItem(), removeItem(), grandTotal
--}}
<div class="form-grid-2 doc-meta-grid" style="margin-bottom:18px;">
    <div class="form-group" style="margin-bottom:0;">
        <label class="form-label">Cliente <span class="required">*</span></label>
        <div style="display:flex;gap:8px;">
            <select x-model="form.client_id" class="form-select" style="flex:1;" :disabled="readOnly">
                <option value="">Seleccionar cliente...</option>
                <template x-for="c in clients" :key="c.id">
                    <option :value="c.id" x-text="c.name" :selected="form.client_id == c.id"></option>
                </template>
            </select>
            <button type="button" x-show="!readOnly" @click="openClientModal()"
                    class="btn btn-secondary" style="padding:0 12px;flex-shrink:0;" title="Crear cliente">
                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </button>
        </div>
        <span x-show="errors.client_id" x-text="errors.client_id" class="form-error"></span>
    </div>
    <div class="form-grid-2 doc-dates-grid" style="margin-bottom:0;">
        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Fecha <span class="required">*</span></label>
            <input type="date" x-model="form.date" class="form-input" style="color-scheme:dark;" :disabled="readOnly"/>
            <span x-show="errors.date" x-text="errors.date" class="form-error"></span>
        </div>
        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Vencimiento</label>
            <input type="date" x-model="form.due_date" class="form-input" style="color-scheme:dark;" :disabled="readOnly"/>
        </div>
    </div>
</div>

<div class="doc-items-box">
    <div class="doc-items-table-header">
        <span style="text-align:center;">#</span>
        <span>Servicio</span>
        <span style="text-align:center;">Cant.</span>
        <span style="text-align:right;">P. Unit.</span>
        <span style="text-align:right;">Subtotal</span>
        <span></span>
    </div>

    <div class="doc-items-list">
        <template x-for="(item, i) in form.items" :key="i">
            <div class="doc-item-row">
                <!-- Mobile Only Top Row: Item # & Remove Button -->
                <div class="doc-item-row-header-mobile">
                    <span class="doc-item-badge" x-text="'Ítem #' + (i + 1)"></span>
                    <button type="button" x-show="form.items.length > 1 && !readOnly"
                            @click="removeItem(i)" class="action-btn danger" title="Eliminar ítem">
                        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Desktop Index Indicator -->
                <span class="doc-item-index" x-text="i+1"></span>

                <!-- Service Selector & Description -->
                <div x-data="{ ddOpen: false }" @click.away="ddOpen = false" style="position:relative;width:100%;">
                    <input type="text"
                           x-model="item.service_name"
                           @focus="ddOpen = true"
                           @input="item.service_id = null"
                           @keydown.escape="ddOpen = false"
                           @keydown.tab="ddOpen = false"
                           :disabled="readOnly"
                           placeholder="Escribir o seleccionar servicio..."
                           class="form-input" style="font-size:14px;padding:9px 12px;"
                           autocomplete="off"/>
                    <input type="text"
                           x-model="item.description"
                           :disabled="readOnly"
                           placeholder="Descripción (opcional)..."
                           class="form-input"
                           style="font-size:12px;padding:7px 12px;margin-top:4px;color:var(--text-muted);"
                           autocomplete="off"/>
                    <div x-show="ddOpen && filteredServices(item.service_name).length && !readOnly"
                         x-transition:enter="transition ease-out duration-100"
                         style="position:absolute;z-index:500;width:100%;top:calc(100% + 4px);
                                background:#1C1C1C;border:1px solid var(--border-default);
                                border-radius:10px;box-shadow:0 16px 40px rgba(0,0,0,.6);
                                overflow:hidden;display:none;">
                        <div style="max-height:220px;overflow-y:auto;">
                            <template x-for="svc in filteredServices(item.service_name)" :key="svc.id">
                                <button type="button"
                                        @mousedown.prevent="pickService(i, svc); ddOpen = false"
                                        style="width:100%;text-align:left;padding:10px 14px;background:transparent;
                                               border:none;border-bottom:1px solid var(--border-subtle);cursor:pointer;"
                                        @mouseenter="$el.style.background='rgba(230,57,70,.1)'"
                                        @mouseleave="$el.style.background='transparent'">
                                    <div style="font-size:13px;font-weight:600;color:var(--text-primary);" x-text="svc.name"></div>
                                    <div style="font-size:11px;color:var(--text-muted);margin-top:2px;" x-text="fmt(svc.unit_price)"></div>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Inputs: Cantidad & Precio (Responsive) -->
                <div class="doc-item-inputs-mobile">
                    <div>
                        <label class="form-label doc-item-label-mobile">Cantidad</label>
                        <input type="number" step="0.01" min="0.01" x-model="item.quantity"
                               @input="calcRow(item)"
                               :disabled="readOnly"
                               class="form-input numeric-input" style="font-size:13px;padding:9px 10px;text-align:center;margin-top:0;" placeholder="1"
                               :aria-label="'Cantidad del ítem ' + (i + 1)"/>
                    </div>
                    <div>
                        <label class="form-label doc-item-label-mobile">P. Unitario</label>
                        <input type="text" inputmode="numeric" x-model="item.unit_price_display"
                               @input="syncMoney(item, $event)"
                               @focus="$event.target.select()"
                               :disabled="readOnly"
                               class="form-input money-input" style="font-size:13px;padding:9px 12px;text-align:right;" placeholder="0"
                               autocomplete="off" :aria-label="'Precio unitario del ítem ' + (i + 1)"/>
                    </div>
                </div>

                <!-- Subtotal -->
                <div class="doc-item-cell-subtotal">
                    <span x-text="fmt(item.subtotal)"
                          style="font-size:14px;font-weight:700;color:var(--text-primary);font-family:'Geist Mono','Geist',monospace;font-variant-numeric:tabular-nums;"></span>
                </div>

                <!-- Desktop Action Button -->
                <div class="doc-item-cell-action">
                    <button type="button" x-show="form.items.length > 1 && !readOnly"
                            @click="removeItem(i)" class="action-btn danger" style="width:28px;height:28px;" title="Eliminar ítem">
                        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </template>
    </div>

    <!-- Footer -->
    <div class="doc-items-footer">
        <button type="button" x-show="!readOnly" @click="addItem()"
                style="display:inline-flex;align-items:center;gap:6px;background:transparent;border:1px dashed var(--border-red);color:var(--red-primary);padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;transition:all .2s;"
                @mouseenter="$el.style.background='var(--red-soft)'" @mouseleave="$el.style.background='transparent'">
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Agregar ítem
        </button>
        <div class="doc-total-wrap" style="display:flex;align-items:baseline;gap:12px;">
            <span style="font-size:12px;color:var(--text-muted);font-weight:700;letter-spacing:.05em;">TOTAL COP</span>
            <span x-text="fmt(grandTotal)"
                  style="font-size:28px;font-weight:700;color:var(--red-primary);font-family:'Geist Mono','Geist',monospace;line-height:1;font-variant-numeric:tabular-nums;letter-spacing:-0.025em;"></span>
        </div>
    </div>
</div>
<span x-show="errors.items" x-text="errors.items" class="form-error" style="display:block;margin-bottom:12px;"></span>
