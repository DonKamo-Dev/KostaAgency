# Meta Ads AI Wizard — Design Spec
**Date:** 2026-05-20  
**Status:** Approved  
**Stack:** Laravel + Livewire + Anthropic PHP SDK + DomPDF

---

## Overview

A new sub-section under "Servicios" in the sidebar that provides an AI-powered Meta Ads campaign quote generator. The user answers 5 guided questions; Claude generates a full campaign strategy following real Meta Ads structure (Campaign → Ad Sets → Ads). The result is saved to the database and exportable as a professional PDF.

---

## Navigation

- New sidebar sub-link: **"✦ IA Meta Ads"** under "Servicios" (same pattern as Casos de Estudio sub-links)
- Route: `GET /servicios/meta-ads-ia` → `MetaAdsWizard` Livewire component (full page)
- Route: `GET /servicios/meta-ads-ia/historial` → `MetaAdsHistory` Livewire component
- Named routes: `meta-ads.wizard`, `meta-ads.history`

---

## Database

### Table: `ai_meta_quotes`

| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `company_id` | bigint FK | |
| `client_name` | string | |
| `industry` | string nullable | |
| `budget_cop` | integer | Total budget in COP |
| `duration_days` | integer | 15 / 30 / 45 / 60 |
| `daily_budget_cop` | integer | Calculated: budget / duration |
| `age_range` | string | e.g. "25–34" |
| `location` | string | Free text, e.g. "Cartagena, Colombia" |
| `interests` | json | Array of selected interest chips |
| `campaign_type` | string | AWARENESS / LEADS / CONVERSIONS / TRAFFIC / ENGAGEMENT |
| `destination` | string | WEB / WHATSAPP / BOTH |
| `whatsapp_number` | string nullable | Only if destination includes WhatsApp |
| `ai_result` | json | Full Claude response (see schema below) |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

## Wizard — 5 Steps

Layout: Left sidebar listing all 5 steps with status indicators (pending / active / completed). Right area shows current step content. Validation occurs per step before advancing.

### Step 1 — Cliente
- **client_name**: text input, required
- **industry**: single-select chips — Restaurante · Moda · Salud · Inmobiliaria · Educación · Tecnología · Otro

### Step 2 — Presupuesto
- **budget_cop**: numeric input, minimum 100,000 COP, formatted with thousand separators
- **duration_days**: single-select chips — 15 días · 30 días · 45 días · 60 días
- Display: auto-calculated "Equivale a $X.XXX/día" shown below inputs

### Step 3 — Audiencia
- **age_range**: single-select chips — 18–24 · 25–34 · 35–44 · 45+
- **location**: text input, free form (e.g. "Cartagena, Colombia")
- **interests**: multi-select chips — Emprendimiento · Moda · Salud · Tecnología · Hogar · Gastronomía · Otro

### Step 4 — Tipo de campaña
- **campaign_type**: single-select chips:
  - `AWARENESS` → Reconocimiento de marca
  - `TRAFFIC` → Tráfico web
  - `LEADS` → Generación de leads
  - `CONVERSIONS` → Conversión / Ventas
  - `ENGAGEMENT` → Interacción

### Step 5 — Destino
- **destination**: single-select chips — Solo Web · Solo WhatsApp · Web + WhatsApp
- **whatsapp_number**: text input, shown only if destination includes WhatsApp

**After step 5:** "✦ Generar estrategia con IA" button activates. During generation, a skeleton loader replaces the result area.

---

## AI Integration — Claude API

### Package
`anthropic/anthropic-sdk-php` (official Anthropic PHP SDK)

### Model
`claude-sonnet-4-6` — best balance of quality and cost for structured JSON generation

### Prompt caching
System prompt marked with cache_control to avoid re-sending Meta Ads context on every call.

### System prompt (cached)
```
You are a Meta Ads strategy expert for a Colombian digital agency called Kamo Agency.
You generate professional campaign strategies following Meta Ads real structure:
Campaign → Ad Sets → Ads.

Rules:
- All monetary values in COP (Colombian Pesos)
- Use real Meta Ads terminology (placements, objectives, optimization events, CTAs)
- Budget estimates must be realistic for the Colombian market
- Always structure campaigns with TOFU/MOFU/BOFU phases when budget allows
- Minimum recommended daily budget per ad set: 5,000 COP
- Agency fees: gestión = 30–40% of pauta budget, creativos = 20–30% of pauta budget
- Return ONLY valid JSON matching the exact schema provided. No markdown, no explanation.
```

### User message (not cached)
Constructed from wizard answers:
```
Generate a Meta Ads campaign strategy for:
- Client: {client_name} ({industry})
- Total budget: ${budget_cop} COP over {duration_days} days (${daily_budget_cop} COP/day)
- Target audience: {age_range}, {location}, interests: {interests}
- Campaign objective: {campaign_type}
- Destination: {destination} {whatsapp_number}

Return JSON matching this exact schema: { ... }
```

