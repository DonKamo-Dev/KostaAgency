# UI, Accessibility & Consistency Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Preserve Kamo's current design while making public, auth, and workspace views consistent, responsive, semantic, keyboard accessible, and complete across loading/empty/error states.

**Architecture:** Consolidate shared design tokens and interaction primitives in active layouts, keep controller-backed Blade screens canonical, and align Breeze auth views with Kamo. Make targeted semantic and responsive fixes instead of restructuring navigation or rebranding.

**Tech Stack:** Blade, Livewire 3, Alpine.js, Tailwind CSS 3, Vite 8, browser-based responsive verification

**Spec:** `docs/superpowers/specs/2026-08-23-platform-audit-remediation-design.md`

## Global Constraints

- Preserve coral-red/black palette, glass surfaces, typography, sidebar, and mobile bottom navigation.
- Use a 4 px spacing base and existing radius character.
- Every control has hover, active, focus-visible, and disabled states.
- Validate 390×844, 768×1024, 1280×720, and 1440×900.
- Git metadata is absent in this workspace; run commit commands after repository restoration.

---

### Task 1: Shared tokens, focus, motion, and navigation semantics

**Files:**
- Modify: `apps/laravel/resources/css/app.css`
- Modify: `apps/laravel/resources/views/components/layouts/app.blade.php`
- Modify: `apps/laravel/resources/views/layouts/guest.blade.php`
- Modify: `apps/laravel/resources/views/layouts/landing.blade.php`
- Modify: `apps/laravel/resources/views/partials/landing-nav.blade.php`
- Create: `apps/laravel/tests/Feature/Accessibility/LayoutAccessibilityTest.php`

**Interfaces:**
- Produces: shared skip link, `main` target, focus ring tokens, reduced-motion override, and semantic navigation.

- [ ] **Step 1: Write failing layout accessibility assertions**

```php
public function test_public_and_app_layouts_have_skip_link_main_target_and_theme_color(): void
{
    $public = $this->get('/')->assertOk()->getContent();
    $this->assertStringContainsString('href="#main"', $public);
    $this->assertStringContainsString('id="main"', $public);
    $this->assertStringContainsString('name="theme-color"', $public);
}
```

Add an authenticated assertion for dashboard using a user with attached company.

- [ ] **Step 2: Run the test and verify inconsistent layout primitives**

Run: `php artisan test tests/Feature/Accessibility/LayoutAccessibilityTest.php`

Expected: FAIL for missing app/auth primitives.

- [ ] **Step 3: Normalize shared primitives**

Add CSS variables for four text levels, border levels, control background/border/focus, and explicit transitions. Add:

```css
:focus-visible { outline: 2px solid var(--red-bright); outline-offset: 3px; }
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after { animation-duration: 0.01ms !important; animation-iteration-count: 1 !important; scroll-behavior: auto !important; }
}
```

Add skip links and `<main id="main">`. Set `color-scheme: dark` and matching `theme-color`. Replace clickable non-button navigation toggles with buttons and accessible names.

- [ ] **Step 4: Run layout tests and build**

Run: `php artisan test tests/Feature/Accessibility/LayoutAccessibilityTest.php`

Expected: PASS.

Run: `npm run build`

Expected: exit 0.

- [ ] **Step 5: Record the task checkpoint**

```bash
git add apps/laravel/resources/css/app.css apps/laravel/resources/views/components/layouts/app.blade.php apps/laravel/resources/views/layouts apps/laravel/resources/views/partials/landing-nav.blade.php apps/laravel/tests/Feature/Accessibility/LayoutAccessibilityTest.php
git commit -m "fix: unify accessible layout primitives"
```

### Task 2: Kamo authentication experience in Spanish

**Files:**
- Modify: `apps/laravel/resources/views/livewire/pages/auth/register.blade.php`
- Modify: `apps/laravel/resources/views/livewire/pages/auth/forgot-password.blade.php`
- Modify: `apps/laravel/resources/views/livewire/pages/auth/reset-password.blade.php`
- Modify: `apps/laravel/resources/views/livewire/pages/auth/confirm-password.blade.php`
- Modify: `apps/laravel/resources/views/livewire/pages/auth/verify-email.blade.php`
- Modify: `apps/laravel/resources/views/livewire/pages/auth/login.blade.php`
- Modify: `apps/laravel/tests/Feature/Auth/RegistrationTest.php`
- Modify: `apps/laravel/tests/Feature/Auth/PasswordResetTest.php`

