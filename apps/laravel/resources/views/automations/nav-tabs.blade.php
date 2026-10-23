<div class="auto-tabs-container" style="display: flex; gap: 8px; margin-bottom: 26px; border-bottom: 1px solid var(--border-subtle); padding-bottom: 12px; flex-wrap: wrap;">
    <a href="{{ route('automations.email') }}" wire:navigate class="auto-tab-btn {{ request()->routeIs('automations.email') || request()->routeIs('automations.index') ? 'active' : '' }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 10px; font-size: 13.5px; font-weight: 600; text-decoration: none; transition: all 0.2s ease; {{ request()->routeIs('automations.email') || request()->routeIs('automations.index') ? 'background: #FFFFFF; color: #000000; box-shadow: 0 2px 10px rgba(255,255,255,0.15);' : 'background: rgba(255,255,255,0.03); color: var(--text-secondary); border: 1px solid var(--border-subtle);' }}">
        <svg style="width: 17px; height: 17px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        <span>Correo Electrónico (Resend)</span>
        @php
            $hasResend = filled(\App\Models\Setting::get('resend_api_key', env('RESEND_API_KEY')));
        @endphp
        <span style="width: 7px; height: 7px; border-radius: 50%; background: {{ $hasResend ? '#10B981' : '#F59E0B' }};"></span>
    </a>

    <a href="{{ route('automations.whatsapp') }}" wire:navigate class="auto-tab-btn {{ request()->routeIs('automations.whatsapp') ? 'active' : '' }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 10px; font-size: 13.5px; font-weight: 600; text-decoration: none; transition: all 0.2s ease; {{ request()->routeIs('automations.whatsapp') ? 'background: #FFFFFF; color: #000000; box-shadow: 0 2px 10px rgba(255,255,255,0.15);' : 'background: rgba(255,255,255,0.03); color: var(--text-secondary); border: 1px solid var(--border-subtle);' }}">
        <svg style="width: 17px; height: 17px; color: {{ request()->routeIs('automations.whatsapp') ? '#059669' : '#25D366' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        <span>WhatsApp (Meta Business)</span>
        @php
            $hasMeta = filled(\App\Models\Setting::get('whatsapp_meta_phone_number_id', env('WHATSAPP_PHONE_NUMBER_ID'))) && filled(\App\Models\Setting::get('whatsapp_meta_access_token', env('WHATSAPP_ACCESS_TOKEN')));
        @endphp
        <span style="width: 7px; height: 7px; border-radius: 50%; background: {{ $hasMeta ? '#10B981' : '#F59E0B' }};"></span>
    </a>
</div>
