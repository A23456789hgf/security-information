<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'نظام الأمن الاستخباراتي') — {{ config('app.name', 'الأمن الاستخباراتي') }}</title>

    {{-- Zero-Flash Dark Mode Initialization & Global Toggle Function --}}
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();

        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
    </script>

    {{-- Google Fonts: Cairo & Tajawal (Identical to tem) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/all.min.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="min-h-screen bg-slate-950 font-sans text-slate-50 antialiased selection:bg-brand-500/20 selection:text-brand-400">
    @yield('content')

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
