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
| Casos de estudio | Disponible | Administración del portafolio público. |

## Pendientes

Ordenados según el plan [2026-09-17-cierre-pendientes-plataforma](docs/superpowers/plans/2026-09-17-cierre-pendientes-plataforma.md):

| # | Pendiente | Fase | Estado |
| --- | --- | --- | --- |
| 1 | Repositorio Git inexistente | Fase 1 | Completada (remoto `origin` en `main`) |
| 2 | Código muerto residual de tenancy/roles | Fase 2 | Completada (commit `b48b7c7`) |
| 3 | Generación de Meta Ads síncrona (sin job en cola) | Retirado | Módulo eliminado a solicitud del usuario |
| 4 | Reporte de auditoría final `docs/audits/2026-08-23-platform-remediation.md` | Fase 4 | Completada (commit `399089b`) |
| 5 | Planes/documentación desincronizados | Fase 5 | Completada (commit de cierre) |

## Registro de cambios

### 2026-09-18

- Se agregó el enlace de acceso directo a **Bento** (`/bento`) en la barra lateral debajo de Dashboard en la sección "Principal".
- Se actualizaron los archivos `README.md` (raíz y `apps/laravel`) retirando las instrucciones y comandos obsoletos del worker de cola.
- Se eliminó completamente el sistema de IA automático de Meta Ads:
  - Eliminados: job `GenerateMetaAdsQuote`, servicio `ClaudeMetaAdsService`, modelo `AiMetaQuote`, factory `AiMetaQuoteFactory`, controlador `MetaAdsController`, componentes Livewire `Wizard` y `History`.
  - Eliminadas: vistas de Livewire y plantilla PDF de cotización Meta Ads (`resources/views/livewire/meta-ads/`, `resources/views/pdf/meta-ads-quote.blade.php`).
  - Eliminado el subenlace de navegación en el sidebar (`components/layouts/app.blade.php`), rutas en `routes/web.php`, y credenciales en `config/services.php` (`groq`, `anthropic`).
  - Creada y ejecutada la migración `2026_09_18_000000_drop_ai_meta_quotes_table.php` para eliminar la tabla `ai_meta_quotes` en MySQL.
  - Retirado el servicio worker `kamo_queue` de `docker-compose.yml`, liberando recursos de cómputo.
- Formateo de código PHP heredado con Laravel Pint:
  - Se ejecutó `vendor/bin/pint` sobre `app`, `bootstrap`, `config`, `database`, `routes` y `tests`.
  - Verificación `vendor/bin/pint --test` con 0 archivos con advertencias (`passed`).
- Smoke visual multi-viewport interactivo con subagente de navegador:
  - Probado en Desktop (1440x900), Tablet (768x1024) y Mobile (375x667).
  - Flujo autenticado en `/dashboard`, `/clients`, `/quotes`, `/invoices`.
  - Verificada la correcta visualización de bottom nav móvil y FAB, sin desbordamiento horizontal (`scrollWidth <= innerWidth`).
  - Pruebas automatizadas: 77/77 tests PHP en verde (273 aserciones) y 7/7 tests JS en verde.
- Se renombró la rama principal a `main` (`git branch -M main`).
- Se configuró el repositorio remoto `origin` apuntando a `https://github.com/DonKamo-Dev/KostaAgency.git`.
- Se autenticó Git con la cuenta `DonKamo-Dev` y se subió el código completo a la rama `main` (`git push -u origin main`).

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
- Fase 3 completada: la generación de Meta Ads ahora corre en segundo plano con el job `App\Jobs\GenerateMetaAdsQuote` (`tries=2`, `timeout=120`), la migración `2026_09_17_000001_add_generation_status_to_ai_meta_quotes.php` (`generation_status`, `error`, índice) y el servicio `queue` en `docker-compose.yml`.
- El componente `Wizard` crea la cotización en `pending`, despacha el job y hace polling con `wire:poll.3s="checkGeneration"`; `History` lista solo cotizaciones `completed`.
- Commit `f190689` (9 archivos, +355/−51); refinamiento de accesibilidad y cobertura de fallo en commit posterior. Verificación: `php artisan test` (91 tests / 310 aserciones, en verde), `npm run test:js` (7 tests) y `npm run build` sin errores.
- Fase 4 completada: se ejecutaron los gates finales (PHP 91/310, JS 7/7, build OK), se levantó el servicio `queue` (`kamo_queue` Up) y se verificó `intl` y la tabla `jobs`. El pipeline asíncrono se validó end-to-end: dispatch → cola → worker → `generation_status = failed` con `error` persistido ante una clave Groq inválida.
- Se publicó el reporte `docs/audits/2026-08-23-platform-remediation.md` con gates, smoke HTTP, hallazgos resueltos y hallazgos no bloqueantes (estilo Pint heredado, `KAMO_LOCAL_ACCESS_EMAIL` sin definir, `GROQ_API_KEY` inválida, smoke visual manual pendiente). Commit `399089b`.
- Fase 5 completada: se sincronizaron los planes `2026-08-23-ui-accessibility-consistency.md`, `2026-08-23-runtime-pdf-docker.md` y `2026-08-23-financial-document-workflows.md` con banner de estado y checkboxes marcados solo donde hay gate/artefacto verificable; la spec incorpora la sección "Estado final" (sin multi-empresa) y se actualizaron los README raíz y de `apps/laravel` (cola, verificación y estado de Git).

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

# Worker de cola (generación de Meta Ads en segundo plano)
docker compose up -d queue

# Pruebas: ejecutar en el host (usa SQLite en memoria y APP_ENV=testing de phpunit.xml)
php artisan test
```

> Importante: `docker compose exec app php artisan test` (sin overrides) falla con errores `419`/CSRF porque el `APP_ENV=local` y `DB_CONNECTION=mysql` del compose pisan la configuración de PHPUnit. Para correr tests dentro del contenedor hay que forzar el entorno:
>
> ```bash
> docker compose exec -e APP_ENV=testing -e DB_CONNECTION=sqlite -e DB_DATABASE=:memory: -e SESSION_DRIVER=array -e CACHE_STORE=array app php artisan test
> ```
