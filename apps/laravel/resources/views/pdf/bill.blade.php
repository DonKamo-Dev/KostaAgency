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
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 28px 36px 250px 36px;
            color: #333;
            background: #fff;
        }
        .clear { clear: both; }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            table-layout: fixed;
        }
        .table th {
            text-align: left;
            padding: 8px 10px;
            background: #f1f3f5;
            color: #495057;
            font-size: 10px;
            text-transform: uppercase;
            border-bottom: 2px solid #dee2e6;
            letter-spacing: 0.05em;
        }
        .table td {
            padding: 8px 10px;
            border-bottom: 1px solid #dee2e6;
            font-size: 12px;
            word-wrap: break-word;
            vertical-align: middle;
            line-height: 1.3;
        }
        .table td.desc-cell {
            vertical-align: top;
        }
        .section-box {
            margin-bottom: 12px;
            border-left: 4px solid #E63946;
            padding: 10px 16px;
            background: #fcfcfc;
        }
        .section-label {
            font-size: 10px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 6px;
            font-weight: bold;
        }
        .debe-a-banner {
            text-align: center;
            margin: 14px 0 10px 0;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 0.14em;
            color: #111111;
            text-transform: uppercase;
        }
        .concepto-title {
            font-size: 11px;
            font-weight: bold;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin: 14px 0 8px 0;
        }
        .footer {
            position: absolute;
            bottom: 16px;
            left: 36px;
            right: 36px;
            text-align: center;
            font-size: 9px;
            color: #aaa;
            border-top: 1px solid #eee;
            padding-top: 6px;
        }
        .document-summary {
            position: absolute;
            right: 36px;
            bottom: 58px;
            left: 36px;
        }
    </style>
