# Plan de Cierre — Pendientes de la Plataforma Kamo

> **Para workers agentic:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) o superpowers:executing-plans. Los pasos usan checkboxes (`- [ ]`) para seguimiento.

**Meta:** Cerrar los cinco pendientes verificados del proyecto: (1) repositorio Git inexistente, (2) código muerto residual de la tenancy/roles revertida, (3) generación de Meta Ads síncrona sin job en cola, (4) reporte de auditoría final nunca publicado, y (5) planes/documentación desincronizados.

**Arquitectura:** Inicializar control de versiones como línea base; eliminar únicamente el código muerto con dependencia activa confirmada; mover la generación de Meta Ads a un job de cola con estado persistido en `ai_meta_quotes`; ejecutar los gates finales; publicar el reporte de auditoría; sincronizar documentación y taggear una versión estable.

**Contexto verificado (2026-09-17):**
- El plan `2026-08-23-security-tenancy-access.md` quedó obsoleto: la migración `2026_08_24_000000_remove_multi_company_structure.php` revirtió el multi-empresa y ya no existe ninguna referencia a `company` en `app/`.
- Código muerto con riesgo confirmado de ORFANDAD (sin rutas, controller ni registro):
  - Modelos `Role.php`, `Permission.php`, `RecurringDocument.php`.
  - Middleware `CheckPermission.php` (no registrado en `bootstrap/app.php`, passthrough no-op).
  - Relación `Client::recurring_documents` (`Client.php:27`).
  - Migración `2026_04_28_193845_create_roles_and_permissions_tables.php` (crea la tabla basura `roles_and_permissions_tables`).
  - Seeders huérfanos: `RolePermissionSeeder`, `AdminUserSeeder`, `CoreDataSeeder`, `CaseStudySeeder` (`DatabaseSeeder` solo llama a `InitialSetupSeeder`).
  - Factory `RecurringDocumentFactory.php`.
- `Wizard::generate()` (`app/Livewire/MetaAds/Wizard.php:180`) llama al servicio con `set_time_limit(120)` de forma síncrona.
- El entorno Docker usa `QUEUE_CONNECTION=database` (`.env.docker`) y existe la tabla `jobs`, pero **no hay worker de cola** en `docker-compose.yml` ni servicio Redis.
- `docs/audits/` no existe: el reporte `2026-08-23-platform-remediation.md` nunca se creó.
- Confirmado `fatal: not a git repository` en la raíz del workspace.

**Tech Stack:** Git, Laravel 13, Livewire 3, queue (database), PHPUnit 12, Vite 8, Docker Compose, docencia de planes superpowers.

## Convenciones globales

- Cada fase termina con gates verdes y un commit único y descriptivo.
- Antes de cada commit: revisar `git status`, `git diff --cached`, y confirmar que `apps/laravel/.env` y `` `backups/` `` NO están staged.
- Todos los cambios de comportamiento usan red-green-refactor.
- Preservar la identidad visual y los flujos actuales; no reestructurar navegación ni branding.
- No commitear secretos (`*.env`, dumps SQL, `.env.testing`, `.env.docker`).

---

## Fase 1 — Control de versiones (línea base Git)

**Objetivo:** Tener historial y puntos de restauración antes de tocar código.

### Task 1.1: Ignorados raíz y revisión de secretos

**Archivos:**
- Crear: `.gitignore` (raíz)
- Revisar: `apps/laravel/.gitignore`

**Pasos:**
- [ ] **Step 1:** Crear `.gitignore` raíz que excluya al menos:
  ```gitignore
  # Entornos y secretos
  apps/laravel/.env
  apps/laravel/.env.testing
  apps/laravel/.env.docker
  .env

  # Dependencias y build
  apps/laravel/vendor/
  apps/laravel/node_modules/
  apps/laravel/public/build/

  # Datos locales y temporales
  backups/
  tmp/
  *.sql
  *.log

  # Herramientas locales del operador
  .agents/
  .claude/
  .opencode/
  ```
  Confirmar que `.env.example`, `.gitignore`, `docker-compose.yml` y `README.md` queden trackeados.