**Interfaces:**
- Produces: consistent Spanish Kamo auth screens with headings, labels, autocomplete, inline errors, and keyboard-accessible controls.

- [ ] **Step 1: Add failing copy and structure assertions**

```php
$this->get('/register')
    ->assertOk()
    ->assertSee('<h1', false)
    ->assertSee('Crear cuenta')
    ->assertSee('Confirmar contraseña')
    ->assertDontSee('Already registered?');
```

Add equivalent Spanish assertions for forgot/reset/verify pages.

- [ ] **Step 2: Run authentication feature tests**

Run: `php artisan test tests/Feature/Auth`

Expected: FAIL on default English copy and missing heading.

- [ ] **Step 3: Apply the existing login visual language to all auth views**

Reuse `auth-wrapper`, `login-card`, `custom-label`, `custom-input`, `btn-primary-red`, and `custom-link`. Every page receives one `<h1>`, Spanish active-voice copy, input `name`, correct `type`, `autocomplete`, clickable labels, inline errors, and specific submit labels. Remove `tabindex="-1"` from the password visibility button and provide visible focus.

- [ ] **Step 4: Run auth tests and mobile browser verification**

Run: `php artisan test tests/Feature/Auth`

Expected: PASS.

Browser: inspect `/login`, `/register`, `/forgot-password` at 390×844; expected no horizontal overflow and all controls reachable by keyboard.

- [ ] **Step 5: Record the task checkpoint**

```bash
git add apps/laravel/resources/views/livewire/pages/auth apps/laravel/tests/Feature/Auth
git commit -m "fix: align authentication views with kamo"
```

### Task 3: Canonical workspace CRUD interaction states

**Files:**
- Modify: `apps/laravel/resources/views/clients/index.blade.php`
- Modify: `apps/laravel/resources/views/services/index.blade.php`
- Modify: `apps/laravel/resources/views/expenses/index.blade.php`
- Modify: `apps/laravel/resources/views/quotes/index.blade.php`
- Modify: `apps/laravel/resources/views/bills/index.blade.php`
- Modify: `apps/laravel/resources/views/invoices/index.blade.php`
- Modify: `apps/laravel/resources/views/partials/crud-app-script.blade.php`
- Modify: `apps/laravel/resources/views/partials/doc-app-script.blade.php`
- Modify: `apps/laravel/resources/views/partials/doc-items-modal.blade.php`
- Create: `apps/laravel/tests/Feature/Accessibility/WorkspaceViewsTest.php`

**Interfaces:**
- Produces: labeled search/forms, accessible modals, confirmed deletion, inline API errors, and stable loading/empty/error states.

- [ ] **Step 1: Write failing semantic view assertions**

```php
public function test_workspace_search_controls_have_accessible_names(): void
{
    foreach (['/clients', '/services', '/expenses', '/quotes', '/bills', '/invoices'] as $path) {
        $this->actingAs($this->user)->get($path)
            ->assertOk()
            ->assertSee('aria-label="Buscar', false);
    }
}
```

Add assertions for `role="dialog"`, `aria-modal="true"`, explicit close-button labels, and a live error region.

- [ ] **Step 2: Run workspace view tests**

Run: `php artisan test tests/Feature/Accessibility/WorkspaceViewsTest.php`

Expected: FAIL.

- [ ] **Step 3: Implement semantic states and safe destructive actions**

Add labels/accessible names to every search and form control. Give modals a heading referenced by `aria-labelledby`, focus the first control on open, return focus on close, close on Escape, contain overscroll, and mark background inert where supported. Replace direct deletion with a Kamo confirmation dialog. In shared scripts, store `requestError`, render it in `aria-live="polite"`, clear loaders in `finally`, and preserve user input after `422` or network errors.

- [ ] **Step 4: Run workspace tests and responsive browser passes**

Run: `php artisan test tests/Feature/Accessibility/WorkspaceViewsTest.php`

