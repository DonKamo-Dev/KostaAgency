# Bitácora del Proyecto Kamo Platform

## Propósito

Kamo Platform es una plataforma SaaS para la gestión operativa y financiera de una agencia digital. Centraliza clientes, servicios, cotizaciones, cuentas de cobro, facturas, pagos, gastos y casos de estudio.

## Estado actual

- Stack: Laravel 13, Livewire 3, Tailwind CSS, MySQL 8 y Docker.
- Interfaz: tema oscuro con acento coral, navegación lateral en escritorio y navegación inferior en móvil.
- Acceso local: `http://localhost:8000` mediante `docker-compose up -d`.

## Módulos disponibles

| Módulo | Estado | Alcance |
| --- | --- | --- |
| Dashboard | Disponible | KPIs financieros, actividad reciente y métricas comerciales. |
| Clientes | Disponible | Creación, edición, búsqueda y eliminación controlada. |
| Servicios | Disponible | Catálogo de servicios y precios unitarios. |
| Cotizaciones | Disponible | Creación, edición, duplicación, cancelación y conversión a factura. |
| Cuentas de cobro | Disponible | Gestión de documentos de cobro y registro de pagos. |
| Facturas | Disponible | Creación, edición, anulación, PDF y registro de pagos. |
| Gastos | Disponible | Registro por fecha, categoría y monto. |
| Campañas IA | Disponible | Generación e historial de propuestas para Meta Ads. |
| Casos de estudio | Disponible | Administración del portafolio público. |

## Pendientes

Ordenados según el plan [2026-09-17-cierre-pendientes-plataforma](docs/superpowers/plans/2026-09-17-cierre-pendientes-plataforma.md):

| # | Pendiente | Fase | Estado |
| --- | --- | --- | --- |
| 1 | Repositorio Git inexistente | Fase 1 | Completada (baseline en `master`) |
| 2 | Código muerto residual de tenancy/roles | Fase 2 | Completada (commit `b48b7c7`) |
| 3 | Generación de Meta Ads síncrona (sin job en cola) | Fase 3 | Pendiente |
| 4 | Reporte de auditoría final `docs/audits/2026-08-23-platform-remediation.md` | Fase 4 | Pendiente |
| 5 | Planes/documentación desincronizados | Fase 5 | Pendiente |

## Registro de cambios

### 2026-09-17

- Se creó el plan por fases [2026-09-17-cierre-pendientes-plataforma](docs/superpowers/plans/2026-09-17-cierre-pendientes-plataforma.md) para el cierre de los 5 pendientes del proyecto.
- Fase 1 completada: se inicializó el repositorio Git en la raíz (rama `master`) con `git init`.
- Se creó el `.gitignore` raíz y se verificó la cobertura de `.env`, `backups/`, `tmp/`, tooling local del agente y artefactos de build.
- Commit baseline `f5aa1b7` (268 archivos). Se detectó y retiró en `bbc7f21` la base SQLite binaria `apps/laravel/kamo_laravel` (180 KB) que se había colado al staging.
- Working tree limpio y sin archivos sensibles trackeados.
- Verificación: `git log --oneline`, `git status --short`, `git check-ignore apps/laravel/.env backups/`.
- Fase 2 completada: se eliminó el código muerto de la tenancy/roles revertida (modelos `Role`, `Permission`, `RecurringDocument`; middleware `CheckPermission`; seeders `RolePermission`, `AdminUser`, `CoreData`, `CaseStudy`; factory `RecurringDocumentFactory`; comandos legacy vacíos) y la relación `Client::recurringDocuments`.
- Se retiraron las migraciones que creaban `roles_and_permissions_tables` y `recurring_documents`, y se añadió `2026_09_17_000000_drop_orphaned_tenancy_tables.php` para eliminar ambas tablas en bases existentes.
- Se marcó el plan `2026-08-23-security-tenancy-access.md` como `SUPERSEDED`.
- Commit `b48b7c7` (15 archivos, 332 eliminaciones). Verificación: `php artisan test` (85 tests / 294 aserciones, en verde) y búsqueda de símbolos huérfanos sin resultados.

### 2026-09-16

- Se creó esta bitácora como fuente de seguimiento técnico y funcional del proyecto.
- Se documentó el alcance actual de la plataforma y sus módulos principales.
- Se confirmó que las pruebas focalizadas de clientes y pagos estaban operativas durante la revisión.

## Convención para nuevas entradas

Agregar una entrada por cambio relevante con este formato:

```md
### AAAA-MM-DD - Título breve

- Cambio realizado.
- Motivo o impacto funcional.
- Verificación ejecutada: `comando`.
```

## Comandos de verificación

```bash
# Entorno
docker compose up -d

# Migraciones (contenedor)
docker compose exec app php artisan migrate

# Pruebas: ejecutar en el host (usa SQLite en memoria y APP_ENV=testing de phpunit.xml)
php artisan test
```

> Importante: `docker compose exec app php artisan test` (sin overrides) falla con errores `419`/CSRF porque el `APP_ENV=local` y `DB_CONNECTION=mysql` del compose pisan la configuración de PHPUnit. Para correr tests dentro del contenedor hay que forzar el entorno:
>
> ```bash
> docker compose exec -e APP_ENV=testing -e DB_CONNECTION=sqlite -e DB_DATABASE=:memory: -e SESSION_DRIVER=array -e CACHE_STORE=array app php artisan test
> ```
