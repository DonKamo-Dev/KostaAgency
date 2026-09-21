<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kosta') }} - Acceso</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.jsx'])

        <style>
            /* Luxury Monochrome Design System */
            :root {
                --bg-primary: #080808;
                --bg-elevated: #101010;
                --bg-card: rgba(18, 18, 18, 0.85);
                --red-primary: #FFFFFF;
                --red-glow: rgba(255, 255, 255, 0.12);
                --red-dark: #27272A;
                --text-primary: #FAFAFA;
                --text-secondary: rgba(250, 250, 250, 0.75);
                --text-muted: rgba(250, 250, 250, 0.48);
                --border-subtle: rgba(255, 255, 255, 0.12);
                --border-focus: rgba(255, 255, 255, 0.35);
            }

            * {
                font-family: 'Inter', system-ui, sans-serif;
            }

            .font-display {
                font-family: 'Space Grotesk', sans-serif;
            }

            /* Ambient Red Glow Effect */
            .ambient-glow {
                position: fixed;
                pointer-events: none;
                z-index: 0;
            }

            .ambient-glow-1 {
                top: -20%;
                right: -10%;
                width: 600px;
                height: 600px;
                background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
                filter: blur(80px);
                animation: pulse-glow 4s ease-in-out infinite;
            }

            .ambient-glow-2 {
                bottom: -30%;
                left: -20%;
                width: 800px;
                height: 800px;
                background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 60%);
                filter: blur(100px);
                animation: pulse-glow 6s ease-in-out infinite reverse;
            }

            @keyframes pulse-glow {
                0%, 100% { opacity: 0.6; transform: scale(1); }
                50% { opacity: 1; transform: scale(1.1); }
            }

            /* Glass Card with Red Edge Glow */
            .login-card {
                background: var(--bg-card);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid var(--border-subtle);
                border-radius: 24px;
                position: relative;
                overflow: hidden;
                padding: 48px 44px;
                box-shadow: 
                    0 25px 50px -12px rgba(0, 0, 0, 0.8),
                    0 0 0 1px rgba(255, 255, 255, 0.08),
                    inset 0 1px 0 rgba(255, 255, 255, 0.1);
            }

            @media (max-width: 480px) {
                .login-card {
                    padding: 36px 28px;
                }
            }

            /* Custom Inputs - Dark with Red Focus */
            .custom-input {
                background: rgba(10, 10, 10, 0.6);
                border: 1px solid rgba(245, 245, 245, 0.1);
                border-radius: 12px;
                color: var(--text-primary);
                padding: 14px 16px;
                font-size: 15px;
                transition: all 0.25s ease;
                width: 100%;
            }

            .custom-input:hover {
                border-color: rgba(230, 57, 70, 0.3);
            }

            .custom-input:focus {
                outline: none;
                border-color: var(--red-primary);
                box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.15);
            }

            .custom-input::placeholder {
                color: var(--text-muted);
            }

            /* Override Chrome autofill yellow/white background */
            .custom-input:-webkit-autofill,
            .custom-input:-webkit-autofill:hover,
            .custom-input:-webkit-autofill:focus,
            .custom-input:-webkit-autofill:active {
                -webkit-text-fill-color: var(--text-primary) !important;
                -webkit-box-shadow: 0 0 0 1000px rgba(10, 10, 10, 0.95) inset !important;
                box-shadow: 0 0 0 1000px rgba(10, 10, 10, 0.95) inset !important;
                transition: background-color 5000s ease-in-out 0s;
                caret-color: var(--text-primary);
            }

            /* Primary Button - Luxury White */
            .btn-primary-red {
                background: #FFFFFF;
                color: #000000;
                font-weight: 700;
                font-size: 15px;
                padding: 14px 28px;
                border-radius: 12px;
                border: 1px solid rgba(255, 255, 255, 0.9);
                cursor: pointer;
                transition: all 0.25s ease;
                position: relative;
                overflow: hidden;
                box-shadow: 
                    0 4px 20px rgba(255, 255, 255, 0.15),
                    0 1px 3px rgba(0, 0, 0, 0.3);
            }

            .btn-primary-red:hover {
                background: #E4E4E7;
                transform: translateY(-2px);
                box-shadow: 
                    0 8px 30px rgba(255, 255, 255, 0.25),
                    0 2px 4px rgba(0, 0, 0, 0.3);
            }

            .btn-primary-red:active {
                transform: translateY(0);
            }

            .btn-primary-red:disabled {
                cursor: wait;
                opacity: 0.85;
                transform: none;
            }

            .auth-loading-screen {
                position: fixed;
                inset: 0;
                z-index: 50;
                align-items: center;
                justify-content: center;
                background:
                    radial-gradient(circle at 50% 45%, rgba(230, 57, 70, 0.22), transparent 34%),
                    rgba(10, 10, 10, 0.86);
                backdrop-filter: blur(14px);
                -webkit-backdrop-filter: blur(14px);
            }

            .auth-loading-panel {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 16px;
                padding: 28px 32px;
                color: var(--text-primary);
                text-align: center;
            }

            .auth-loading-mark {
                width: 68px;
                height: 68px;
                border-radius: 18px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #18181B, #27272A);
                border: 1px solid rgba(255, 255, 255, 0.2);
                box-shadow:
                    0 18px 50px rgba(0, 0, 0, 0.6),
                    inset 0 1px 0 rgba(255, 255, 255, 0.2);
                animation: loading-float 1.4s ease-in-out infinite;
            }

            .auth-loading-ring {
                width: 108px;
                height: 108px;
                position: absolute;
                border-radius: 999px;
                border: 1px solid rgba(245, 245, 245, 0.08);
                border-top-color: var(--red-primary);
                animation: loading-spin 0.9s linear infinite;
            }

            .auth-loading-title {
                margin: 8px 0 0;
                font-family: 'Space Grotesk', sans-serif;
                font-size: 20px;
                font-weight: 700;
            }

            .auth-loading-copy {
                margin: 0;
                color: var(--text-secondary);
                font-size: 14px;
            }

            @keyframes loading-spin {
                to { transform: rotate(360deg); }
            }

            @keyframes loading-float {
                0%, 100% { transform: translateY(0) scale(1); }
                50% { transform: translateY(-4px) scale(1.03); }
            }

            /* Checkbox Custom Styling */
            .custom-checkbox {
                appearance: none;
                width: 18px;
                height: 18px;
                border: 2px solid rgba(245, 245, 245, 0.3);
                border-radius: 4px;
                background: transparent;
                cursor: pointer;
                position: relative;
                transition: all 0.2s ease;
            }

            .custom-checkbox:checked {
                background: var(--red-primary);
                border-color: var(--red-primary);
            }

            .custom-checkbox:checked::after {
                content: '';
                position: absolute;
                left: 4px;
                top: 1px;
                width: 5px;
                height: 9px;
                border: solid white;
                border-width: 0 2px 2px 0;
                transform: rotate(45deg);
            }

            /* Links */
            .custom-link {
                color: var(--text-secondary);
                text-decoration: none;
                font-size: 14px;
                transition: color 0.2s ease;
            }

            .custom-link:hover {
                color: var(--red-primary);
            }

            /* Error Messages */
            .error-message {
                color: var(--red-primary);
                font-size: 13px;
                margin-top: 6px;
            }

            /* Logo Container */
            .logo-container {
                width: 64px;
                height: 64px;
                background: linear-gradient(135deg, #18181B, #27272A);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 
                    0 10px 40px rgba(0, 0, 0, 0.5),
                    inset 0 1px 0 rgba(255, 255, 255, 0.2);
            }

            /* Label Styling */
            .custom-label {
                color: var(--text-secondary);
                font-size: 14px;
                font-weight: 500;
                margin-bottom: 8px;
                display: block;
            }

            /* Grid Pattern Background */
            .grid-pattern {
                background-image: 
                    linear-gradient(rgba(230, 57, 70, 0.03) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(230, 57, 70, 0.03) 1px, transparent 1px);
                background-size: 50px 50px;
            }

            /* Auth Container - Force max width */
            .auth-wrapper {
                width: 100%;
                max-width: 440px;
                margin: 0 auto;
            }

            /* Body styles */
            body {
                background-color: #0A0A0A;
                color: var(--text-primary);
                margin: 0;
                min-height: 100vh;
            }
        </style>
    </head>
    <body style="background-color: var(--bg-primary);" class="antialiased">
        <a href="#main" class="skip-link">Saltar al contenido principal</a>

        <!-- Ambient Glow Effects -->
        <div class="ambient-glow ambient-glow-1"></div>
        <div class="ambient-glow ambient-glow-2"></div>

        <!-- Grid Pattern Overlay -->
        <div class="fixed inset-0 grid-pattern pointer-events-none" style="opacity: 0.5;"></div>

        <!-- Main Content -->
        <div id="main" class="min-h-screen flex flex-col justify-start items-center relative z-10 px-4 sm:px-6 pt-20 pb-12">
            {{ $slot }}
        </div>

        {{-- Global Thinking Orb HUD --}}
        <div id="global-thinking-hud" class="global-thinking-hud">
            <thinking-orb state="working" size="20" label="Procesando..." pill></thinking-orb>
        </div>
    </body>
</html>