Expected: PASS.

Browser: verify each route at 390×844 and 1280×720; expected no document-level overflow, modal fits viewport, and empty/loading/error states remain legible.

- [ ] **Step 5: Record the task checkpoint**

```bash
git add apps/laravel/resources/views/clients apps/laravel/resources/views/services apps/laravel/resources/views/expenses apps/laravel/resources/views/quotes apps/laravel/resources/views/bills apps/laravel/resources/views/invoices apps/laravel/resources/views/partials apps/laravel/tests/Feature/Accessibility/WorkspaceViewsTest.php
git commit -m "fix: complete workspace interaction states"
```

### Task 4: Dashboard and Meta Ads responsive accessibility

**Files:**
- Modify: `apps/laravel/resources/views/dashboard/index.blade.php`
- Modify: `apps/laravel/resources/views/livewire/meta-ads/wizard.blade.php`
- Modify: `apps/laravel/resources/views/livewire/meta-ads/history.blade.php`
- Modify: `apps/laravel/resources/views/livewire/case-studies/index.blade.php`
- Create: `apps/laravel/tests/Feature/Accessibility/DashboardMetaAdsTest.php`

**Interfaces:**
- Produces: tabular financial data, semantic filters/chips, accessible chart fallback, and keyboard-operable Meta Ads selections.

- [ ] **Step 1: Write failing accessibility assertions**

```php
$this->actingAs($this->user)->get('/servicios/meta-ads-ia')
    ->assertOk()
    ->assertDontSee('<div wire:click="setPlatform', false)
    ->assertSee('aria-pressed=', false);
```

Assert dashboard canvas has an accessible description/fallback and financial numbers use a `tabular-nums` class.

- [ ] **Step 2: Run focused tests**

Run: `php artisan test tests/Feature/Accessibility/DashboardMetaAdsTest.php`

Expected: FAIL on clickable divs and missing chart fallback.

- [ ] **Step 3: Replace pointer-only controls and complete data states**

Change platform, industry, duration, age, and interest chips to `<button type="button">` with `aria-pressed`. Add keyboard focus styles. Give charts adjacent text summaries and empty-state copy. Use `font-variant-numeric: tabular-nums` on monetary and percentage comparisons. Make Chart.js initialization idempotent across Livewire navigation.

Apply the same modal semantics, focus behavior, labeled icon buttons, bulk-action confirmation, and live status feedback to the case-study manager.

- [ ] **Step 4: Run tests and browser verification**

Run: `php artisan test tests/Feature/Accessibility/DashboardMetaAdsTest.php tests/Feature/MetaAds`

Expected: PASS.

Browser: verify keyboard operation and layouts at all four target viewports.

- [ ] **Step 5: Record the task checkpoint**

```bash
git add apps/laravel/resources/views/dashboard/index.blade.php apps/laravel/resources/views/livewire/meta-ads apps/laravel/resources/views/livewire/case-studies/index.blade.php apps/laravel/tests/Feature/Accessibility/DashboardMetaAdsTest.php
git commit -m "fix: make dashboard and meta ads accessible"
```

### Task 5: Public website, portfolio, contact, and CV polish

**Files:**
- Modify: `apps/laravel/resources/views/livewire/landing/index.blade.php`
- Modify: `apps/laravel/resources/views/livewire/landing/portfolio.blade.php`
- Modify: `apps/laravel/resources/views/livewire/landing/contact.blade.php`
- Modify: `apps/laravel/resources/views/livewire/landing/bento.blade.php`
- Modify: `apps/laravel/resources/views/partials/landing-nav.blade.php`
- Modify: `apps/laravel/resources/views/partials/landing-footer.blade.php`
- Create: `apps/laravel/tests/Feature/PublicViewsTest.php`

**Interfaces:**
- Produces: valid contact/WhatsApp links, semantic CV action, explicit image dimensions, reliable contact success/error state, and consistent public metadata.

- [ ] **Step 1: Write failing public-view assertions**

