# Kamo Platform Audit & Remediation Design

## Objective

Restore Kamo Platform to a reliable, secure, consistent state while preserving its current visual identity: deep black surfaces, coral-red accents, subtle glass effects, Space Grotesk headings, Inter body text, desktop sidebar, and mobile bottom navigation.

The work covers the public website, authentication, the authenticated agency workspace, document/PDF workflows, Meta Ads tooling, accessibility, responsive behavior, and the local Docker runtime.

## Confirmed Baseline

- The Laravel/Vite application builds successfully.
- The application runs through Docker at `http://localhost:8000`.
- The public landing page renders at desktop and mobile widths without horizontal overflow.
- The current automated suite aborts in `MetaAds/PdfTest` because Dompdf exhausts the 128 MB PHP memory limit while decoding the PNG logo.
- `thinking-orb` sends arbitrary display sizes to an API that only accepts its canonical preset sizes, causing `Cannot read properties of undefined (reading 'count')` in the browser.
- `/entrar-kamo` attempts to authenticate user ID `1`, while the current database contains users `34` and `35`, so the route redirects back to login.
- Docker lacks the PHP `intl` extension required by Laravel number formatting utilities.
- Resource mutations use unscoped route model binding in several controllers, allowing records outside the current company to be addressed.
- The registration flow still uses the default English Breeze presentation and does not match the rest of Kamo.
- Static review found missing or inconsistent focus states, semantic controls, reduced-motion handling, image dimensions, labels, copy, and destructive-action safeguards.
- The codebase contains both controller-backed Blade screens and older Livewire versions for several modules, creating duplicated behavior and maintenance ambiguity.

## Product & Interface Direction

### Human and task

The primary user is an agency operator who moves rapidly between clients, services, quotes, bills, invoices, payments, expenses, case studies, and campaign proposals. The interface must support quick financial and operational decisions without visual noise or uncertainty about company scope.

### Domain vocabulary

- Agency workspace
- Client portfolio
- Commercial documents
- Cash collection
- Campaign proposals
- Delivery pipeline
- Company tenancy

### Color world

- Deep studio black for the workspace canvas
- Charcoal equipment surfaces for cards and controls
- Coral red for Kamo identity and primary action
- Muted silver for secondary information
- Warm white for primary text
- Restrained green, amber, and red for financial status

### Signature

Kamo's signature remains the coral-red operational pulse: coral accents in navigation and primary actions, paired with restrained orbital progress indicators. The orbital indicator will be made robust and accessible rather than removed.

### Defaults explicitly rejected

- Generic white SaaS dashboard → retain Kamo's dark studio workspace.
- Full visual redesign → improve the established token and component system in place.
- Decorative loader everywhere → show scoped feedback only for actions that actually take time.

## Architectural Approach

Use a staged, in-place remediation rather than a rewrite:

1. Establish security and tenancy invariants.
2. Repair global runtime failures shared by every screen.
3. Correct document and financial workflows.
4. Consolidate active UI patterns without changing the information architecture.
5. Align public and authentication views with the same design system.
6. Complete responsive, accessibility, and reduced-motion remediation.
7. Verify the full stack with automated and browser-based regression checks.

Existing URLs and core workflows remain stable unless a route is explicitly unsafe or development-only.

## Security and Tenancy

### Current-company invariant

Every authenticated read or mutation must operate on the authenticated user's current company. A missing company is an explicit error state; production code must never fall back to the first company or a hard-coded ID.

### Scoped resources

Clients, services, expenses, documents, payments, case studies, and Meta Ads quotes must be queried through current-company scope before being viewed, edited, converted, paid, downloaded, or deleted. Cross-company identifiers return `404` and produce no side effects.

Related foreign keys must also belong to the current company. For example, a quote cannot reference another company's client or service.

### Unsafe development routes

- `/entrar-kamo` must not use a fixed database ID. It will be disabled outside the local environment and resolve the configured local development user safely when local access is enabled.
- `/react-test` will be restricted to local development or removed from production routing.

### Destructive actions

