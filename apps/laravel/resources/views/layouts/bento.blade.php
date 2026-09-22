<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Yohan Blanco - WordPress Developer & Digital Marketing Specialist</title>

    <!-- Theme hydration before paint (defaults to dark mode) -->
    <script>
        (function() {
            try {
                const storedTheme = localStorage.getItem('bento_theme');
                if (storedTheme === 'light') {
                    document.documentElement.classList.remove('dark');
                    document.documentElement.classList.add('light');
                } else {
                    document.documentElement.classList.add('dark');
                    document.documentElement.classList.remove('light');
                }
            } catch (e) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <!-- Google Fonts: Syne & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])

    <style>
        :root {
            --bento-bg: #F8F9FA;
            --bento-surface: #FFFFFF;
            --bento-surface-subtle: #F1F3F5;
            --bento-surface-hover: #E9ECEF;
            --bento-border: rgba(0, 0, 0, 0.08);
            --bento-border-hover: rgba(0, 0, 0, 0.16);
            --bento-text-main: #09090B;
            --bento-text-muted: #52525B;
            --bento-text-subtle: #71717A;
            --bento-primary-btn-bg: #09090B;
            --bento-primary-btn-text: #FFFFFF;
            --bento-secondary-btn-bg: #FFFFFF;
            --bento-secondary-btn-border: rgba(0, 0, 0, 0.1);
            --bento-secondary-btn-text: #09090B;
            --bento-card-shadow: 0 1px 3px rgba(0, 0, 0, 0.03), 0 4px 12px rgba(0, 0, 0, 0.03);
            --bento-card-shadow-hover: 0 8px 24px rgba(0, 0, 0, 0.07);
            --radius-card: 12px;
            --radius-btn: 8px;
            --radius-badge: 6px;
            --radius-avatar: 50%;
        }

        html.dark {
            --bento-bg: #09090B;
            --bento-surface: #121214;
            --bento-surface-subtle: #18181B;
            --bento-surface-hover: #222226;
            --bento-border: rgba(255, 255, 255, 0.08);
            --bento-border-hover: rgba(255, 255, 255, 0.18);
            --bento-text-main: #FFFFFF;
            --bento-text-muted: #A1A1AA;
            --bento-text-subtle: #71717A;
            --bento-primary-btn-bg: #FFFFFF;
            --bento-primary-btn-text: #09090B;
            --bento-secondary-btn-bg: #141416;
            --bento-secondary-btn-border: rgba(255, 255, 255, 0.12);
            --bento-secondary-btn-text: #FFFFFF;
            --bento-card-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
            --bento-card-shadow-hover: 0 8px 28px rgba(0, 0, 0, 0.6);
        }

        html, body {
            background-color: var(--bento-bg) !important;
            background: var(--bento-bg) !important;
            color: var(--bento-text-main);
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            transition: background-color 0.25s ease, color 0.25s ease;
        }

        .font-display {
            font-family: 'Syne', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen antialiased selection:bg-zinc-700 selection:text-white m-0 p-0 flex justify-center items-start">
    {{ $slot }}
</body>
</html>
