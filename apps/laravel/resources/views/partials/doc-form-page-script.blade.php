<script>
/**
 * documentFormPage — componente Alpine para formularios de documentos en páginas
 * propias (crear / editar / ver) en lugar de modales.
 * Recibe configuración:
 *   mode ('create'|'edit'|'show'), document (datos o null), clients, services,
 *   payments, storeUrl, updateUrl, showBaseUrl, paymentUrl (opcional), defaultNotes,
 *   clientStoreUrl, clientReloadUrl.
 */
function documentFormPage(cfg) {
    const TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';

    const blankItem = () => ({ service_id: null, service_name: '', description: '', quantity: 1, unit_price: 0, subtotal: 0 });

    const blankForm = () => ({
        client_id: '',
        date: new Date().toISOString().slice(0, 10),
        due_date: '',
        notes: cfg.defaultNotes || '',
        items: [blankItem()],
    });

    const formFromDoc = (doc) => ({
        client_id: doc.client_id ?? '',
        date: doc.date ?? new Date().toISOString().slice(0, 10),
        due_date: doc.due_date ?? '',
        notes: doc.notes ?? '',
        items: (doc.items && doc.items.length) ? doc.items.map(i => ({ ...blankItem(), ...i })) : [blankItem()],
    });

    return {
        mode:       cfg.mode,
        readOnly:   cfg.mode === 'show',
        saving:     false,
        savingPay:  false,
        clients:    cfg.clients || [],
        services:   cfg.services || [],
        payments:   cfg.payments || [],
        docId:      cfg.document?.id ?? null,
        docNumber:  cfg.document?.doc_number ?? null,
        docStatus:  cfg.document?.status ?? null,
        docTotal:   parseFloat(cfg.document?.total ?? 0),
        docPaid:    parseFloat(cfg.document?.paid ?? 0),
        errors:     {},
        payErrors:  {},
        pay:        { amount: '', date: new Date().toISOString().slice(0, 10), method: 'transfer', notes: '' },
        clientModal:{ open: false, saving: false, errors: {}, form: { name: '', tax_id: '', email: '', phone: '', address: '' } },
        form:       cfg.document ? formFromDoc(cfg.document) : blankForm(),
        toast:      { show: false, message: '', timer: null },

        init() {
            this.form.items.forEach(item => this.calcRow(item));
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

        /* Ítems (puro JS, sin servidor) */
        addItem()          { this.form.items.push(blankItem()); },
        removeItem(i)      { this.form.items.splice(i, 1); },
        calcRow(item) {
            item.subtotal = (parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0);
        },
        pickService(i, svc) {
            const item = this.form.items[i];
            item.service_id   = svc.id;
            item.service_name = svc.name;
            item.unit_price   = svc.unit_price;
            item.description  = svc.description || item.description;
            this.calcRow(item);
        },
        filteredServices(q) {
            const s = (q || '').toLowerCase();
            const base = this.services.filter(x => x.name && x.name.trim());
            return s ? base.filter(x => x.name.toLowerCase().includes(s)) : base;
        },
        get grandTotal() {
            return this.form.items.reduce((sum, i) => sum + (parseFloat(i.subtotal) || 0), 0);
        },
        get balance() {
            return (this.docTotal || 0) - (this.docPaid || 0);
        },

        /* Guardar documento */
        async save() {
            this.errors = {};
            if (!this.form.client_id) { this.errors.client_id = 'Selecciona un cliente'; return; }
            if (!this.form.date)      { this.errors.date = 'La fecha es requerida'; return; }
            const validItems = this.form.items.filter(i => (i.service_name || '').trim());
            if (!validItems.length)   { this.errors.items = 'Agrega al menos un ítem'; return; }

            this.saving = true;
            const method = this.mode === 'edit' ? 'PUT' : 'POST';
            const url    = this.mode === 'edit' ? cfg.updateUrl : cfg.storeUrl;

            try {
                const d = await this.request(url, {
                    method,
                    headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': TOKEN },
                    body: JSON.stringify({ ...this.form, items: validItems }),
                });
                this.notify(d.message);
                window.location.href = cfg.showBaseUrl + d.id;
            } catch (error) {
                this.errors = error.data?.errors || {};
                this.notify(error.message);
                this.saving = false;
            }
        },

        /* Registrar pago (solo facturas en modo ver) */
        async savePayment() {
            this.payErrors = {};
            if (!this.pay.amount || parseFloat(this.pay.amount) <= 0) { this.payErrors.amount = 'Ingresa un monto válido'; return; }
            if (!this.pay.date) { this.payErrors.date = 'La fecha es requerida'; return; }

            this.savingPay = true;
            try {
                const d = await this.request(cfg.paymentUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': TOKEN },
                    body: JSON.stringify(this.pay),
                });
                this.notify(d.message);
                window.location.reload();
            } catch (error) {
                this.payErrors = error.data?.errors || {};
                this.notify(error.message);
                this.savingPay = false;
            }
        },

        /* Crear cliente desde el formulario (modal inline) */
        openClientModal() {
            this.clientModal.errors = {};
            this.clientModal.form = { name: '', tax_id: '', email: '', phone: '', address: '' };
            this.clientModal.saving = false;
            this.clientModal.open = true;
        },

        closeClientModal() {
            this.clientModal.open = false;
            this.clientModal.errors = {};
            this.clientModal.saving = false;
        },

        async saveClient() {
            this.clientModal.errors = {};
            if (!(this.clientModal.form.name || '').trim()) {
                this.clientModal.errors.name = 'El nombre es obligatorio';
                return;
            }

            this.clientModal.saving = true;
            try {
                const d = await this.request(cfg.clientStoreUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': TOKEN },
                    body: JSON.stringify(this.clientModal.form),
                });
                const created = d.client || {};

                if (cfg.clientReloadUrl) {
                    const fresh = await this.request(cfg.clientReloadUrl, { headers: { Accept: 'application/json' } });
                    this.clients = (fresh && fresh.clients) || [];
                }

                this.form.client_id = created.id ?? this.form.client_id;
                this.clientModal.open = false;
                this.notify(d.message || 'Cliente creado');
            } catch (error) {
                this.clientModal.errors = error.data?.errors || {};
                this.notify(error.message);
            } finally {
                this.clientModal.saving = false;
            }
        },

        /* Helpers */
        fmt(v) { return '$' + new Intl.NumberFormat('es-CO', { maximumFractionDigits: 0 }).format(v || 0); },
        methodLabel(m) {
            return { transfer: 'Transferencia', nequi: 'Nequi', cash: 'Efectivo', card: 'Tarjeta' }[m] || m;
        },

        notify(msg, ms = 3000) {
            clearTimeout(this.toast.timer);
            this.toast.message = msg;
            this.toast.show    = true;
            this.toast.timer   = setTimeout(() => this.toast.show = false, ms);
        },
    };
}
</script>