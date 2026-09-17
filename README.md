# Kamo Platform

Plataforma SaaS de gestión para agencias digitales: clientes, servicios, cotizaciones, facturación y gastos.

**Stack:** Laravel 13 · Livewire 3 · Tailwind CSS · MySQL 8 · Docker

## 🚀 Despliegue

```bash
docker compose up -d
```

Levanta `app` (PHP-FPM), `db` (MySQL 8), `nginx` (host:8000) y `queue` (worker de
cola para la generación de Meta Ads). La primera vez tardará unos minutos en
instalar dependencias.

## 🌐 Acceso

- **App:** http://localhost:8000
- **DB:** localhost:3306 (user: `user` / password: `user_password`)

## 📦 Comandos útiles

```bash
# Migraciones
docker compose exec app php artisan migrate

# Seed DB
docker compose exec app php artisan db:seed

# Limpiar cachés
docker compose exec app php artisan optimize:clear

# Worker de cola (Meta Ads en segundo plano)
docker compose up -d queue
# o, en desarrollo local: php artisan queue:work

# Acceder al contenedor
docker compose exec app bash
```

## ✅ Verificación

```bash
# Tests PHP (host, SQLite en memoria)
php artisan test

# Tests JS y build
npm run test:js && npm run build

# Estado de contenedores y extensión intl
docker compose ps
docker exec kamo_app php -m | grep intl
```

> Los tests deben ejecutarse en el host (o forzando el entorno dentro del contenedor):
> `docker compose exec app php artisan test` sin overrides falla por `419`/CSRF al
> heredar `APP_ENV=local` y `DB_CONNECTION=mysql` del compose.

## 📂 Estructura

```
apps/laravel/          → Aplicación Laravel
  app/Livewire/        → Componentes Livewire (CRUD)
  app/Jobs/            → Jobs en cola (Meta Ads)
  resources/views/     → Vistas Blade
  database/migrations/ → Migraciones DB
docker-compose.yml     → Orquestación Docker (app, db, nginx, queue)
```

## ✨ Módulos

- **Dashboard** – KPIs financieros y actividad reciente
- **Clientes** – CRUD de clientes
- **Servicios** – Catálogo de servicios y precios
- **Cotizaciones** – Crear, editar, convertir a factura
- **Facturas** – Pagos, anulaciones, balance
- **Gastos** – Registro mensual con categorías
- **Campañas IA** – Propuestas para Meta Ads en segundo plano e historial
- **Casos de estudio** – Administración del portafolio público

## 🔖 Control de versiones

Repositorio Git en la raíz, rama `master`. No se versionan `.env`, `.env.testing`,
`.env.docker`, dumps SQL ni `backups/`. Estado verificado en la
[auditoría de remediación](docs/audits/2026-08-23-platform-remediation.md) y en
[BITACORA.md](BITACORA.md).

## 🎨 Diseño

Tema oscuro premium con paleta rojo coral (`#E63946`) y negro profundo. Glassmorphism sutil, tipografía Space Grotesk + Inter. Sidebar a la izquierda en desktop, bottom navigation en móvil.
