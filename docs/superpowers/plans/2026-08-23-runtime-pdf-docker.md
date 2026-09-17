# Runtime, PDF & Docker Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

> **Estado (2026-09-17):** plan cerrado. `intl`, los PDFs sin PNG y el loader global
> quedaron verificados por los gates de la
> [auditoría de remediación](../../audits/2026-08-23-platform-remediation.md)
> (`npm run test:js` 7/7, `npm run build` OK, `intl` presente en el contenedor, PDFs con
> `%PDF` en la suite).
>
> **Convención de checkboxes:** `[x]` = entregable reproducido hoy (gate automático verde
> o artefacto presente en el repositorio). Los pasos de estado rojo TDD (`Step 2`), los
> checkpoints de commit históricos (`Step 5`) y las verificaciones manuales en navegador
> quedan **sin marcar** porque no son reproducibles en este entorno.

**Goal:** Remove shared browser errors, make loaders accessible, generate PDFs within 128 MB, and provide the required PHP runtime extensions.

**Architecture:** Isolate orb preset selection into a pure tested module, keep animation behavior inside the custom element, and remove duplicate global request hooks. Replace Dompdf's expensive alpha-PNG logo with a lightweight HTML/CSS brand mark. Rebuild the PHP image with `intl` enabled.

**Tech Stack:** JavaScript ES modules, Node built-in test runner, Vite 8, Laravel/Dompdf, PHP 8.4 Alpine, Docker Compose

**Spec:** `docs/superpowers/specs/2026-08-23-platform-audit-remediation-design.md`

## Global Constraints

- Preserve the orbital loader and Kamo branding.
- Honor `prefers-reduced-motion`.
- PDF generation must pass under the normal 128 MB memory limit.
- No duplicate global fetch or Livewire hooks.
- Git metadata is absent in this workspace; run commit commands after repository restoration.

---

### Task 1: Valid thinking-orb presets and reduced motion

**Files:**
- Create: `apps/laravel/resources/js/thinking-orb-preset.js`
- Create: `apps/laravel/tests/js/thinking-orb-preset.test.mjs`
- Modify: `apps/laravel/resources/js/thinking-orb.js`
- Modify: `apps/laravel/package.json`
- Modify: `apps/laravel/resources/css/thinking-orb.css`

**Interfaces:**
- Produces: `presetSizeFor(displaySize): 24|64`; reduced-motion detection remains an internal custom-element responsibility.

- [x] **Step 1: Write failing pure JavaScript tests**

```js
import test from 'node:test';
import assert from 'node:assert/strict';
import { presetSizeFor } from '../../resources/js/thinking-orb-preset.js';

test('maps every small display size to the 24 preset', () => {
  for (const size of [14, 16, 18, 20, 24, 40, 47]) {
    assert.equal(presetSizeFor(size), 24);
  }
});

test('maps large display sizes to the 64 preset', () => {
  for (const size of [48, 56, 64, 80]) {
    assert.equal(presetSizeFor(size), 64);
  }
});
```

- [ ] **Step 2: Run the new JavaScript test and verify the missing module failure**

Run: `node --test tests/js/thinking-orb-preset.test.mjs`

Expected: FAIL with module-not-found.

- [x] **Step 3: Implement preset mapping and use it in the custom element**

```js
export function presetSizeFor(displaySize) {
  return Number(displaySize) >= 48 ? 64 : 24;
}
```

In `thinking-orb.js`, call `resolvePreset(state, presetSizeFor(size))`. Add a `matchMedia('(prefers-reduced-motion: reduce)')` check: draw one frame at `t = 0.6`, do not start `requestAnimationFrame`, and keep the canvas accessible name. Replace `transition: all` in `thinking-orb.css` with explicit properties.

Add package script: `"test:js": "node --test tests/js/*.test.mjs"`.

- [x] **Step 4: Run unit test and production build**

Run: `npm run test:js`

Expected: PASS, 2 tests.

Run: `npm run build`

Expected: exit 0.

- [ ] **Step 5: Record the task checkpoint**

```bash
git add apps/laravel/resources/js apps/laravel/resources/css/thinking-orb.css apps/laravel/tests/js apps/laravel/package.json
git commit -m "fix: normalize thinking orb presets"
```

### Task 2: Single global loading controller

**Files:**
- Modify: `apps/laravel/resources/js/thinking-orb.js`
- Modify: `apps/laravel/resources/views/components/layouts/app.blade.php`
- Modify: `apps/laravel/resources/views/layouts/guest.blade.php`
- Modify: `apps/laravel/resources/views/layouts/landing.blade.php`
- Create: `apps/laravel/tests/Feature/SharedLayoutTest.php`

**Interfaces:**
- Consumes: `window.showThinkingLoader(label, state)` and `window.hideThinkingLoader()`.
- Produces: exactly one HUD per page and exactly one Livewire hook registration.

- [x] **Step 1: Write a failing shared-layout test**

```php
public function test_layouts_render_one_global_hud_and_do_not_duplicate_livewire_hooks(): void
{
    $html = $this->get('/login')->assertOk()->getContent();

    $this->assertSame(1, substr_count($html, 'id="global-thinking-hud"'));
    $this->assertStringNotContainsString("Livewire.hook('commit'", $html);
}
```

