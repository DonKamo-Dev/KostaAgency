<script>
/**
 * crudApp — componente Alpine genérico para módulos CRUD simples (sin ítems anidados).
 * Recibe configuración: listUrl, storeUrl, updateUrl, deleteUrl, blankForm, rowMap.
 */
window.crudApp = function crudApp(config) {
    const TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const initialRows = (config.initialRows && Array.isArray(config.initialRows)) ? config.initialRows.map(config.rowMap) : [];

    return {
        rows:        initialRows,
        meta:        config.initialMeta || { current_page: 1, last_page: 1, total: 0 },
        hydrated:    false,
        search:      '',
        searchTimer: null,
        loadingList: (config.initialRows && Array.isArray(config.initialRows)) ? false : true,
        open:        false,
        editingId:   null,
        saving:      false,
        form:        config.blankForm(),
        errors:      {},
        toast:       { show: false, message: '', timer: null },

        init() {
            this.hydrated = true;

            if (!config.initialRows) {
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

        async load(page = 1) {
            this.loadingList = true;
            try {
                const data = await this.request(`${config.listUrl}?search=${encodeURIComponent(this.search)}&page=${page}`,
                                                { headers: { Accept: 'application/json' } });
                this.rows = data.data.map(config.rowMap);
                this.meta = data.meta;
            } catch (error) {
                this.notify(error.message);
            } finally {
                this.loadingList = false;
            }
        },

        onSearch() {
            clearTimeout(this.searchTimer);
            this.searchTimer = setTimeout(() => this.load(1), 400);
        },

        goPage(p) { if (p >= 1 && p <= this.meta.last_page) this.load(p); },

        openNew() {
            this.editingId = null; this.form = config.blankForm(); this.errors = {}; this.open = true;
        },

        openEdit(row) {
            this.editingId = row.id;
            this.form = Object.assign(config.blankForm(), row);
            this.errors = {}; this.open = true;
        },

        close() { this.open = false; this.editingId = null; this.errors = {}; },

        async save() {
            this.errors = {};
            this.saving = true;
            const method = this.editingId ? 'PUT' : 'POST';
            const url    = this.editingId ? config.updateUrl(this.editingId) : config.storeUrl;
            try {
                const d = await this.request(url, {
                    method,
                    headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': TOKEN },
                    body: JSON.stringify(this.form),
                });
                this.notify(d.message); this.close(); this.load(this.meta.current_page);
            } catch (error) {
                this.errors = error.data?.errors || {};
                this.notify(error.message);
            } finally {
                this.saving = false;
            }
        },

        async del(id, msg = '¿Eliminar este registro?') {
            if (!confirm(msg + ' Esta acción no se puede deshacer.')) return;
            try {
                const d = await this.request(config.deleteUrl(id), {
                    method: 'DELETE',
                    headers: { Accept: 'application/json', 'X-CSRF-TOKEN': TOKEN },
                });
                this.notify(d.message);
                this.load(this.meta.current_page);
            } catch (error) {
                this.notify(error.message);
            }
        },

        fmt(v) { return '$' + new Intl.NumberFormat('es-CO', { maximumFractionDigits: 0 }).format(v || 0); },

        notify(msg, ms = 3000) {
            clearTimeout(this.toast.timer);
            this.toast.message = msg; this.toast.show = true;
            this.toast.timer = setTimeout(() => this.toast.show = false, ms);
        },
    };
}
</script>