### Response JSON schema

```json
{
  "resumen_ejecutivo": "string",
  "estructura_campana": {
    "objetivo": "string (Meta campaign objective enum)",
    "nombre_campana": "string",
    "ad_sets": [
      {
        "nombre": "string",
        "audiencia_descripcion": "string",
        "placements": ["string"],
        "presupuesto_diario_cop": "integer",
        "optimizacion": "string (Meta optimization goal)",
        "duracion_dias": "integer"
      }
    ]
  },
  "creativos": {
    "formatos": ["string"],
    "copies_sugeridos": ["string"],
    "call_to_action": "string (Meta CTA enum)",
    "recomendaciones": "string"
  },
  "metricas_estimadas": {
    "alcance_diario": "string",
    "cpm_estimado_cop": "string",
    "ctr_objetivo": "string",
    "cpc_estimado_cop": "string",
    "frecuencia_sugerida": "string"
  },
  "fases": [
    {
      "fase": "TOFU | MOFU | BOFU",
      "dias": "string",
      "objetivo": "string",
      "presupuesto_pct": "integer"
    }
  ],
  "recomendaciones_pixel": ["string"],
  "desglose_precios": {
    "presupuesto_pauta_cop": "integer",
    "honorarios_gestion_cop": "integer",
    "honorarios_creativos_cop": "integer",
    "total_agencia_cop": "integer",
    "total_inversion_cop": "integer"
  }
}
```

---

## Result View

After generation, the wizard area is replaced by the quote result. Style: Kamo dark theme, consistent with rest of platform.

**Layout:**
- **Header card**: client name, campaign type badge, "IA Generado" badge, date, two action buttons: "Exportar PDF" and "Nueva cotización"
- **Metric chips row**: budget / duration / daily rate / campaign objective / destination
- **4 content cards** (2×2 grid):
  1. Estructura de campaña (ad sets list with placements and daily budget)
  2. Creativos (formats, copies, CTA)
  3. Métricas estimadas (alcance, CPM, CTR, CPC, frecuencia)
  4. Fases TOFU/MOFU/BOFU (timeline bars with % budget)
- **Pixel recommendations** (collapsible list)
- **Pricing table** (full width, pinned bottom):
  - Presupuesto pauta | Gestión | Creativos | **Total agencia** | **Total inversión**

---

## History View (`/servicios/meta-ads-ia/historial`)

Table of saved quotes with columns: Cliente · Tipo campaña · Presupuesto · Duración · Fecha · Acciones (Ver / Exportar PDF / Eliminar).

Linked from a "Ver historial" button on the wizard page header.

---

## PDF Export

**Package:** `barryvdh/laravel-dompdf`  
**Trigger:** "Exportar PDF" button → `GET /servicios/meta-ads-ia/{id}/pdf`  
**Theme:** White background, professional. Kamo logo top-left. Red accent (#E63946) for headings and totals.

**3-page layout:**
- **Página 1:** Portada — Kamo logo, "Propuesta Meta Ads", client name, date, resumen ejecutivo
- **Página 2:** Estructura de campaña + ad sets table + creativos + fases TOFU/MOFU/BOFU
- **Página 3:** Métricas estimadas + recomendaciones pixel + pricing table with signature line

---

## Livewire Components

### `App\Livewire\MetaAds\Wizard`
Properties: `$step` (1–5), `$clientName`, `$industry`, `$budgetCop`, `$durationDays`, `$ageRange`, `$location`, `$interests[]`, `$campaignType`, `$destination`, `$whatsappNumber`, `$generating`, `$quoteId`, `$result`

Key methods: `nextStep()`, `prevStep()`, `generate()` (calls Claude synchronously — set `wire:loading` spinner + disable button during call; PHP `set_time_limit(120)` to avoid timeout; saves to DB on success), `exportPdf($id)`

### `App\Livewire\MetaAds\History`
Standard paginated table with search, same CRUD patterns as existing modules.

---

## New Files

```
app/
  Livewire/MetaAds/Wizard.php
  Livewire/MetaAds/History.php
  Models/AiMetaQuote.php
  Services/ClaudeMetaAdsService.php   ← wraps Anthropic SDK call

resources/views/
  livewire/meta-ads/wizard.blade.php
  livewire/meta-ads/history.blade.php
  pdf/meta-ads-quote.blade.php        ← DomPDF template

database/migrations/
  xxxx_create_ai_meta_quotes_table.php

routes/web.php                        ← 2 new routes + PDF route
resources/views/components/layouts/app.blade.php  ← sidebar sub-link
```

---

## Packages to Install

```bash
composer require anthropic/anthropic-sdk-php
composer require barryvdh/laravel-dompdf
```

`.env` addition:
```
ANTHROPIC_API_KEY=sk-ant-...
```

---

## Out of Scope

- A/B testing management
- Real-time Meta Ads API integration (read actual campaign performance)
- Multi-language support
- Quote editing after generation (regenerate instead)
