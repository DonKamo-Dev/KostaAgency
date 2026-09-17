# Kamo Platform

Plataforma SaaS de gestión para agencias digitales: clientes, servicios, cotizaciones, facturación y gastos.

**Stack:** Laravel 13 · Livewire 3 · Tailwind CSS · MySQL 8 · Docker

## 🚀 Despliegue

```bash
docker-compose up -d
```

La primera vez tardará unos minutos en instalar dependencias.

## 🌐 Acceso

- **App:** http://localhost:8000
- **DB:** localhost:3306 (user: `user` / password: `user_password`)

## 📦 Comandos útiles

```bash
# Migraciones
docker-compose exec laravel php artisan migrate

# Seed DB
docker-compose exec laravel php artisan db:seed

# Limpiar cachés
docker-compose exec laravel php artisan optimize:clear

# Acceder al contenedor
docker-compose exec laravel bash
```

## 📂 Estructura

```
apps/laravel/          → Aplicación Laravel
  app/Livewire/        → Componentes Livewire (CRUD)
  resources/views/     → Vistas Blade
  database/migrations/ → Migraciones DB
docker-compose.yml     → Orquestación Docker
```

## ✨ Módulos

- **Dashboard** – KPIs financieros y actividad reciente
- **Clientes** – CRUD de clientes
- **Servicios** – Catálogo de servicios y precios
- **Cotizaciones** – Crear, editar, convertir a factura
- **Facturas** – Pagos, anulaciones, balance
- **Gastos** – Registro mensual con categorías

## 🎨 Diseño

Tema oscuro premium con paleta rojo coral (`#E63946`) y negro profundo. Glassmorphism sutil, tipografía Space Grotesk + Inter. Sidebar a la izquierda en desktop, bottom navigation en móvil.