Deletion and cancellation remain explicit operations. The UI must require confirmation or provide a safe undo pattern, and the server must authorize the target independently of the UI.

## Runtime and Infrastructure

### Thinking orb

The custom element will translate requested display size into a valid library preset (`24` or `64`) while preserving CSS dimensions. It will render a static representative frame when `prefers-reduced-motion: reduce` is active, clean up observers/listeners, expose meaningful status text, and avoid duplicate global request hooks.

Global navigation and request feedback will be delayed so fast actions do not flash a loader. Loaders must always be dismissed on success, validation failure, network failure, and Livewire navigation.

### Docker PHP runtime

Install and enable `intl` in the application image. The rebuilt container must report the extension and allow Laravel database inspection/number formatting without runtime exceptions.

### PDFs

Optimize or replace the oversized PNG logo used by Dompdf, avoiding expensive alpha decoding. PDF generation must succeed under the normal 128 MB limit; raising the limit is not the primary fix. Both general document PDFs and Meta Ads PDFs must retain branding and produce valid `%PDF` responses.

## Functional Modules

### Authentication

- Login, registration, password reset, email verification, and profile screens use consistent Spanish copy and Kamo styling.
- Authentication forms retain browser autocomplete, clear labels, inline validation, visible focus, and keyboard-accessible password visibility controls.
- Registration produces a valid user/company relationship required by authenticated middleware.
- Intended redirects and email-verification behavior remain covered by tests.

### Dashboard

- KPI values are company-scoped and use consistent currency/number formatting.
- Charts initialize once, update safely after navigation, and handle empty datasets.
- Quick actions remain visible and keyboard accessible.
- Empty and zero states explain what action creates data.

### Clients, services, and expenses

- Keep existing routes and controller-backed screens as the canonical implementation.
- Search, pagination, creation, editing, and deletion preserve URL or component state where practical.
- Forms expose labels, meaningful names, correct input types, inline errors, loading states, and unsaved-change protection for modals.
- Older unused parallel implementations will be removed only after confirming they have no active route or test dependency.

### Quotes, bills, and invoices

- Server-side totals are recalculated from quantity and unit price; client-submitted subtotal values are never trusted.
- Document type is enforced for every route: invoice actions cannot target quotes or bills, and vice versa.
- Document numbering avoids collisions under concurrent requests.
- Payments cannot be zero, negative, cross-company, or greater than the outstanding balance unless overpayment is explicitly supported; this design does not add overpayment support.
- Conversions and payment registration are transactional.
- PDF access is company-scoped.

### Meta Ads

- Wizard and history records are company-scoped.
- View/edit routes enforce ownership and mode.
- Generation failures show actionable errors and preserve user input.
- Saved results and PDFs remain available after navigation.
- Existing AI prompt behavior is preserved except where validation or error handling is required.

### Public website, portfolio, contact, and CV

- Preserve current layout, typography, palette, and marketing structure.
- Repair broken or placeholder links, including incomplete WhatsApp targets.
- Make the contact flow validate clearly and expose a reliable success/error state.
- Add explicit image dimensions and lazy loading where applicable.
- Replace click-only non-semantic containers with buttons or links.
- Keep headings hierarchical and provide meaningful page titles and metadata.

## Interface System

### Tokens and depth

Use the existing black/charcoal/coral token family. Depth remains primarily surface color shifts plus low-contrast borders; no new dramatic shadows or gradients are introduced. Inputs remain visually inset relative to their containing surface.

### Spacing and shape

Normalize layout spacing to a 4 px base unit. Preserve the established friendly radius scale, but use smaller radii for controls and larger radii for cards/modals consistently.

### Typography and data

Space Grotesk remains the display face and Inter the interface face. Financial columns use tabular numerals. Four text-emphasis levels are used consistently for primary content, support text, metadata, and disabled/placeholder text.

### Interaction states

Every interactive element must have default, hover, active, focus-visible, and disabled states. Asynchronous sections must cover loading, empty, success, and error states. Buttons perform actions; links navigate.

### Responsive behavior

Validate at minimum:

