<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensaje Recibido — Kosta Studio</title>
    <style>
        body { margin: 0; padding: 0; background-color: #0a0a0c; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #f4f4f5; }
        .wrapper { max-width: 580px; margin: 30px auto; background-color: #121216; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; overflow: hidden; }
        .header { background: radial-gradient(circle at top, #1f1f28 0%, #121216 100%); padding: 36px 32px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); text-align: center; }
        .badge { display: inline-block; background: rgba(230, 57, 70, 0.15); border: 1px solid rgba(230, 57, 70, 0.35); color: #e63946; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; padding: 4px 14px; border-radius: 999px; margin-bottom: 14px; }
        .title { color: #ffffff; font-size: 22px; font-weight: 700; margin: 0 0 8px 0; line-height: 1.3; }
        .subtitle { color: rgba(255, 255, 255, 0.65); font-size: 14px; margin: 0; }
        .body { padding: 32px; font-size: 14px; line-height: 1.7; color: rgba(255, 255, 255, 0.85); }
        .highlight-box { background: rgba(0, 0, 0, 0.4); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px; padding: 20px; margin: 24px 0; }
        .highlight-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 13px; }
        .highlight-label { color: rgba(255, 255, 255, 0.5); font-weight: 500; }
        .highlight-value { color: #ffffff; font-weight: 600; text-align: right; }
        .btn-wa { display: inline-block; background: #25D366; color: #ffffff; text-decoration: none; font-weight: 700; font-size: 14px; padding: 13px 26px; border-radius: 12px; margin: 12px 0; }
        .footer { padding: 24px 32px; background: #0c0c0e; border-top: 1px solid rgba(255, 255, 255, 0.06); text-align: center; font-size: 12px; color: rgba(255, 255, 255, 0.4); line-height: 1.6; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <span class="badge">KOSTA STUDIO & FILMS</span>
            <h1 class="title">¡Hola, {{ $lead->nombre }}!</h1>
            <p class="subtitle">Hemos recibido tu mensaje con éxito</p>
        </div>

        <div class="body">
            <p>
                Gracias por ponerte en contacto con nosotros. Nuestro equipo creativo y técnico ya está revisando los detalles de tu consulta sobre <strong>{{ $lead->servicio }}</strong>.
            </p>
            <p>
                Analizamos cada reto de manera personalizada para proponerte la mejor dirección técnica, creativa y comercial. Nos pondremos en contacto contigo en menos de <strong>24 horas laborables</strong>.
            </p>

            <div class="highlight-box">
                <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #e63946; font-weight: 700; margin-bottom: 14px;">Resumen de tu solicitud</div>
                <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                    <tr>
                        <td style="padding: 6px 0; color: rgba(255,255,255,0.5);">Servicio:</td>
                        <td style="padding: 6px 0; color: #ffffff; font-weight: 600; text-align: right;">{{ $lead->servicio }}</td>
                    </tr>
                    @if($lead->presupuesto)
                    <tr>
                        <td style="padding: 6px 0; color: rgba(255,255,255,0.5);">Rango estimado:</td>
                        <td style="padding: 6px 0; color: #ffffff; font-weight: 600; text-align: right;">{{ $lead->presupuesto }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="padding: 6px 0; color: rgba(255,255,255,0.5);">Fecha de envío:</td>
                        <td style="padding: 6px 0; color: #ffffff; font-weight: 600; text-align: right;">{{ $lead->created_at?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>

            <p style="margin-bottom: 6px;">¿Necesitas atención inmediata o tienes un proyecto urgente?</p>
            <div style="text-align: center; margin: 16px 0 24px;">
                <a href="https://wa.me/573113894136?text={{ urlencode('Hola Kosta, acabo de enviar una consulta desde la web sobre ' . $lead->servicio . ' y me gustaría hablar directamente.') }}" target="_blank" class="btn-wa">
                    Escríbenos directamente por WhatsApp
                </a>
            </div>

            <p style="color: rgba(255, 255, 255, 0.6); font-size: 13px;">
                Un saludo cordial,<br>
                <strong style="color: #ffffff;">Equipo Kosta Studio & Films</strong><br>
                Cartagena de Indias, Colombia
            </p>
        </div>

        <div class="footer">
            KOSTA STUDIO & FILMS · Creando marcas que se ven, historias que conectan y experiencias que venden.<br>
            <a href="https://kostastudio.com" style="color: #e63946; text-decoration: none;">kostastudio.com</a>
        </div>
    </div>
</body>
</html>
