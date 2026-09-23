<x-guest-layout>
    <section style="max-width:720px;margin:0 auto;padding:48px 24px;line-height:1.7;">
        <h1 style="font-size:32px;margin-bottom:16px;">{{ __('landing.privacy.title') }}</h1>
        <p>{{ __('landing.privacy.p1') }}</p>
        <p>{{ __('landing.privacy.p2') }}</p>
        <p><a href="{{ route('contact') }}">{{ __('landing.privacy.back') }}</a></p>
    </section>
</x-guest-layout>