- [ ] **Step 2: Run the test and verify inline duplicate hook failure**

Run: `php artisan test tests/Feature/SharedLayoutTest.php`

Expected: FAIL while layouts contain inline hook code.

- [x] **Step 3: Centralize lifecycle setup in `thinking-orb.js`**

Use a window guard:

```js
if (!window.__kamoLoaderHooksInstalled) {
  window.__kamoLoaderHooksInstalled = true;
  document.addEventListener('livewire:init', installLivewireLoaderHook, { once: true });
  installNavigationLoader();
}
```

Remove inline Livewire hook scripts from Blade layouts. Keep one HUD container in each top-level layout because only one layout renders per response. Ensure timers are cleared on success/failure and Livewire navigation.

- [ ] **Step 4: Run layout tests and browser console smoke check** _(tests y build verde; consola del navegador pendiente de revisión manual)_

Run: `php artisan test tests/Feature/SharedLayoutTest.php`

Expected: PASS.

Run: `npm run build`

Expected: exit 0 and no duplicate custom-element warning.

- [ ] **Step 5: Record the task checkpoint**

```bash
git add apps/laravel/resources/js/thinking-orb.js apps/laravel/resources/views apps/laravel/tests/Feature/SharedLayoutTest.php
git commit -m "fix: centralize global loading feedback"
```

### Task 3: Memory-safe branded PDFs

**Files:**
- Modify: `apps/laravel/resources/views/pdf/quote.blade.php`
- Modify: `apps/laravel/resources/views/pdf/meta-ads-quote.blade.php`
- Modify: `apps/laravel/tests/Feature/MetaAds/PdfTest.php`
- Create: `apps/laravel/tests/Feature/Documents/PdfTest.php`

**Interfaces:**
- Produces: valid PDF response without decoding `Imagotipo-DK.png`.

- [x] **Step 1: Strengthen PDF regression tests**

```php
$response = $this->actingAs($user)->get(route('meta-ads.pdf', $quote));
$response->assertOk();
$response->assertHeader('content-type', 'application/pdf');
$this->assertStringStartsWith('%PDF', $response->streamedContent());
```

Add the equivalent assertion for `documents.pdf` using a company-owned document with one item.

- [ ] **Step 2: Run the focused test under 128 MB and verify the crash**

Run: `php -d memory_limit=128M artisan test tests/Feature/MetaAds/PdfTest.php`

Expected: FAIL with Dompdf PNG alpha allocation exhaustion.

- [x] **Step 3: Replace raster logo embedding with lightweight brand markup**

Remove base64 image loading from both templates and render:

```blade
<div class="brand-mark">⚡</div>
<div class="brand-name">Kamo.Dev</div>
```

Use PDF-safe CSS, text, borders, and existing coral/black colors. Do not use remote resources, SVG filters, or alpha-heavy raster images.

- [x] **Step 4: Run both PDF tests at the normal memory limit**

Run: `php -d memory_limit=128M artisan test tests/Feature/MetaAds/PdfTest.php tests/Feature/Documents/PdfTest.php`

Expected: PASS with `%PDF` content.

- [ ] **Step 5: Record the task checkpoint**

```bash
git add apps/laravel/resources/views/pdf apps/laravel/tests/Feature/MetaAds/PdfTest.php apps/laravel/tests/Feature/Documents/PdfTest.php
git commit -m "fix: generate branded pdfs within memory limit"
```

### Task 4: Docker `intl` runtime

**Files:**
- Modify: `apps/laravel/Dockerfile`
- Modify: `README.md`

**Interfaces:**
- Produces: PHP image with `intl`, `gd`, `pdo_mysql`, `zip`, and `opcache` loaded.

- [ ] **Step 1: Capture the failing runtime check** _(estado rojo histórico; `intl` ya está instalado y verificado)_

Run: `docker exec kamo_app php -r "exit(extension_loaded('intl') ? 0 : 1);"`

Expected: exit 1.

- [ ] **Step 2: Add ICU build dependencies and `intl`**

Update the Docker layer:

```dockerfile
RUN apk add --no-cache \
        icu-dev \
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql zip gd intl opcache
```

Document `docker compose build app && docker compose up -d --force-recreate app nginx` in README.

- [x] **Step 3: Rebuild and recreate the app container**

Run: `docker compose build app`

Expected: exit 0.

Run: `docker compose up -d --force-recreate app nginx`

Expected: app and nginx are running.

- [x] **Step 4: Verify the extension and Laravel command**

Run: `docker exec kamo_app php -r "echo extension_loaded('intl') ? 'intl-ok' : 'intl-missing';"`

Expected: `intl-ok`.

Run: `docker exec kamo_app php artisan db:show --counts`

Expected: exit 0 without the Number formatting exception.

- [ ] **Step 5: Record the task checkpoint**

```bash
git add apps/laravel/Dockerfile README.md
git commit -m "fix: add php intl to docker runtime"
```