```php
public function test_public_pages_have_titles_headings_and_no_placeholder_whatsapp_link(): void
{
    foreach (['/', '/portafolio', '/contacto', '/cv'] as $path) {
        $this->get($path)->assertOk()->assertSee('<h1', false);
    }

    $this->get('/')->assertDontSee('href="https://wa.me/"', false);
    $this->get('/cv')->assertDontSee('<div onclick="copyEmailToClipboard()"', false);
}
```

- [ ] **Step 2: Run public tests**

Run: `php artisan test tests/Feature/PublicViewsTest.php`

Expected: FAIL for placeholder link and clickable CV div.

- [ ] **Step 3: Apply targeted public fixes**

Read the WhatsApp number from `config('kamo.whatsapp_number')`; hide the control when unset instead of rendering an empty destination. Convert the CV email card to a button with accessible copy feedback. Add explicit `width`/`height` to known images, `loading="lazy"` below the fold, one page-specific `<h1>`, and page titles/descriptions. Keep existing marketing layout and colors. Contact success and error messages use `aria-live="polite"` and retain form values after failure.

- [ ] **Step 4: Run tests and all viewport checks**

Run: `php artisan test tests/Feature/PublicViewsTest.php`

Expected: PASS.

Browser: verify `/`, `/portafolio`, `/contacto`, and `/cv` at 390×844, 768×1024, 1280×720, and 1440×900 with no horizontal overflow or console errors.

- [ ] **Step 5: Record the task checkpoint**

```bash
git add apps/laravel/resources/views/livewire/landing apps/laravel/resources/views/partials/landing-nav.blade.php apps/laravel/resources/views/partials/landing-footer.blade.php apps/laravel/tests/Feature/PublicViewsTest.php
git commit -m "fix: polish public kamo views"
```

### Task 6: Remove confirmed dead duplicate screens and run final gates

**Files:**
- Delete only if unreferenced: `apps/laravel/resources/views/livewire/clients/index.blade.php`
- Delete only if unreferenced: `apps/laravel/resources/views/livewire/services/index.blade.php`
- Delete only if unreferenced: `apps/laravel/resources/views/livewire/expenses/index.blade.php`
- Delete only if unreferenced: `apps/laravel/resources/views/livewire/quotes/index.blade.php`
- Delete only if unreferenced: `apps/laravel/resources/views/livewire/bills/index.blade.php`
- Delete only if unreferenced: `apps/laravel/resources/views/livewire/invoices/index.blade.php`
- Create: `docs/audits/2026-08-23-platform-remediation.md`

**Interfaces:**
- Consumes: all prior plans.
- Produces: one canonical screen per module and final evidence report.

- [ ] **Step 1: Prove duplicate views are unreferenced**

Run: `rg -n "livewire\.(clients|services|expenses|quotes|bills|invoices)\.index|Livewire\\(Clients|Services|Expenses|Quotes|Bills|Invoices)" apps/laravel --glob '!vendor/**' --glob '!storage/**'`

Expected: only class/view pairs with no route or test consumer; if any active reference exists, do not delete that pair.

- [ ] **Step 2: Delete only proven dead pairs and run route/view smoke tests**

Use `apply_patch` deletions for the unreferenced files. Run: `php artisan route:list --except-vendor`.

Expected: all intended routes present and no route references removed classes.

- [ ] **Step 3: Run full automated gates**

Run: `php artisan test`

Expected: exit 0, zero failures, zero premature process exits.

Run: `npm run test:js && npm run build`

Expected: both exit 0.

Run: `vendor/bin/pint --test app bootstrap config database routes tests`

Expected: touched files pass; formatting-only legacy findings are listed separately.

- [ ] **Step 4: Run container and browser gates and write audit report**

Run: `docker compose ps` and `docker exec kamo_app php -m`.

Expected: app/db/nginx healthy/running and `intl` present.

Browser: exercise login, dashboard, every workspace index, create/edit validation, isolated test-record deletion confirmation, quote conversion, payment, PDF, public routes, all target viewports, keyboard focus, and console logs. Record commands, test counts, remaining non-blocking findings, and screenshots in `docs/audits/2026-08-23-platform-remediation.md`.

- [ ] **Step 5: Record the final checkpoint**

```bash
git add apps/laravel docs/audits/2026-08-23-platform-remediation.md
git commit -m "fix: complete platform audit remediation"
```
