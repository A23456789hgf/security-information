<div class="flex items-center gap-2">
    <label for="per_page" class="text-xs font-bold text-slate-500 dark:text-slate-400 whitespace-nowrap mb-0">عرض:</label>
    <select name="per_page" id="per_page" class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-xl px-3 py-2 outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all cursor-pointer" onchange="this.form.submit()">
        @foreach([50, 100, 500, 1000] as $limit)
            <option value="{{ $limit }}" {{ request('per_page', 50) == $limit ? 'selected' : '' }}>{{ $limit }} سجل</option>
        @endforeach
    </select>
</div>