- [ ] **Step 2:** Verificar que lo sensible está cubierto: `git check-ignore apps/laravel/.env backups/` e inspeccionar que no exista otro `.env` o dump fuera de los ignorados.
- [ ] **Step 3:** Definir identidad local si no existe: `git config user.name "Kamo"` y `git config user.email "yohanblaro18@gmail.com"` (solo local de este repo).

### Task 1.2: Commit baseline

**Pasos:**
- [ ] **Step 1:** `git init` en la raíz del workspace (look para que la raíz del proyecto sea `Kamo-Plataform`).
- [ ] **Step 2:** `git add -A` y revisar el listado: `git diff --cached --name-only`.
- [ ] **Step 3:** Confirmar que `apps/laravel/.env` NO aparece y que `tmp/` y `backups/` NO aparecen.
- [ ] **Step 4:** Commit del estado actual sin modificaciones: `git commit -m "chore: baseline del proyecto tras auditoría de remediación"`.
- [ ] **Step 5:** Verificación: `git log --oneline -1` debe mostrar el commit baseline.

**Gate:** `git status` limpio y al menos 1 commit.

---

## Fase 2 — Limpieza de código muerto de tenancy/roles

**Objetivo:** Eliminar hardware heredado con prueba de orfandad y dejar las migraciones/seeders coherentes con el workspace privado único.

### Task 2.1: Prueba de referencias activas

**Pasos:**
- [ ] **Step 1:** Ejecutar:
  ```bash
  rg -n "Role|Permission|RecurringDocument|CheckPermission|roles_and_permissions|RolePermissionSeeder|AdminUserSeeder|CoreDataSeeder|CaseStudySeeder" apps/laravel --glob '!vendor/**' --glob '!storage/**' --glob '!node_modules/**'
  ```
- [ ] **Step 2:** Confirmar que los únicos matches son las definiciones mismas (autoload incl.) y que ninguna ruta (`routes/web.php`), controller, Livewire, Blade o seeder activo referencie estos símbolos.
- [ ] **Step 3:** Documentar en un comentario del PR/commit (o en el reporte de auditoría) cada archivo y su evidencia de orfandad.

### Task 2.2: Eliminación confirmada

**Archivos a eliminar (solo si Step 2.1.2 confirma orfandad):**
- `apps/laravel/app/Models/Role.php`
- `apps/laravel/app/Models/Permission.php`
- `apps/laravel/app/Models/RecurringDocument.php`
- `apps/laravel/app/Http/Middleware/CheckPermission.php`
- `apps/laravel/database/migrations/2026_04_28_193845_create_roles_and_permissions_tables.php`
- `apps/laravel/database/factories/RecurringDocumentFactory.php`
- `apps/laravel/database/seeders/RolePermissionSeeder.php`
- `apps/laravel/database/seeders/AdminUserSeeder.php` (su función ya la cubre `InitialSetupSeeder`)
- `apps/laravel/database/seeders/CoreDataSeeder.php`
- `apps/laravel/database/seeders/CaseStudySeeder.php` (solo si no lo invoca ningún seeder activo)

**Archivos a modificar:**
- `apps/laravel/app/Models/Client.php` — eliminar la relación `recurring_documents()` (línea 27).
- `apps/laravel/app/Models/RecurringDocument.php` — eliminado junto con su factory.

**Pasos:**
- [ ] **Step 1:** Crear y ejecutar la migración de limpieza de la tabla huérfana (si existe en BD): `Schema::dropIfExists('roles_and_permissions_tables')`. Nombre sugerido: `2026_09_17_000000_drop_orphaned_roles_tables.php`.
- [ ] **Step 2:** Aplicar las eliminaciones de archivos listadas.
- [ ] **Step 3:** `php artisan migrate` (y un `migrate:fresh --env=testing` para validar cero tablas huérfanas).

### Task 2.3: Archivar el plan obsoleto de tenancy

