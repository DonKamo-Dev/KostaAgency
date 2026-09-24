<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? __('landing.seo.title') }}</title>
    <meta name="description" content="{{ $description ?? __('landing.seo.description') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/site.js'])
    <style>
        :root {
            --bg-primary: #080808;
            --bg-gradient-1: #121214;
            --bg-gradient-2: #18181B;
            --accent-red: #FFFFFF;
            --accent-red-bright: #F4F4F5;
            --accent-red-dark: #27272A;
            --accent-red-glow: rgba(255, 255, 255, 0.15);
            --accent-red-soft: rgba(255, 255, 255, 0.05);
            --text-primary: #FFFFFF;
            --text-secondary: rgba(255, 255, 255, 0.75);
            --text-muted: rgba(255, 255, 255, 0.48);
            --text-subtle: rgba(255, 255, 255, 0.32);
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.08);
            --section-spacing: 64px;
            --section-spacing-mobile: 44px;
            --container-padding: 24px;
            --container-padding-md: 40px;
            --container-padding-lg: 56px;

            /* Motion tokens */
            --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
            --ease-in: cubic-bezier(0.7, 0, 0.84, 0);
            --duration-fast: 200ms;
            --duration-base: 300ms;
            --duration-slow: 600ms;
        }

        html { scroll-padding-top: 88px; }

        * { box-sizing: border-box; }

        /* ===== Typography System ===== */
        .font-display { font-family: 'Syne', sans-serif; letter-spacing: 0; }
        .font-body { font-family: 'Inter', sans-serif; }

        h1, h2, h3 {
            font-family: 'Syne', sans-serif;
            letter-spacing: 0;
            overflow-wrap: break-word;
            word-wrap: break-word;
            hyphens: none;
        }
        h1 {
            font-size: clamp(34px, 5.5vw, 64px);
            font-weight: 700;
            line-height: 1.05;
            padding-top: 0.05em;
        }
        h2 {
            font-size: clamp(28px, 4vw, 48px);
            font-weight: 700;
            line-height: 1.1;
        }
        h3 { font-weight: 600; }

        body {
            font-family: 'Inter', sans-serif;
            font-size: 16px;
            font-weight: 400;
            line-height: 1.5;
        }

        /* ===== Background ===== */
        .bg-dark { background-color: var(--bg-primary); }

        .bg-dark-gradient {
            background:
                radial-gradient(ellipse at 20% 0%, var(--bg-gradient-1) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 100%, var(--bg-gradient-2) 0%, transparent 50%),
                var(--bg-primary);
        }

        /* ===== Color Tokens ===== */
        .text-red-accent { color: var(--accent-red); }
        .bg-red-accent { background-color: var(--accent-red); }
        .border-red-accent { border-color: var(--accent-red); }

        /* ===== Section Spacing ===== */
        .section-spacing {
            padding-top: var(--section-spacing);
            padding-bottom: var(--section-spacing);
        }

        @media (max-width: 768px) {
            .section-spacing {
                padding-top: var(--section-spacing-mobile);
                padding-bottom: var(--section-spacing-mobile);
            }
        }

        /* ===== Glass Cards ===== */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            transition: border-color var(--duration-base) var(--ease-out),
                        background var(--duration-base) var(--ease-out),
                        transform var(--duration-base) var(--ease-out);
        }

        .glass-card:hover {
            border-color: rgba(255, 255, 255, 0.35);
            background: rgba(255, 255, 255, 0.06);
            transform: translateY(-4px);
        }

        .service-icon-wrapper {
            transition: transform var(--duration-base) var(--ease-out),
                        background var(--duration-base) var(--ease-out);
        }

        .glass-card:hover .service-icon-wrapper {
            transform: scale(1.08);
            background: rgba(255, 255, 255, 0.15);
        }

        .text-gradient {
            background: linear-gradient(135deg, #FFFFFF 0%, #A1A1AA 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* ===== Buttons (CTA) ===== */
        .btn-primary {
            background: #FFFFFF;
            color: #000000;
            height: 56px;
            padding: 0 32px;
            font-size: 16px;
            font-weight: 700;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.9);
            cursor: pointer;
            transition: transform var(--duration-fast) var(--ease-out),
                        box-shadow var(--duration-fast) var(--ease-out),
                        background var(--duration-fast) var(--ease-out);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            box-shadow: 0 4px 20px rgba(255, 255, 255, 0.15);
        }

        .btn-primary:hover {
            background: #E4E4E7;
            transform: translateY(-2px);
            box-shadow: 0 10px 32px rgba(255, 255, 255, 0.25);
        }

        .btn-primary:active { transform: scale(0.97); }

        .btn-primary:focus-visible {
            outline: 3px solid rgba(255, 255, 255, 0.6);
            outline-offset: 3px;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: #FFFFFF;
            height: 56px;
            padding: 0 28px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 999px;
            cursor: pointer;
            transition: border-color var(--duration-fast) var(--ease-out),
                        background var(--duration-fast) var(--ease-out),
                        transform var(--duration-fast) var(--ease-out);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-secondary:hover {
            border-color: rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.09);
            transform: translateY(-2px);
        }

        .btn-secondary:active { transform: scale(0.97); }

        .btn-secondary:focus-visible {
            outline: 3px solid var(--accent-red);
            outline-offset: 3px;
        }

        /* ===== Nav Links ===== */
        .floating-site-nav {
            position: fixed;
            top: 16px;
            left: 0;
            right: 0;
            z-index: 50;
            padding: 0 18px;
            pointer-events: none;
        }

        .floating-site-nav-frame {
            pointer-events: auto;
            overflow: hidden;
            border-radius: 28px;
            background:
                linear-gradient(135deg, rgba(34, 16, 19, 0.86), rgba(14, 14, 14, 0.86)),
                rgba(12, 12, 12, 0.82);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow:
                0 18px 50px rgba(0, 0, 0, 0.32),
                0 0 0 1px rgba(230, 57, 70, 0.06),
                inset 0 1px 0 rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(22px);
            -webkit-backdrop-filter: blur(22px);
        }

        .floating-site-nav-frame::before {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: radial-gradient(circle at 12% 0%, rgba(230, 57, 70, 0.18), transparent 38%);
        }

        @media (max-width: 767px) {
            .floating-site-nav {
                top: 10px;
                padding: 0 12px;
            }

            .floating-site-nav-frame {
                border-radius: 22px;
            }
        }

        .nav-link {
            position: relative;
            text-decoration: none;
            color: var(--text-secondary);
            transition: color var(--duration-fast) var(--ease-out);
            padding: 4px 2px;
            font-weight: 500;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent-red);
            transition: width var(--duration-base) var(--ease-out);
        }

        .nav-link:hover { color: #FFFFFF; }
        .nav-link:hover::after { width: 100%; }

        .nav-link:focus-visible {
            outline: 2px solid var(--accent-red);
            outline-offset: 4px;
            border-radius: 4px;
        }

        /* ===== Footer ===== */
        .footer-border {
            border-top: 1px solid rgba(230, 57, 70, 0.2);
        }

        .social-icon {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: var(--text-muted);
            transition: color var(--duration-fast) var(--ease-out),
                        background var(--duration-fast) var(--ease-out),
                        transform var(--duration-fast) var(--ease-out);
        }

        .social-icon svg { width: 18px; height: 18px; }

        .social-icon:hover {
            color: var(--accent-red);
            background: var(--accent-red-soft);
            transform: translateY(-2px);
        }

        .social-icon:focus-visible {
            outline: 2px solid var(--accent-red);
            outline-offset: 2px;
        }

        /* ===== Animations ===== */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulseRed {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 0.7; }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-fadeInUp {
            animation: fadeInUp var(--duration-slow) var(--ease-out) forwards;
        }

        .animate-pulse-glow {
            animation: pulseRed 4s ease-in-out infinite;
        }

        /* Animation Delays (stagger) */
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }

        /* Reduced Motion - Accessibility */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }

            .animate-float,
            .animate-pulse-glow {
                animation: none !important;
            }

            [class*="animate-fadeInUp"] {
                opacity: 1 !important;
                transform: none !important;
            }
        }

        /* ===== Stats Section ===== */
        .stats-section {
            background: linear-gradient(135deg, var(--accent-red-dark) 0%, var(--accent-red) 100%);
            color: #FFFFFF;
            position: relative;
            overflow: hidden;
        }

        .stats-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 30%, rgba(255, 255, 255, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(0, 0, 0, 0.2) 0%, transparent 50%);
            pointer-events: none;
        }

        .stats-number {
            font-family: 'Syne', sans-serif;
            font-size: clamp(40px, 6vw, 64px);
            font-weight: 700;
            line-height: 1;
            letter-spacing: 0;
        }

        .stats-label {
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            opacity: 0.85;
        }

        /* ===== Grid Pattern ===== */
        .grid-pattern {
            background-image:
                linear-gradient(rgba(230, 57, 70, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(230, 57, 70, 0.04) 1px, transparent 1px);
            background-size: 64px 64px;
            mask-image: radial-gradient(ellipse 80% 50% at 50% 0%, #000 50%, transparent 100%);
            -webkit-mask-image: radial-gradient(ellipse 80% 50% at 50% 0%, #000 50%, transparent 100%);
        }

        /* ===== Skip Link (a11y) ===== */
        .skip-link {
            position: absolute;
            top: -100px;
            left: 16px;
            background: var(--accent-red);
            color: #FFFFFF;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            z-index: 100;
            transition: top var(--duration-fast) var(--ease-out);
        }

        .skip-link:focus {
            top: 16px;
        }

        /* ===== Selection ===== */
        ::selection {
            background: var(--accent-red);
            color: #FFFFFF;
        }

        /* ===== Scrollbar ===== */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        ::-webkit-scrollbar-track { background: #0A0A0A; }
        ::-webkit-scrollbar-thumb {
            background: rgba(230, 57, 70, 0.3);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent-red);
        }

        /* ===== Eyebrow Label ===== */
        .eyebrow {
            display: inline-block;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--accent-red);
            margin-bottom: 16px;
        }

        /* ===== Testimonial Card ===== */
        .testimonial-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 32px;
            transition: border-color var(--duration-base) var(--ease-out),
                        transform var(--duration-base) var(--ease-out);
        }

        .testimonial-card:hover {
            border-color: var(--accent-red);
            transform: translateY(-4px);
        }

        /* ===== Logo Cloud ===== */
        .logo-item {
            color: rgba(255, 255, 255, 0.4);
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0;
            transition: color var(--duration-fast) var(--ease-out);
        }

        .logo-item:hover {
            color: var(--accent-red);
        }
    </style>
</head>
<body class="font-body antialiased bg-dark text-white overflow-x-hidden">
    <a href="#main" class="skip-link">{{ __('landing.nav.skip_main') }}</a>
    <div id="main">
    {{ $slot }}
    </div>

    {{-- Global Thinking Orb HUD --}}
    <div id="global-thinking-hud" class="global-thinking-hud">
        <thinking-orb state="working" size="20" label="{{ __('landing.nav.hud_processing') }}" pill></thinking-orb>
    </div>
</body>
</html>
