<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotización {{ $document->doc_number }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 28px 36px 50px 36px;
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
    </style>
</head>
<body>
    <table style="width: 100%; margin-bottom: 16px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                @if($document->type !== 'quote')
                    <div style="margin-bottom: 10px;">
                        <div style="display:inline-flex;align-items:center;gap:8px;">
                            <div style="width:32px;height:32px;background:#111111;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                <span style="color:#fff;font-size:16px;font-weight:700;">K</span>
                            </div>
                            <div>
                                <div style="font-size:18px;font-weight:700;color:#111111;letter-spacing:-0.02em;line-height:1;">KOSTA</div>
                                <div style="font-size:8px;color:#666;text-transform:uppercase;letter-spacing:0.18em;font-weight:600;">Studio Films</div>
                            </div>
                        </div>
                    </div>
                @endif
                <div style="font-size: 11px; color: #666; line-height: 1.4;">
                    <div style="font-weight: bold; color: #111; font-size: 13px; margin-bottom: 4px;">Kosta Studio Films</div>
                    yohanblaro18@gmail.com<br>
                    3113894136<br>
                    Cartagena, Bolivar
                </div>
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <div style="margin-bottom: 15px;">
                    <div style="font-size: 10px; color: #888; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 4px;">{{ ['quote' => 'Cotización No.', 'invoice' => 'Factura No.', 'bill' => 'Cuenta de Cobro No.'][$document->type] ?? 'Documento No.' }}</div>
                    <div style="font-size: 28px; font-weight: bold; color: #111111;">{{ $document->doc_number }}</div>
                </div>
                
                <div style="font-size: 12px;">
                    <span style="color: #888;">Fecha:</span>
                    <span style="font-weight: bold; margin-left: 5px;">{{ \Carbon\Carbon::parse($document->date)->format('d/m/Y') }}</span>
                </div>
                @if($document->due_date)
                    <div style="font-size: 12px; margin-top: 4px;">
                        <span style="color: #888;">Vencimiento:</span>
                        <span style="font-weight: bold; margin-left: 5px;">{{ \Carbon\Carbon::parse($document->due_date)->format('d/m/Y') }}</span>
                    </div>
                @endif
            </td>
        </tr>
    </table>

    <div class="client-section" style="margin-bottom: 12px; border-left: 4px solid #E63946; padding: 8px 16px; background: #fcfcfc;">
        <div style="font-size: 10px; color: #888; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px;">Datos del Cliente</div>
        <div style="font-size: 18px; font-weight: bold; color: #333; margin-bottom: 10px;">{{ $document->client->name }}</div>
        
        <table style="width: 100%; font-size: 12px; color: #555;">
            <tr>
                <td style="width: 50%; padding-bottom: 5px;">
                    <span style="color: #999;">ID/NIT:</span> {{ $document->client->tax_id ?? '—' }}
                </td>
                <td style="width: 50%; padding-bottom: 5px;">
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

    <div class="clear"></div>

    <table class="table">
        <thead>
            <tr>
                <th style="width: 30px; text-align: center;">#</th>
                <th>Servicio / Descripción</th>
                <th style="width: 52px; text-align: center;">Cant.</th>
                <th style="width: 90px; text-align: right;">P. Unitario</th>
                <th style="width: 90px; text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($document->items as $index => $item)
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
                    <td style="text-align: right; font-weight: bold;">${{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 14px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 60%; vertical-align: top; padding-right: 20px;">
                    @if($document->notes)
                        <div style="background: #fcfcfc; border-left: 3px solid #E63946; padding: 10px 12px; border-radius: 0 6px 6px 0;">
                            <div style="font-weight: bold; color: #333; margin-bottom: 8px; text-transform: uppercase; font-size: 9px; letter-spacing: 0.05em; border-bottom: 1px solid #eee; padding-bottom: 4px;">Términos y Formas de Pago</div>
                            <div style="font-size: 10px; color: #555; line-height: 1.4;">
                                {!! nl2br(e($document->notes)) !!}
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
