<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
* { font-family: 'DejaVu Sans', sans-serif; box-sizing: border-box; margin: 0; padding: 0; }
body { background: #fff; color: #111; font-size: 11px; line-height: 1.5; }

.top-bar { background: #E63946; height: 6px; }
.page    { padding: 28px 40px 24px; }

/* ── Header ── */
.logo-sub { font-size: 9px; color: #bbb; text-transform: uppercase; letter-spacing: 1px; margin-top: 3px; }
.ref-pill { background: #fff0f1; border: 1px solid #fecdd0; color: #E63946;
            font-weight: bold; font-size: 9px; padding: 3px 10px; border-radius: 20px; }
.divider  { height: 1px; background: #eee; margin: 12px 0; }

/* ── Titulo ── */
.prop-title { font-size: 20px; font-weight: bold; color: #111; margin-bottom: 2px; }
.prop-sub   { font-size: 11px; color: #999; }

/* ── Info grid ── */
.info-wrap { background: #fafafa; border: 1px solid #eee; border-radius: 8px;
             padding: 16px 20px; margin: 14px 0; }
.info-lbl  { font-size: 9px; font-weight: bold; text-transform: uppercase;
             letter-spacing: 0.9px; color: #E63946; padding: 7px 16px 7px 0; width: 100px; }
.info-val  { font-size: 13px; color: #222; font-weight: bold; padding: 7px 24px 7px 0; }

/* ── Resumen ── */
.exec-box   { background: #fafafa; border-left: 3px solid #E63946;
              padding: 10px 14px; border-radius: 0 6px 6px 0; margin: 14px 0; }
.exec-box p { font-size: 10.5px; color: #444; line-height: 1.65; }

/* ── Sección propuesta ── */
.sec-head { background: #111; color: #fff; padding: 7px 14px;
            border-radius: 6px 6px 0 0; }
.sec-head span { font-size: 8.5px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.2px; }
.sec-body { border: 1px solid #e5e5e5; border-top: none;
            border-radius: 0 0 6px 6px; padding: 12px 14px; margin-bottom: 14px; }

/* ── Tabla campañas ── */
.tbl-header td { font-size: 8px; color: #bbb; text-transform: uppercase;
                 letter-spacing: 0.5px; padding-bottom: 6px;
                 border-bottom: 1px solid #eee; }
.tbl-row td    { padding: 7px 0; border-bottom: 1px solid #f7f7f7; vertical-align: middle; }
.tbl-row:last-child td { border-bottom: none; }
.td-name   { font-size: 11px; font-weight: bold; color: #111; width: 42%; }
.td-budget { font-size: 11px; font-weight: bold; color: #E63946; width: 26%;
             font-family: 'DejaVu Sans', sans-serif; }
.td-center { font-size: 10px; color: #666; width: 16%; text-align: center; }

/* ── Beneficios ── */
.ben-head  { font-size: 8px; color: #bbb; text-transform: uppercase;
             letter-spacing: 0.5px; padding-top: 10px; padding-bottom: 6px;
             border-top: 1px solid #f0f0f0; margin-top: 4px; }
.ben-item  { font-size: 10.5px; color: #444; padding: 3px 0; line-height: 1.55; }
.ben-num   { color: #E63946; font-weight: bold; padding-right: 5px; }

/* ── Desglose financiero ── */
.fin-table    { width: 100%; border-collapse: collapse; }
.fin-table td { padding: 8px 14px; border-bottom: 1px solid #f2f2f2; }
.fin-lbl      { color: #555; font-size: 11px; }
.fin-amt      { text-align: right; font-weight: bold; color: #111; font-size: 11.5px;
                font-family: 'DejaVu Sans', sans-serif; }
.fin-sub-lbl  { color: #bbb; font-size: 9.5px; }
.fin-sub-amt  { text-align: right; color: #bbb; font-size: 9.5px;
                font-family: 'DejaVu Sans', sans-serif; }
.total-tr td  { background: #E63946; padding: 11px 14px; border-bottom: none; }
.total-lbl    { color: #fff; font-size: 11px; font-weight: bold;
                text-transform: uppercase; letter-spacing: 0.6px; }
.total-amt    { text-align: right; color: #fff; font-size: 22px; font-weight: bold;
                font-family: 'DejaVu Sans', sans-serif; }

/* ── Footer ── */
.footer-row td { font-size: 8px; color: #ccc; padding-top: 8px;
                 border-top: 1px solid #f0f0f0; }
</style>
</head>
<body>

@php
    $p          = $quote->ai_result['desglose_precios'];
    $ps         = $quote->ai_result['proposal_structure'] ?? null;
    $platforms = ['META' => 'Meta Ads', 'GOOGLE' => 'Google Ads', 'BOTH' => 'Google & Meta Ads'];
    $platformLabel = $platforms[$quote->platform] ?? 'Campañas Digitales';

    $destinationLabels = [
        'WEB'      => 'Sitio Web',
        'WHATSAPP' => 'Solo WhatsApp',
        'BOTH'     => 'Web + WhatsApp',
    ];
    $campaignLabels = [
        'AWARENESS'   => 'Reconocimiento de marca',
        'TRAFFIC'     => 'Tráfico web',
        'LEADS'       => 'Generación de leads',
        'CONVERSIONS' => 'Conversiones / Ventas',
        'ENGAGEMENT'  => 'Interacción',
        'BOTH'        => 'Web + WhatsApp',
    ];
@endphp

<div class="top-bar"></div>
<div class="page">

{{-- HEADER --}}
<table width="100%" style="border-collapse:collapse;"><tr valign="middle">
    <td>
        <div style="display:inline-flex;align-items:center;gap:6px;">
            <div style="width:24px;height:24px;background:#E63946;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                <span style="color:#fff;font-size:12px;font-weight:700;">K</span>
            </div>
            <div>
                <div style="font-size:14px;font-weight:700;color:#E63946;letter-spacing:-0.02em;line-height:1;">KAMO <span style="color:#111;">Agency</span></div>
            </div>
        </div>
        <div class="logo-sub">Propuesta Comercial &middot; {{ ['META'=>'Meta Ads','GOOGLE'=>'Google Ads','BOTH'=>'Google & Meta Ads'][$quote->platform] ?? 'Pauta Digital' }}</div>
    </td>
    <td align="right" valign="middle">
        <span class="ref-pill">Ref. #{{ str_pad($quote->id, 4, '0', STR_PAD_LEFT) }} &middot; {{ $quote->created_at->format('d/m/Y') }}</span>
    </td>
</tr></table>

<div class="divider"></div>

{{-- TÍTULO --}}
<div class="prop-title">{{ $quote->client_name }}</div>
<div class="prop-sub">{{ $quote->industry }}</div>

{{-- INFO CLAVE --}}
<div class="info-wrap">
<table style="width:100%;border-collapse:collapse;">
    <tr>
        <td class="info-lbl">Objetivo</td>
        <td class="info-val">{{ $campaignLabels[$quote->campaign_type] ?? $quote->campaign_type }}</td>
        <td class="info-lbl">Presupuesto</td>
        <td style="padding:7px 24px 7px 0;font-family:'DejaVu Sans',sans-serif;font-size:18px;font-weight:700;color:#E63946;letter-spacing:-0.3px;">${{ number_format($quote->budget_cop,0,',','.') }} <span style="font-size:11px;font-weight:normal;color:#aaa;">COP</span></td>
    </tr>
    <tr>
        <td class="info-lbl">Duracion</td>
        <td class="info-val">{{ $quote->duration_days }} dias</td>
        <td class="info-lbl">Plataforma</td>
        <td class="info-val">{{ $platformLabel }}</td>
    </tr>
    <tr>
        <td class="info-lbl">Destino</td>
        <td class="info-val">{{ $destinationLabels[$quote->destination] ?? $quote->destination }}</td>
        @if($quote->location)
        <td class="info-lbl">Ubicacion</td>
        <td class="info-val">{{ $quote->location }}</td>
        @else<td colspan="2"></td>@endif
    </tr>
    @if($quote->age_range)
    <tr>
        <td class="info-lbl">Audiencia</td>
        <td class="info-val">{{ $quote->age_range }} anos</td>
        <td colspan="2"></td>
    </tr>
    @endif
</table>
</div>

{{-- RESUMEN --}}
<div class="exec-box">
    <p>{{ $quote->ai_result['resumen_ejecutivo'] }}</p>
</div>

{{-- ESTRUCTURA DE PROPUESTA --}}
@if($ps && !empty($ps['campaigns']))
<div class="sec-head">
    <span>Estructura de inversion {{ $platformLabel }} &mdash; {{ $quote->duration_days }} dias</span>
</div>
<div class="sec-body">
    <table width="100%" style="border-collapse:collapse;">
        <tr class="tbl-header">
            <td class="td-name">Campaña</td>
            <td class="td-budget">Presupuesto</td>
            <td class="td-center">Campanas</td>
            <td class="td-center">Anuncios</td>
        </tr>
        @foreach($ps['campaigns'] as $camp)
        <tr class="tbl-row">
            <td class="td-name">{{ $camp['name'] }}</td>
            <td class="td-budget">${{ number_format($camp['budget'],0,',','.') }} COP</td>
            <td class="td-center">{{ $camp['campaigns_count'] }} {{ $camp['campaigns_count'] == 1 ? 'campaña' : 'campañas' }}</td>
            <td class="td-center">{{ $camp['ads_count'] }} anuncios</td>
        </tr>
        @endforeach
    </table>

    @if(!empty($ps['benefits']))
    <div class="ben-head">Beneficios / Puntos clave</div>
    @foreach($ps['benefits'] as $i => $benefit)
    <div class="ben-item"><span class="ben-num">{{ $i + 1 }}.</span>{{ $benefit }}</div>
    @endforeach
    @endif
</div>
@endif

{{-- DESGLOSE FINANCIERO --}}
<div style="border:1px solid #e5e5e5;border-radius:8px;overflow:hidden;">
    <div style="background:#111;padding:8px 14px;">
        <span style="color:#fff;font-size:8.5px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;">Desglose de inversion</span>
    </div>
    <table class="fin-table">
        <tr>
            <td class="fin-lbl">Presupuesto en pauta</td>
            <td class="fin-amt">${{ number_format($p['presupuesto_pauta_cop'],0,',','.') }} COP</td>
        </tr>
        <tr>
            <td class="fin-lbl">Honorarios gestion de campana</td>
            <td class="fin-amt">${{ number_format($p['honorarios_gestion_cop'],0,',','.') }} COP</td>
        </tr>
        <tr>
            <td class="fin-lbl">Honorarios produccion de creativos</td>
            <td class="fin-amt">${{ number_format($p['honorarios_creativos_cop'],0,',','.') }} COP</td>
        </tr>
        <tr>
            <td class="fin-sub-lbl">Total honorarios agencia</td>
            <td class="fin-sub-amt">${{ number_format($p['total_agencia_cop'],0,',','.') }} COP</td>
        </tr>
        <tr class="total-tr">
            <td class="total-lbl">Total inversion</td>
            <td class="total-amt">${{ number_format($p['total_inversion_cop'],0,',','.') }} COP</td>
        </tr>
    </table>
</div>

{{-- FOOTER --}}
<table width="100%" style="border-collapse:collapse;margin-top:10px;">
    <tr class="footer-row">
        <td>Kamo Agency &middot; kamo.agency &middot; Propuesta valida 30 dias</td>
        <td align="right">{{ now()->format('d/m/Y') }}</td>
    </tr>
</table>

</div>{{-- end .page --}}
</body>
</html>
