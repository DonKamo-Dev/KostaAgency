<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cuenta de Cobro {{ $document->doc_number }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #1f2430;
            background: #ffffff;
        }
        .wave-header {
            width: 100%;
            line-height: 0;
        }
        .wave-header img {
            width: 100%;
            height: 76px;
            display: block;
        }
        .page-content {
            padding: 16px 52px 245px 52px;
        }
        .logo-wrap {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo-wrap img {
            height: 36px;
            width: auto;
        }
        .meta-row {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
            font-size: 11px;
            color: #2b303a;
        }
        .title-block {
            text-align: center;
            margin-bottom: 28px;
        }
        .title-pill {
            display: inline-block;
            background: #42568b;
            color: #ffffff;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 7px 26px;
            border-radius: 18px;
            margin-bottom: 10px;
        }
        .doc-number {
            font-size: 13px;
            font-weight: bold;
            color: #1f2430;
            margin-bottom: 3px;
        }
        .client-name {
            font-size: 13px;
            font-weight: bold;
            color: #1f2430;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }
        .client-tax {
            font-size: 10px;
            color: #6b7280;
            margin-top: 2px;
        }
        .intro-text {
            font-size: 11px;
            color: #2b303a;
            margin-bottom: 12px;
        }
        .items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            table-layout: fixed;
        }
        .items-table th {
            background: #42568b;
            color: #ffffff;
            font-size: 10.5px;
            font-weight: bold;
            padding: 7px 12px;
            text-align: left;
        }
        .items-table th.col-first {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            text-align: center;
        }
        .items-table th.col-last {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            text-align: right;
        }
        .items-table td {
            padding: 9px 12px;
            font-size: 11px;
            color: #2b303a;
            vertical-align: top;
            word-wrap: break-word;
            line-height: 1.35;
        }
        .items-table tbody tr {
            page-break-inside: avoid;
        }
        .item-desc-sub {
            font-size: 9.5px;
            color: #6b7280;
            margin-top: 2px;
        }
        .bottom-section {
            position: absolute;
            left: 52px;
            right: 52px;
            bottom: 42px;
        }
        .total-divider {
            border-top: 1px solid #7b8290;
            margin-bottom: 8px;
        }
        .total-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
        }
        .total-label {
            text-align: right;
            font-size: 11.5px;
            font-weight: bold;
            color: #1f2430;
            padding-right: 28px;
        }
        .total-value {
            text-align: right;
            font-size: 11.5px;
            font-weight: bold;
            color: #1f2430;
            width: 110px;
        }
        .payment-details {
            font-size: 10.5px;
            line-height: 1.65;
            color: #2b303a;
        }
        .payment-details strong {
            color: #1f2430;
        }
        .custom-notes {
            margin-top: 8px;
            font-size: 9.5px;
            color: #555c68;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    @php
        $waveSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 95" preserveAspectRatio="none">'
            . '<path d="M0,0 L800,0 L800,66 Q400,104 0,66 Z" fill="#2d3b63"/>'
            . '<path d="M0,0 L800,0 L800,52 Q400,88 0,52 Z" fill="#42568b"/>'
            . '<path d="M0,0 L800,0 L800,32 Q360,70 0,26 Z" fill="#364778" opacity="0.65"/>'
            . '</svg>';
        $parsedDate = \Carbon\Carbon::parse($document->date)->locale('es');
        $formattedDate = ucfirst($parsedDate->translatedFormat('l j \d\e F \d\e Y'));
        $defaultMonth = ucfirst($parsedDate->translatedFormat('F'));
    @endphp

    <div class="wave-header">
        <img src="data:image/svg+xml;base64,{{ base64_encode($waveSvg) }}" alt="">
    </div>

    <div class="page-content">
        <div class="logo-wrap">
            @if(file_exists(public_path('img/WEB-Kosta-Black.svg')))
                <img src="data:image/svg+xml;base64,{{ base64_encode(file_get_contents(public_path('img/WEB-Kosta-Black.svg'))) }}" alt="Kosta Studio Films">
            @else
                <div style="font-size: 18px; font-weight: bold; color: #2d3b63; letter-spacing: 0.04em;">KOSTA</div>
                <div style="font-size: 8px; color: #555c68; text-transform: uppercase; letter-spacing: 0.16em;">Studio Films</div>
            @endif
        </div>

        <table class="meta-row">
            <tr>
                <td style="text-align: left;">{{ $formattedDate }}</td>
                <td style="text-align: right;">Cartagena, Colombia</td>
            </tr>
        </table>

        <div class="title-block">
            <div class="title-pill">CUENTA DE COBRO</div>
            <div class="doc-number">N° {{ $document->doc_number }}</div>
            <div class="client-name">{{ $document->client->name }}</div>
            @if(!empty($document->client->tax_id))
                <div class="client-tax">ID/NIT: {{ $document->client->tax_id }}</div>
            @endif
        </div>

        <div class="intro-text">
            A continuación, se detallan los conceptos correspondientes al servicio prestado:
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th class="col-first" style="width: 14%;">Cantidad</th>
                    <th style="width: 48%;">Descripción</th>
                    <th style="width: 18%; text-align: center;">Mes</th>
                    <th class="col-last" style="width: 20%;">Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($document->items as $item)
                    @php
                        $qty = (float) $item->quantity;
                        $displayQty = $qty == (int) $qty ? (int) $qty : $item->quantity;
                        $itemValue = (float) $item->subtotal > 0
                            ? (float) $item->subtotal
                            : ($qty * (float) $item->unit_price);
                    @endphp
                    <tr>
                        <td style="text-align: center;">{{ $displayQty }}</td>
                        <td>
                            <div>{{ $item->service_name }}</div>
                            @if(!empty($item->description))
                                <div class="item-desc-sub">{{ $item->description }}</div>
                            @endif
                        </td>
                        <td style="text-align: center;">{{ $defaultMonth }}</td>
                        <td style="text-align: right;">${{ number_format($itemValue, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="bottom-section">
        <div class="total-divider"></div>
        <table class="total-table">
            <tr>
                <td></td>
                <td class="total-label">Total</td>
                <td class="total-value">${{ number_format($document->total, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="payment-details">
            <div><strong>Pagar a nombre de:</strong> Kosta Studio Films</div>
            <div><strong>Número de cuenta bancaria:</strong> 67817145938 (Ahorros) · Nequi: 3158396238</div>
            <div><strong>Banco:</strong> Bancolombia / Nequi</div>
            <div><strong>Teléfono:</strong> 3113894136</div>
            <div><strong>Correo electrónico:</strong> yohanblaro18@gmail.com</div>
            <div><strong>Dirección de residencia:</strong> Cartagena, Bolívar, Colombia</div>
        </div>

        @if(!empty($document->notes))
            <div class="custom-notes">
                {!! nl2br(e($document->notes)) !!}
            </div>
        @endif
    </div>
</body>
</html>