**Archivos:**
- Mover: `docs/superpowers/plans/2026-08-23-security-tenancy-access.md` → `docs/superpowers/archive/2026-08-23-security-tenancy-access.md` (o añadir header `> **SUPERSEDED** — revertida por 2026_08_24_000000_remove_multi_company_structure.php` sin borrarlo).

**Pasos:**
- [ ] **Step 1:** Añadir el aviso `SUPERSEDED` al inicio del documento y moverlo/renombrarlo si se prefiere.
- [ ] **Step 2:** Referenciar el plan archivado en el reporte de auditoría (Fase 4).

### Task 2.4: Gates de la fase

- [ ] **Step 1:** `php artisan test` (esperado: 85+ tests verdes).
- [ ] **Step 2:** `vendor/bin/pint app database tests` (esperado: sin errores en archivos tocados).
- [ ] **Step 3:** Commit: `git add -A && git commit -m "chore: remove legacy tenancy/roles code"`.

---

## Fase 3 — Generación de Meta Ads en segundo plano (queued job)

**Objetivo:** Sustituir la llamada síncrona de `Wizard::generate()` por un job en cola con estado persistido, preservando inputs ante fallos y manteniendo PDF/historial intactos.

### Task 3.1: Estado persistente en `ai_meta_quotes`

**Archivos:**
- Crear: `apps/laravel/database/migrations/2026_09_17_000001_add_generation_status_to_ai_meta_quotes.php`
- Modificar: `apps/laravel/app/Models/AiMetaQuote.php`

**Interfaces:**
- Produces: columnas `generation_status` (`pending|processing|completed|failed`, default `completed`) y `error` (text nullable) para cohortes existentes.

**Pasos:**
- [ ] **Step 1:** Migración:
  ```php
  $table->string('generation_status', 20)->default('completed')->after('ai_result');
  $table->text('error')->nullable()->after('generation_status');
  ```
- [ ] **Step 2:** Añadir a `$casts`/nullable del modelo y protección de escritura manual (`generation_status` fuera de `$fillable` o gestionado solo por el job).

### Task 3.2: Job `GenerateMetaAdsQuote`

**Archivos:**
- Crear: `apps/laravel/app/Jobs/GenerateMetaAdsQuote.php`

**Interfaces:**
- Consumes: `ClaudeMetaAdsService` y el id de `AiMetaQuote`.
- Produces: `handle(): void` idempotente que: carga la cotización por id, marca `processing`, llama `$service->generate($datos)`, persiste `ai_result` y `completed`; en `\Throwable` guarda `failed` + mensaje en `error` y relanza según política de reintentos (`backoff`).

**Pasos:**
- [ ] **Step 1:** Escribir el job con transición de estados y `backoff` por fallo transitorio.
- [ ] **Step 2:** El job NO depende de `auth()` (los jobs corren sin sesión); recibe el payload completo o el id de `AiMetaQuote`.

### Task 3.3: Refactor del wizard

**Archivos:**
- Modificar: `apps/laravel/app/Livewire/MetaAds/Wizard.php`
- Modificar: `apps/laravel/resources/views/livewire/meta-ads/wizard.blade.php`

**Interfaces:**
- Consumes: `GenerateMetaAdsQuote`.
- Produces: `generate()` que persiste la cotización en `pending`, despacha el job y pasa a un estado de espera; `wire:poll` mientras `generating`; al completar redirige/limpiar; al fallar muestra `error` conservando todos los inputs.

**Pasos:**
- [ ] **Step 1:** Extraer la construcción del payload (industry/interests/"Otro") a un método privado reutilizable.
- [ ] **Step 2:** `generate()`: validar → `AiMetaQuote::create([... , 'generation_status' => 'pending'])` → `dispatch(new GenerateMetaAdsQuote($quote->id))` → `$this->generating = true`.
- [ ] **Step 3:** Añadir un método `checkGeneration()` consultable por `wire:poll.3s` que simple estado: `completed` → redirect a historial/ver; `failed` → error visible; `processing/pending` → seguir.
- [ ] **Step 4:** Mantener el botón de generar deshabilitado y `aria-busy="true"` mientras espera; el error de la API sigue siendo accionable y con inputs preservados.
- [ ] **Step 5:** Quitar el `// TODO` y el `set_time_limit(120)`.

