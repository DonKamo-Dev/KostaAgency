<script>
/**
 * docApp — Alpine genérico para módulos de documentos con ítems (Facturas, Cuentas de Cobro).
 * cfg: { listUrl, formDataUrl, editDataUrl, storeUrl, updateUrl, deleteUrl,
 *         paymentUrl, titleNew, paymentMethods }
 */
window.docApp = function docApp(cfg) {
    const TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const blank = () => ({
        client_id : '',
        date      : new Date().toISOString().slice(0,10),
        due_date  : '',
        notes     : '',
        items     : [blankItem()],
    });
    function blankItem() { return { service_id:null, service_name:'', description:'', quantity:1, unit_price:0, subtotal:0 }; }

    return {
        rows: (cfg.initialRows && Array.isArray(cfg.initialRows)) ? cfg.initialRows : [],
        meta: cfg.initialMeta || { current_page:1, last_page:1, total:0 },
        search:'', searchTimer:null,
        loadingList: (cfg.initialRows && Array.isArray(cfg.initialRows)) ? false : true,
        /* Modal doc */
        open:false, loadingModal:false, editingId:null, readOnly:false,
        saving:false, form:blank(), errors:{},
        clients:[], services:[], formDataLoaded:false,
        /* Modal pago */
        payOpen:false, payingDoc:null, savingPay:false,
        payForm:{ amount:'', date:new Date().toISOString().slice(0,10), method:'transfer', notes:'' },
        /* Toast */
        toast:{ show:false, message:'', timer:null },

        init() {
            if (!cfg.initialRows) {
                this.load();
            }

            if (new URLSearchParams(window.location.search).get('create') === '1') {
                this.openNew();
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

        async loadFormData() {
            if (this.formDataLoaded) return;
            try {
                const d = await this.request(cfg.formDataUrl, { headers:{ Accept:'application/json' } });
                this.clients = d.clients;
                this.services = d.services;
                this.formDataLoaded = true;
            } catch (error) {
                this.notify(error.message);
            }
        },

        async load(page=1) {
            this.loadingList=true;
            try {
                const d = await this.request(`${cfg.listUrl}?search=${encodeURIComponent(this.search)}&page=${page}`, { headers:{ Accept:'application/json' } });
                this.rows = d.data; this.meta = d.meta;
            } catch (error) {
                this.notify(error.message);
            } finally {
                this.loadingList=false;
            }
        },

        onSearch() { clearTimeout(this.searchTimer); this.searchTimer=setTimeout(()=>this.load(1),400); },
        goPage(p)  { if(p>=1&&p<=this.meta.last_page) this.load(p); },

        /* Abrir modals */
        openNew() {
            this.editingId=null; this.readOnly=false; this.form=blank(); this.errors={};
            this.open=true; this.loadFormData();
        },
        async openEdit(id) {
            this.editingId=id; this.readOnly=false; this.errors={};
            this.loadingModal=true; this.open=true;
            try {
                await this.loadFormData();
                const d = await this.request(cfg.editDataUrl(id), { headers:{ Accept:'application/json' } });
                this.form = { client_id:d.client_id, date:d.date, due_date:d.due_date, notes:d.notes, items:d.items };
            } catch (error) {
                this.notify(error.message);
                this.close();
            } finally {
                this.loadingModal=false;
            }
        },
        async openView(id) { await this.openEdit(id); this.readOnly=true; },
        close() { this.open=false; this.editingId=null; this.errors={}; },

        /* Ítems — puro JS, sin servidor */
        addItem()   { this.form.items.push(blankItem()); },
        removeItem(i) { this.form.items.splice(i,1); },
        calcRow(i)  {
            const q = parseFloat(this.form.items[i].quantity)||0;
            const p = parseFloat(this.form.items[i].unit_price)||0;
            this.form.items[i].subtotal = q*p;
        },
        pickService(i,svc) {
            Object.assign(this.form.items[i], { service_id:svc.id, service_name:svc.name, unit_price:svc.unit_price, description:svc.description });
            this.calcRow(i);
        },
        filteredServices(q) {
            const s=(q||'').toLowerCase();
            const base=this.services.filter(x=>x.name&&x.name.trim());
            return s?base.filter(x=>x.name.toLowerCase().includes(s)):base;
        },
        get grandTotal() { return this.form.items.reduce((s,i)=>s+(parseFloat(i.subtotal)||0),0); },

        /* Guardar */
        async save() {
            this.errors={};
            if (!this.form.client_id) { this.errors.client_id='Selecciona un cliente'; return; }
            const valid = this.form.items.filter(i=>i.service_name.trim());
            if (!valid.length) { this.errors.items='Agrega al menos un ítem'; return; }
            this.saving=true;
            const method = this.editingId ? 'PUT' : 'POST';
            const url    = this.editingId ? cfg.updateUrl(this.editingId) : cfg.storeUrl;
            try {
                const d = await this.request(url, {
                    method, headers:{'Content-Type':'application/json',Accept:'application/json','X-CSRF-TOKEN':TOKEN},
                    body: JSON.stringify({ ...this.form, items:valid }),
                });
                this.notify(d.message); this.close(); this.load(this.meta.current_page);
            } catch (error) {
                this.errors=error.data?.errors||{}; this.notify(error.message);
            } finally {
                this.saving=false;
            }
        },

        async del(id) {
            if(!confirm('¿Eliminar este documento? No se puede deshacer.')) return;
            try {
                const d = await this.request(cfg.deleteUrl(id), { method:'DELETE', headers:{Accept:'application/json','X-CSRF-TOKEN':TOKEN} });
                this.notify(d.message); this.load(this.meta.current_page);
            } catch (error) {
                this.notify(error.message);
            }
        },

        async cancel(id) {
            if(!confirm('¿Anular este documento?')) return;
            try {
                const d = await this.request(cfg.cancelUrl(id), { method:'POST', headers:{Accept:'application/json','X-CSRF-TOKEN':TOKEN} });
                this.notify(d.message); this.load(this.meta.current_page);
            } catch (error) {
                this.notify(error.message);
            }
        },

        /* Pago */
        openPayment(row) {
            this.payingDoc = row;
            this.payForm   = { amount: row.balance, date: new Date().toISOString().slice(0,10), method:'transfer', notes:'' };
            this.payOpen   = true;
        },
        closePayment() { this.payOpen=false; this.payingDoc=null; },

        async savePayment() {
            if (!this.payingDoc) return;
            this.savingPay=true;
            try {
                const d = await this.request(cfg.paymentUrl(this.payingDoc.id), {
                    method:'POST',
                    headers:{'Content-Type':'application/json',Accept:'application/json','X-CSRF-TOKEN':TOKEN},
                    body: JSON.stringify(this.payForm),
                });
                this.notify(d.message); this.closePayment(); this.load(this.meta.current_page);
            } catch (error) {
                this.notify(error.message);
            } finally {
                this.savingPay=false;
            }
        },

        fmt(v) { return '$'+new Intl.NumberFormat('es-CO',{maximumFractionDigits:0}).format(v||0); },
        notify(msg,ms=3000) {
            clearTimeout(this.toast.timer); this.toast.message=msg; this.toast.show=true;
            this.toast.timer=setTimeout(()=>this.toast.show=false,ms);
        },
    };
}
</script>