- 390 × 844 mobile
- 768 × 1024 tablet
- 1280 × 720 desktop
- 1440 × 900 desktop

Tables may become horizontally contained data regions or purpose-built mobile rows, but the document body must not overflow horizontally. Modals must fit within safe viewport bounds and prevent background scroll.

### Accessibility

- Visible `:focus-visible` treatment for all controls.
- Semantic headings, buttons, links, forms, and tables.
- Labels or accessible names for every control and icon-only button.
- Keyboard alternatives for all pointer interactions.
- `aria-live="polite"` for relevant asynchronous feedback.
- Reduced-motion support for animations and loaders.
- Touch targets suitable for mobile use.
- No disabled zoom or paste prevention.

## Error Handling

- Validation failures return structured `422` responses and render next to the relevant fields.
- Unauthorized cross-company resources return `404` to avoid leaking existence.
- Missing current-company context returns a clear recovery message rather than silently selecting another company.
- Network and server failures dismiss loading states and show an actionable retry message.
- PDF errors are logged with document identifiers while users receive a safe failure response.
- AI service failures preserve entered form data and allow retry.

## Testing Strategy

All behavior changes follow red-green-refactor.

### Feature and unit tests

- Current-company resolution and missing-company behavior.
- Cross-company access blocked for every mutable resource family.
- Foreign keys constrained to the active company.
- Document type enforcement.
- Server-side subtotal/total calculation.
- Payment balance constraints and transactional updates.
- Local-only development routes.
- Auth flows and company provisioning.
- PDF generation below the normal memory limit.
- Meta Ads ownership, history, generation failure, and PDF response.

### Frontend regression checks

- Thinking-orb accepts every size used by Blade without console errors.
- Reduced-motion produces a non-animated status representation.
- Global loaders dismiss on success and failure.
- Build completes without errors.

### Browser verification

- Public routes at all target viewports.
- Authentication screens and validation states.
- Dashboard and each authenticated module.
- CRUD create/edit/delete confirmation paths using isolated test records.
- Quote creation, conversion, invoice payment, expense entry, and PDF download.
- Console errors, horizontal overflow, focus order, keyboard use, and loading/error/empty states.

### Quality gates

- `php artisan test` exits successfully without process crashes.
- `npm run build` exits successfully.
- Laravel Pint passes for files touched by the remediation; broad formatting-only churn is kept separate from behavior changes.
- Docker container health is green and `intl` is loaded.
- No browser console errors remain on audited routes.

## Delivery Sequence

1. Add regression coverage for tenancy, unsafe routes, orb sizing, and PDF memory failure.
2. Repair current-company resolution and scope all resources.
3. Fix development routes and authentication/company provisioning.
4. Fix orb runtime and shared layouts.
5. Fix Docker `intl` and PDF assets.
6. Correct financial/document invariants.
7. Consolidate active CRUD views and interaction states.
8. Align authentication and public views with Kamo's design system.
9. Complete accessibility and responsive remediation.
10. Run automated, container, and browser verification and publish the final audit report.

## Non-Goals

- Rebranding Kamo.
- Replacing Laravel, Livewire, Blade, Alpine, React, or Inertia wholesale.
- Changing the primary navigation model.
- Adding billing subscriptions, accounting integrations, or new marketing features.
- Supporting invoice overpayments.
- Performing an unrelated full-codebase reformat.

## Acceptance Criteria

- The visual identity and information architecture remain recognizably Kamo.
- Authenticated users can access the workspace with a valid company context.
- No user can read or mutate another company's scoped records by changing an ID.
- Active routes no longer expose hard-coded user access or production demos.
- Public and authenticated audited routes produce no JavaScript console errors.
- All core CRUD and document workflows complete with clear loading, success, validation, and error states.
- PDFs generate successfully within the standard runtime memory limit.
- Docker includes the required PHP extensions.
- Public, auth, and application views work at the four target viewports without document-level horizontal overflow.
- Keyboard navigation, focus visibility, labels, semantic controls, and reduced motion meet the stated accessibility requirements.
- The full automated suite and frontend production build pass.