### Task 3.4: Infraestructura de cola

**Archivos:**
- Modificar: `docker-compose.yml`
- Modificar: `apps/laravel/README.md`

**Interfaces:**
- Produces: servicio `queue` basado en la imagen `app`, comando `php artisan queue:work --sleep=3 --tries=3`, `depends_on: db` y volumen bind de código.

**Pasos:**
- [ ] **Step 1:** Añadir el servicio `queue` al compose:
  ```yaml
  queue:
    build:
      context: apps/laravel
      dockerfile: Dockerfile
    container_name: kamo_queue
    restart: unless-stopped
    volumes: ["./apps/laravel:/var/www/html"]
    environment:
      APP_ENV: local
      DB_CONNECTION: mysql
      DB_HOST: db
      DB_DATABASE: kamo_laravel
      DB_USERNAME: user
      DB_PASSWORD: user_password
      QUEUE_CONNECTION: database
    depends_on:
      db: { condition: service_healthy }
    command: php artisan queue:work --sleep=3 --tries=3 --timeout=300
  ```
- [ ] **Step 2:** Confirmar `QUEUE_CONNECTION=database` en `.env.docker` (ya existe) y que la tabla `jobs` esté creada por la migración base.
- [ ] **Step 3:** Documentar en `apps/laravel/README.md`: `docker compose up -d` levanta también el worker; en desarrollo local fuera de Docker, `php artisan queue:work`.

### Task 3.5: Pruebas

**Archivos:**
- Crear: `apps/laravel/tests/Feature/MetaAds/BackgroundGenerationTest.php`
- Modificar: `apps/laravel/tests/Feature/MetaAds/PdfTest.php` (solo si cambia el flujo de creación)

**Pasos:**
- [ ] **Step 1:** `Queue::fake()` → `assertPushed(GenerateMetaAdsQuote::class)` al enviar el wizard con payload válido.
- [ ] **Step 2:** Ejecutar el job con `QUEUE_CONNECTION=sync` (test) y verificar que `ai_meta_quotes` queda `completed` con `ai_result` persistido.
- [ ] **Step 3:** Fallo del servicio (mock de `ClaudeMetaAdsService`) → estado `failed`, `error` poblado, y el wizard conserva los inputs (assert de estado en el componente Livewire via `Livewire::test`).
- [ ] **Step 4:** Regresión: `tests/Feature/MetaAds` completo + `tests/Unit/ClaudeMetaAdsServiceTest` verdes.

### Task 3.6: Gates y commit

- [ ] `php artisan test`, `npm run test:js`, `npm run build`.
- [ ] Commit: `git add -A && git commit -m "feat: generate meta ads quotes asynchronously"`.

---

## Fase 4 — Gates completos y reporte de auditoría final

**Objetivo:** Publicar el entregable faltante de la remediación con evidencia reproducible.

### Task 4.1: Gates de calidad

- [ ] **Step 1:** `php artisan test` → exit 0, cero fallos.
- [ ] **Step 2:** `npm run test:js && npm run build` → exit 0.
- [ ] **Step 3:** `vendor/bin/pint --test app bootstrap config database routes tests` (hallazgos de formato heredados listados por separado, no bloqueantes).
- [ ] **Step 4:** Contenedores: `docker compose ps` (app/db/nginx/queue healthy) y `docker exec kamo_app php -m | grep intl`.
- [ ] **Step 5:** Smoke en navegador de los flujos auditados en los cuatro viewports (login, dashboard, CRUD, cotización→factura→pago, PDF, Meta Ads wizard/historial) registrando consola y overflow horizontal.

### Task 4.2: Publicar el reporte

**Archivos:**
- Crear: `docs/audits/2026-08-23-platform-remediation.md` (nombre histórico definido por el plan original; el contenido documenta el estado verificado a fecha real).

