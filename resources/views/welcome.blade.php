<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>{{ config('app.name', 'الأمن الاستخباراتي') }}</title>

    {{-- Zero-Flash Dark Mode Initialization --}}
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
    </script>

    {{-- Google Fonts: Cairo & Tajawal (Identical to tem) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 font-sans text-slate-50 antialiased flex flex-col justify-between selection:bg-brand-500/20 selection:text-brand-400 relative overflow-hidden">

    {{-- Ambient Lighting --}}
    <div class="absolute top-0 right-1/4 w-[600px] h-[600px] bg-brand-500/15 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-1/4 w-[500px] h-[500px] bg-indigo-500/10 rounded-full blur-[120px] pointer-events-none"></div>

    {{-- Top Navbar --}}
    <header class="h-20 max-w-7xl mx-auto px-6 w-full flex items-center justify-between relative z-10">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 text-white flex items-center justify-center font-display font-bold shadow-lg shadow-brand-500/30">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
            </div>
            <span class="font-display font-bold text-xl text-white">{{ config('app.name', 'نظام المعلومات') }}</span>
        </div>

        <div>
            @auth
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm transition-all shadow-lg shadow-brand-500/30">
                    لوحة التحكم
                </a>
            @else
                <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm transition-all shadow-lg shadow-brand-500/30">
                    تسجيل الدخول
                </a>
            @endauth
        </div>
    </header>

    {{-- Hero Section --}}
    <main class="max-w-4xl mx-auto px-6 text-center my-auto py-12 relative z-10">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-900 border border-slate-800 text-slate-300 text-xs font-bold mb-6">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>بالأمن الوقائي بالتعبئة العامة</span>
        </div>
        
        <h1 class="font-display font-bold text-4xl sm:text-6xl text-white tracking-tight leading-tight mb-6">
            {{ config('app.name', 'نظام المعلومات') }} <br>
            <span class="bg-gradient-to-l from-brand-400 via-indigo-300 to-white bg-clip-text text-transparent">
                بالأمن الوقائي بالتعبئة العامة
            </span>
        </h1>

        <p class="text-slate-400 text-base sm:text-lg max-w-2xl mx-auto mb-10 leading-relaxed">
            منصة مركزية متكاملة مصممة بأحدث التقاييس والمعايير لإدارة السجلات والبيانات التحليلية بدقة وسرية تامة.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-4">
            @auth
                <a href="{{ route('dashboard') }}" class="px-8 py-3.5 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-base transition-all shadow-xl shadow-brand-500/30 flex items-center gap-2">
                    <span>الدخول إلى لوحة التحكم</span>
                    <svg class="w-5 h-5 rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            @else
                <a href="{{ route('login') }}" class="px-8 py-3.5 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-base transition-all shadow-xl shadow-brand-500/30 flex items-center gap-2">
                    <span>تسجيل الدخول للمنظومة</span>
                    <svg class="w-5 h-5 rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            @endauth
        </div>
    </main>

    {{-- Footer --}}
    <footer class="h-16 border-t border-slate-900 flex items-center justify-center text-xs text-slate-500 relative z-10">
        جميع الحقوق محفوظة © {{ date('Y') }} • منظومة العمليات والأمن الذكي
    </footer>

</body>
</html>
