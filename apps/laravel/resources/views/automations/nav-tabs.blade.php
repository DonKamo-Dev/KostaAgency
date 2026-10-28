@php
    $isEmail = request()->routeIs('automations.email') || request()->routeIs('automations.index');
    $isWa = request()->routeIs('automations.whatsapp');
    $hasResend = filled(\App\Models\Setting::get('resend_api_key', env('RESEND_API_KEY')));
    $hasMeta = filled(\App\Models\Setting::get('whatsapp_meta_phone_number_id', env('WHATSAPP_PHONE_NUMBER_ID')))
        && filled(\App\Models\Setting::get('whatsapp_meta_access_token', env('WHATSAPP_ACCESS_TOKEN')));
@endphp
<div style="display: inline-flex; align-items: center; gap: 4px; background: rgba(255,255,255,0.03); border: 1px solid var(--border-subtle); padding: 4px; border-radius: 12px;">
    <a href="{{ route('automations.email') }}"
       style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; border-radius: 9px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.18s ease; {{ $isEmail ? 'background: #FFFFFF; color: #000000; box-shadow: 0 2px 8px rgba(0,0,0,0.25);' : 'color: var(--text-secondary);' }}">
        <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        <span>Correo (Resend)</span>
        <span title="{{ $hasResend ? 'Resend conectado' : 'Pendiente API Key' }}" style="width: 6px; height: 6px; border-radius: 50%; background: {{ $hasResend ? '#10B981' : '#F59E0B' }};"></span>
    </a>

    <a href="{{ route('automations.whatsapp') }}"
       style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; border-radius: 9px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.18s ease; {{ $isWa ? 'background: #FFFFFF; color: #000000; box-shadow: 0 2px 8px rgba(0,0,0,0.25);' : 'color: var(--text-secondary);' }}">
        <svg style="width: 15px; height: 15px; color: {{ $isWa ? '#059669' : '#25D366' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        <span>WhatsApp (Meta)</span>
        <span title="{{ $hasMeta ? 'Meta API conectado' : 'Pendiente credenciales' }}" style="width: 6px; height: 6px; border-radius: 50%; background: {{ $hasMeta ? '#10B981' : '#F59E0B' }};"></span>
    </a>
</div>