</head>
<body>
    <table style="width: 100%; margin-bottom: 14px; border-bottom: 1px solid #eee; padding-bottom: 12px;">
        <tr>
            <td style="width: 50%; vertical-align: middle;">
                @if(file_exists(public_path('img/WEB-Kosta-Black.svg')))
                    <img src="data:image/svg+xml;base64,{{ base64_encode(file_get_contents(public_path('img/WEB-Kosta-Black.svg'))) }}" style="height:32px; width:auto;" alt="Kosta">
                @else
                    <div style="font-size:18px;font-weight:700;color:#111111;letter-spacing:-0.02em;line-height:1;">KOSTA</div>
                    <div style="font-size:8px;color:#666;text-transform:uppercase;letter-spacing:0.18em;font-weight:600;">Studio Films</div>
                @endif
            </td>
            <td style="width: 50%; text-align: right; vertical-align: middle;">
                <div style="font-size: 10px; color: #888; text-transform: uppercase; letter-spacing: 0.12em; margin-bottom: 2px;">CUENTA DE COBRO</div>
                <div style="font-size: 24px; font-weight: bold; color: #111111; line-height: 1.1;">N° {{ $document->doc_number }}</div>
                <div style="font-size: 11px; margin-top: 4px; color: #555;">
                    <span style="color: #888;">Fecha:</span>
                    <span style="font-weight: bold; margin-left: 4px;">{{ \Carbon\Carbon::parse($document->date)->format('d/m/Y') }}</span>
                    @if($document->due_date)
                        <span style="color: #888; margin-left: 10px;">Vence:</span>
                        <span style="font-weight: bold; margin-left: 4px;">{{ \Carbon\Carbon::parse($document->due_date)->format('d/m/Y') }}</span>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    {{-- Quién debe (Cliente) --}}
    <div class="section-box">
        <div class="section-label">Datos del Cliente</div>
        <div style="font-size: 17px; font-weight: bold; color: #111; margin-bottom: 8px;">{{ $document->client->name }}</div>
        <table style="width: 100%; font-size: 11.5px; color: #555;">
            <tr>
                <td style="width: 50%; padding-bottom: 4px;">
                    <span style="color: #999;">ID/NIT:</span> {{ $document->client->tax_id ?? '—' }}
                </td>
                <td style="width: 50%; padding-bottom: 4px;">
                    <span style="color: #999;">Teléfono:</span> {{ $document->client->phone ?? '—' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span style="color: #999;">Email:</span> {{ $document->client->email }}
                </td>
                <td>
                    <span style="color: #999;">Dirección:</span> {{ $document->client->address ?? '—' }}
                </td>
            </tr>
        </table>
    </div>

    {{-- A quién se le debe (Emisor) --}}
    <div style="text-align: center; margin: 16px 0 18px 0;">
        <div class="debe-a-banner" style="margin: 0 0 6px 0;">DEBE A</div>
        <div style="font-size: 16px; font-weight: bold; color: #111111; margin-bottom: 2px;">Yohan Camilo Blanco Rojas</div>
        <div style="font-size: 12px; color: #555555;">CC. 1002196396</div>
    </div>

    <div class="clear"></div>

    <div class="concepto-title">Por concepto de:</div>

    <table class="table">
        <thead>
            <tr>
                <th style="width: 30px; text-align: center;">#</th>
                <th>Servicio / Descripción</th>
                <th style="width: 55px; text-align: center;">Cant.</th>
                <th style="width: 95px; text-align: right;">P. Unitario</th>
                <th style="width: 95px; text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($document->items as $index => $item)
                @php
                    $itemSubtotal = (float) $item->subtotal > 0
                        ? (float) $item->subtotal
                        : ((float) $item->quantity * (float) $item->unit_price);
                @endphp
                <tr>
                    <td style="text-align: center; color: #888;">{{ $index + 1 }}</td>
                    <td class="desc-cell">
                        <div style="font-weight: bold;">{{ $item->service_name }}</div>
                        @if($item->description)
                            <div style="font-size: 10px; color: #666; margin-top: 3px; line-height: 1.3;">{{ $item->description }}</div>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">${{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td style="text-align: right; font-weight: bold;">${{ number_format($itemSubtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="document-summary">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 60%; vertical-align: top; padding-right: 20px;">
                    @if($document->notes)
                        @php
                            $bancolombiaLogo = file_exists(public_path('img/logos/bancolombia.svg'))
                                ? 'data:image/svg+xml;base64,' . base64_encode(file_get_contents(public_path('img/logos/bancolombia.svg')))
                                : null;
                            $nequiLogo = file_exists(public_path('img/logos/nequi.svg'))
                                ? 'data:image/svg+xml;base64,' . base64_encode(file_get_contents(public_path('img/logos/nequi.svg')))
                                : null;
                            $noteLines = preg_split('/\r\n|\r|\n/', trim($document->notes));
                        @endphp
                        <div style="background: #fcfcfc; border-left: 3px solid #E63946; padding: 10px 12px; border-radius: 0 6px 6px 0;">
                            <div style="font-weight: bold; color: #333; margin-bottom: 8px; text-transform: uppercase; font-size: 9px; letter-spacing: 0.05em; border-bottom: 1px solid #eee; padding-bottom: 4px;">Términos y Formas de Pago</div>
                            <div style="font-size: 10px; color: #555; line-height: 1.4;">
                                @foreach($noteLines as $line)
                                    @php
                                        $trimmed = trim($line);
                                        $isBancolombia = stripos($trimmed, 'bancolombia') !== false;
                                        $isNequi = stripos($trimmed, 'nequi') !== false;
                                        preg_match('/[\d\-]{6,}/', $trimmed, $matches);
                                        $accountNumber = $matches[0] ?? null;
                                    @endphp
                                    @if($isBancolombia && $bancolombiaLogo)
                                        <table style="border-collapse: collapse; margin-top: 6px;">
                                            <tr>
                                                <td style="vertical-align: middle; padding-right: 10px;">
                                                    <img src="{{ $bancolombiaLogo }}" style="height: 18px; width: auto;" alt="Bancolombia">
                                                </td>
                                                <td style="vertical-align: middle; font-size: 14px; font-weight: bold; color: #111111; letter-spacing: 0.03em;">
                                                    {{ $accountNumber ?? $trimmed }}
                                                </td>
                                            </tr>
                                        </table>
                                    @elseif($isNequi && $nequiLogo)
                                        <table style="border-collapse: collapse; margin-top: 6px;">
                                            <tr>
                                                <td style="vertical-align: middle; padding-right: 10px;">
                                                    <img src="{{ $nequiLogo }}" style="height: 17px; width: auto;" alt="Nequi">
                                                </td>
                                                <td style="vertical-align: middle; font-size: 14px; font-weight: bold; color: #111111; letter-spacing: 0.03em;">
                                                    {{ $accountNumber ?? $trimmed }}
                                                </td>
                                            </tr>
                                        </table>
                                    @elseif($trimmed !== '')
                                        <div style="margin-bottom: 2px;">{{ $trimmed }}</div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </td>
                <td style="width: 40%; vertical-align: top;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 8px 0; font-size: 13px; color: #666; border-bottom: 1px solid #f1f3f5;">Subtotal:</td>
                            <td style="padding: 8px 0; font-size: 13px; font-weight: bold; color: #333; text-align: right; border-bottom: 1px solid #f1f3f5;">${{ number_format($document->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @if($document->tax > 0)
                            <tr>
                                <td style="padding: 8px 0; font-size: 13px; color: #666; border-bottom: 1px solid #f1f3f5;">IVA (19%):</td>
                                <td style="padding: 8px 0; font-size: 13px; font-weight: bold; color: #333; text-align: right; border-bottom: 1px solid #f1f3f5;">${{ number_format($document->tax, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td style="padding: 10px 5px; font-weight: bold; color: #111; font-size: 13px; border-top: 2px solid #111111; background: #f8fafc;">TOTAL COP:</td>
                            <td style="padding: 10px 5px; font-size: 20px; font-weight: bold; color: #111111; text-align: right; border-top: 2px solid #111111; background: #f8fafc;">${{ number_format($document->total, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Generado automáticamente por Kosta Studio Films - Documento con validez comercial.
    </div>
</body>
</html>