**Interfaces:**
- Produces: reporte final con: contexto y decisiones (incl. reversión de tenancy), comandos ejecutados, conteos de tests (PHP/JS), gates pasados, hallazgos no bloqueantes, artefactos generados y referencias a planes archivados.

**Pasos:**
- [ ] **Step 1:** Estructura del reporte: Resumen → Alcance auditado → Gates (tabla de comandos/resultados) → Hallazgos resueltos → Hallazgos no bloqueantes → Siguientes pasos.
- [ ] **Step 2:** Referenciar `docs/superpowers/archive/2026-08-23-security-tenancy-access.md` (SUPERSEDED) y este plan.
- [ ] **Step 3:** Commit: `git add docs/audits && git commit -m "docs: publish platform remediation audit report"`.

---

## Fase 5 — Sincronización de documentación y cierre

**Objetivo:** Dejar planes, bitácora y README coherentes con el estado final.

### Task 5.1: Actualizar planes existentes

**Archivos:**
- Modificar: `docs/superpowers/plans/2026-08-23-ui-accessibility-consistency.md` (marcar tareas completadas, anotar que el reporte se publica en Fase 4 de este plan)
- Modificar: `docs/superpowers/plans/2026-08-23-runtime-pdf-docker.md` (checkboxes completados)
- Modificar: `docs/superpowers/plans/2026-08-23-financial-document-workflows.md` (checkboxes completados)
- Modificar: `docs/superpowers/specs/2026-08-23-platform-audit-remediation-design.md` (añadir sección "Estado final" con la decisión de no multi-empresa)

**Pasos:**
- [ ] **Step 1:** Marcar `- [ ]` → `- [x]` solo en tareas verificadas por gates reales.
- [ ] **Step 2:** En la spec, anexar la decisión de revertir tenancy (con enlace a la migración y al plan archivado).

### Task 5.2: Actualizar BITACORA.md

**Pasos:**
- [ ] **Step 1:** Añadir entradas por fase completada (esto inicia en `### 2026-09-17`).
- [ ] **Step 2:** Registrar: baseline Git (Fase 1), limpieza de código muerto (Fase 2), job async de Meta Ads (Fase 3), reporte de auditoría (Fase 4), sincronización de docs (Fase 5), cada una con verificación ejecutada.

### Task 5.3: README

**Pasos:**
- [ ] Verificar que `apps/laravel/README.md` (o el raíz) mencione: worker de cola, estado del repositorio Git y comandos de verificación usados.

### Task 5.4: Commit de cierre y versión

**Pasos:**
- [ ] **Step 1:** `git add -A && git commit -m "docs: sync plans, bitacora and readme"`.
- [ ] **Step 2:** Opcional si el usuario lo aprueba: `git tag v1.0.0` como primer corte estable post-línea base.

---

## Orden, dependencias y criterios de aceptación globales

**Dependencias:**
- Fase 2 depende de Fase 1 (historial para auditar la limpieza).
- Fase 3 es independiente de Fase 2 (pero conviene tras Fase 1 para commits limpios).
- Fase 4 depende de Fases 2 y 3 (el reporte debe reflejar el estado final).
- Fase 5 depende de Fases 2–4.

**Criterios de aceptación:**
- `git log` muestra al menos 5 commits temáticos y `git status` limpio.
- Cero símbolos de tenancy/roles huérfanos en `app/`, `routes/`, `database/` (verificable con `rg`).
- La generación de Meta Ads funciona con `QUEUE_CONNECTION=database` y worker activo; los tests cubren el despacho, éxito y fallo.
- `docs/audits/2026-08-23-platform-remediation.md` existe y es reproducible.
- Plan archivado `SUPERSEDED`, `BITACORA.md` al día, checkboxes de planes sincronizados.
- `php artisan test` + `npm run test:js` + `npm run build` en verde al cierre.

## No objetivos

- Reintroducir multi-empresa (decisión revertida en 2026-08-24).
- Rediseñar la UX del wizard o cambiar los flujos de documentos.
- Añadir Redis/Redis para la cola (se usa `database` para minimizar infraestructura).
- Migrar el frontend a otro framework ni reestructurar la navegación.