<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light" style="background-color: #FFFFFF !important;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Yohan Blanco - WordPress Developer & Digital Marketing Specialist</title>

    <!-- Google Fonts: Outfit & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])

    <style>
        :root {
            --accent-red: #E63946;
            --accent-red-hover: #D62839;
            --accent-red-soft: rgba(230, 57, 70, 0.08);
            --bg-bento: #FFFFFF;
            --card-bg: #FFFFFF;
            --card-border: rgba(15, 23, 42, 0.08);
            --card-shadow: 0 1px 3px rgba(0, 0, 0, 0.02), 0 6px 16px rgba(0, 0, 0, 0.04);
            --card-shadow-hover: 0 12px 28px rgba(0, 0, 0, 0.07);
        }

        html, body {
            background-color: #FFFFFF !important;
            background: #FFFFFF !important;
            color: #0F172A;
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .font-display {
            font-family: 'Outfit', sans-serif;
        }

        .bento-card-box {
            background: #FFFFFF;
            border: 1px solid var(--card-border);
            border-radius: 22px;
            box-shadow: var(--card-shadow);
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1),
                        box-shadow 0.25s cubic-bezier(0.16, 1, 0.3, 1),
                        border-color 0.25s ease;
        }

        .bento-card-box:hover {
            transform: translateY(-2px);
            box-shadow: var(--card-shadow-hover);
            border-color: rgba(15, 23, 42, 0.14);
        }
    </style>
</head>
<body class="min-h-screen antialiased selection:bg-[#E63946] selection:text-white bg-white m-0 p-0 flex justify-center items-start">
    {{ $slot }}
</body>
</html>
