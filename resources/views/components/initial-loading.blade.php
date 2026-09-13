{{-- resources/views/components/initial-loading.blade.php --}}
{{-- Initial Loading Splash matching tem InitialLoading.tsx --}}
<div id="initialSplashLoader" class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-slate-50 dark:bg-slate-950 transition-all duration-500" dir="rtl">
    <div class="flex flex-col items-center">
        <div class="relative">
            {{-- Outer Pulse Ring --}}
            <div class="absolute inset-0 rounded-2xl bg-brand-500/20 animate-ping"></div>
            
            {{-- Logo Box --}}
            <div class="relative z-10 flex items-center justify-center w-20 h-20 rounded-2xl bg-brand-600 text-white shadow-2xl shadow-brand-500/40">
                <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>
            </div>
        </div>
        
        <h1 class="mt-6 font-display font-bold text-2xl text-slate-900 dark:text-white">
            بوابة الأمن الذكية
        </h1>
        <p class="mt-2 text-sm font-medium text-slate-500 dark:text-slate-400">
            جاري تهيئة النظام الآمن...
        </p>

        {{-- Loading Bar --}}
        <div class="mt-8 w-48 h-1.5 bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden">
            <div class="h-full bg-brand-600 rounded-full animate-pulse w-full"></div>
        </div>
    </div>
</div>

<script>
window.addEventListener('load', function() {
    const loader = document.getElementById('initialSplashLoader');
    if (loader) {
        setTimeout(() => {
            loader.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(() => loader.remove(), 500);
        }, 400);
    }
});
</script>
