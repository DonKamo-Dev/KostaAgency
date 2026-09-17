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
| 2 | Código muerto residual de tenancy/roles | Fase 2 | Pendiente |
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
docker-compose up -d
docker-compose exec laravel php artisan migrate
docker-compose exec laravel php artisan test
```
