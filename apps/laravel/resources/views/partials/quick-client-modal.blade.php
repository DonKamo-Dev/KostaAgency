{{--
  Partial: modal de creación rápida de cliente desde un formulario de documento.
  Requiere componente Alpine con: clientModal, openClientModal(), closeClientModal(), saveClient().
--}}
<div role="dialog" aria-modal="true" aria-labelledby="quick-client-modal-title" x-show="clientModal.open"
     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"  x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     class="modal-backdrop" @click.self="closeClientModal()" style="display:none;">

    <div class="modal-card" style="max-width:520px;"
         x-show="clientModal.open"
         x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         @click.stop>

        <div class="modal-header">
            <h2 id="quick-client-modal-title" class="modal-title">Crear Cliente</h2>
            <button @click="closeClientModal()" class="modal-close" aria-label="Cerrar">
                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Nombre / Empresa <span class="required">*</span></label>
                <input type="text" x-model="clientModal.form.name" class="form-input" placeholder="Empresa ABC S.A.S" @keydown.enter.prevent="saveClient()"/>
                <span x-show="clientModal.errors.name" x-text="clientModal.errors.name" class="form-error"></span>
            </div>
            <div class="form-group">
                <label class="form-label">ID / Cédula / NIT</label>
                <input type="text" x-model="clientModal.form.tax_id" class="form-input" placeholder="123456789-0"/>
            </div>
            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" x-model="clientModal.form.email" class="form-input" placeholder="contacto@empresa.com"/>
                    <span x-show="clientModal.errors.email" x-text="clientModal.errors.email" class="form-error"></span>
                </div>
                <div class="form-group">
                    <label class="form-label">Teléfono</label>
                    <input type="text" x-model="clientModal.form.phone" class="form-input" placeholder="+57 300 123 4567"/>
                </div>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Dirección</label>
                <textarea x-model="clientModal.form.address" rows="2" class="form-textarea" placeholder="Calle 123 #45-67, Bogotá"></textarea>
            </div>
        </div>

        <div class="modal-footer">
            <button @click="closeClientModal()" class="btn-modal-secondary">Cancelar</button>
            <button @click="saveClient()" :disabled="clientModal.saving" class="btn-modal-primary"
                    :style="clientModal.saving ? 'opacity:.7;cursor:not-allowed;' : ''">
                <span x-show="!clientModal.saving">Crear Cliente</span>
                <span x-show="clientModal.saving" style="display:inline-flex;align-items:center;gap:8px;">
                    <thinking-orb state="working" size="16"></thinking-orb>
                    <span>Guardando...</span>
                </span>
            </button>
        </div>
    </div>
</div>