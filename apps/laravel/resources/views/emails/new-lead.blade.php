@php
    use App\Models\Setting;
    use App\Services\LeadNotificationService;

    $badge = Setting::get('email_tpl_kosta_badge', 'KOSTA STUDIO & FILMS · NUEVO PROSPECTO');
    $title = Setting::get('email_tpl_kosta_title', '¡Nuevo contacto recibido en la web!');
    $intro = Setting::get('email_tpl_kosta_intro', 'Se ha registrado una nueva solicitud desde el formulario de contacto.');
    $footerText = Setting::get('email_tpl_kosta_footer', 'Notificación automática generada por KOSTA Platform · ' . config('app.url'));

    $badge = LeadNotificationService::replaceMetaPlaceholders($badge, $lead);
    $title = LeadNotificationService::replaceMetaPlaceholders($title, $lead);
    $intro = LeadNotificationService::replaceMetaPlaceholders($intro, $lead);
    $footerText = LeadNotificationService::replaceMetaPlaceholders($footerText, $lead);
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #0d0d0f; }
        ::-webkit-scrollbar-thumb { background: #27272a; border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: #3f3f46; }
        body { margin: 0; padding: 16px; background-color: #0d0d0f; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #f4f4f5; }
        .wrapper { max-width: 600px; margin: 8px auto; background-color: #18181b; border: 1px solid #27272a; border-radius: 16px; overflow: hidden; }
        .header { background: linear-gradient(135deg, #18181b 0%, #0d0d0f 100%); padding: 32px; border-bottom: 1px solid #27272a; text-align: center; }
        .badge { display: inline-block; background: rgba(230, 57, 70, 0.15); border: 1px solid rgba(230, 57, 70, 0.3); color: #e63946; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; padding: 4px 12px; border-radius: 999px; margin-bottom: 12px; }
        .title { color: #ffffff; font-size: 22px; font-weight: 700; margin: 0; }
        .intro-text { color: #a1a1aa; font-size: 13.5px; margin: 8px 0 0 0; }
        .body { padding: 32px; }
        .info-grid { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .info-grid td { padding: 12px 0; border-bottom: 1px solid #27272a; font-size: 14px; vertical-align: top; }
        .label { color: #a1a1aa; width: 38%; font-weight: 500; }
        .val { color: #ffffff; font-weight: 600; }
        .val a { color: #e63946; text-decoration: none; }
        .message-box { background: #09090b; border: 1px solid #27272a; border-radius: 12px; padding: 18px; margin: 20px 0; font-size: 14px; line-height: 1.6; color: #d4d4d8; }
        .actions { text-align: center; padding: 24px 0 8px; }
        .btn-wa { display: inline-block; background: #25D366; color: #ffffff; text-decoration: none; font-weight: 700; font-size: 14px; padding: 12px 24px; border-radius: 10px; margin: 4px; }
        .btn-panel { display: inline-block; background: #ffffff; color: #000000; text-decoration: none; font-weight: 700; font-size: 14px; padding: 12px 24px; border-radius: 10px; margin: 4px; }
        .footer { padding: 20px 32px; background: #09090b; border-top: 1px solid #27272a; text-align: center; font-size: 12px; color: #71717a; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <span class="badge">{{ $badge }}</span>
            <h1 class="title">{{ $title }}</h1>
            @if(filled($intro))
                <p class="intro-text">{{ $intro }}</p>
            @endif
        </div>

        <div class="body">
            <table class="info-grid">
                <tr>
                    <td class="label">Nombre completo:</td>
                    <td class="val">{{ $lead->nombre }}</td>
                </tr>
                <tr>
                    <td class="label">Correo electrónico:</td>
                    <td class="val"><a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></td>
                </tr>
                <tr>
                    <td class="label">Teléfono / WhatsApp:</td>
                    <td class="val">{{ $lead->telefono ?: 'No especificado' }}</td>
                </tr>
                <tr>
                    <td class="label">Tipo de cliente:</td>
                    <td class="val"><strong>{{ $lead->tipo_cliente ?: 'No indicado' }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Servicio de interés:</td>
                    <td class="val">{{ $lead->servicio }}</td>
                </tr>
                <tr>
                    <td class="label">Presupuesto estimado:</td>
                    <td class="val">{{ $lead->presupuesto ?: 'Sin definir' }}</td>
                </tr>
                <tr>
                    <td class="label">¿Cómo nos conoció?:</td>
                    <td class="val">{{ $lead->como_nos_conocio ?: 'No indicado' }}</td>
                </tr>
                <tr>
                    <td class="label">Fecha y hora:</td>
                    <td class="val">{{ $lead->created_at?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i') }}</td>
                </tr>
            </table>

            @if($lead->mensaje)
                <p style="margin: 0 0 6px 0; font-size: 12px; color: #a1a1aa; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Mensaje / Descripción del proyecto:</p>
                <div class="message-box">
                    {!! nl2br(e($lead->mensaje)) !!}
                </div>
            @endif

            <div class="actions">
                @if($lead->whatsapp_url)
                    <a href="{{ $lead->whatsapp_url }}" target="_blank" class="btn-wa">Abrir Chat de WhatsApp</a>
                @endif
                <a href="{{ url('/leads') }}" target="_blank" class="btn-panel">Ver en la Plataforma</a>
            </div>
        </div>

        <div class="footer">
            {{ $footerText }}
        </div>
    </div>
</body>
</html>
