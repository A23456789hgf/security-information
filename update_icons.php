<?php

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(__DIR__.'/resources/views')
);

$files = [];
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getBasename() === 'index.blade.php') {
        // Only target system and configuration folders
        $path = $file->getPathname();
        if (strpos($path, 'resources'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'system') !== false ||
            strpos($path, 'resources'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'configuration') !== false) {
            $files[] = $path;
        }
    }
}

$svgView = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" /><path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>';

$svgEdit = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" /><path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" /></svg>';

$svgDelete = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75V4H5a2 2 0 00-2 2v.25C3 6.664 3.336 7 3.75 7h12.5a.75.75 0 000-1.5h-.25V4A2.75 2.75 0 0013.25 1h-4.5zM6 5.5v-1.75C6 3.224 6.448 2.75 7 2.75h6c.552 0 1 .474 1 1V5.5H6z" clip-rule="evenodd" /><path d="M4 8.25V15.5A2.75 2.75 0 006.75 18.25h6.5A2.75 2.75 0 0016 15.5V8.25H4zM7.5 10a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 017.5 10zm5.25.75a.75.75 0 00-1.5 0v4.5a.75.75 0 001.5 0v-4.5z" /></svg>';

foreach ($files as $file) {
    if (strpos($file, 'personal_data') !== false) {
        continue;
    }
    if (strpos($file, 'wives') !== false) {
        continue;
    } // Already manually updated

    $content = file_get_contents($file);
    $original = $content;

    // 1. Replace Table Wrapper
    $content = preg_replace(
        '/<div class="bg-white rounded-\[2\.5rem\] soft-shadow border border-slate-100 overflow-hidden">\s*<div class="overflow-x-auto">\s*<table class="w-full text-right border-collapse">/s',
        '<div class="bg-white rounded-3xl soft-shadow border border-slate-100" style="overflow: visible !important;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">',
        $content
    );

    // 2. Replace thead row
    $content = preg_replace(
        '/<tr class="bg-slate-50\/50 border-b border-slate-100">/s',
        '<tr>',
        $content
    );

    // 3. Replace thead ths
    $content = preg_replace(
        '/<th class="px-8 py-6 text-slate-400 font-bold uppercase tracking-widest text-\[10px\]">#<\/th>/s',
        '<th class="ps-6 py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]" style="width: 80px;">الرقم</th>',
        $content
    );

    // General th replacement
    $content = preg_replace(
        '/<th class="px-6 py-6 text-slate-400 font-bold uppercase tracking-widest text-\[10px\]([^"]*)">/s',
        '<th class="py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]">',
        $content
    );

    $content = preg_replace(
        '/<th class="px-8 py-6 text-slate-400 font-bold uppercase tracking-widest text-\[10px\] text-left">الإجراءات<\/th>/s',
        '<th class="pe-6 py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px] text-end">الإجراءات</th>',
        $content
    );

    // 4. Replace Action td
    $content = preg_replace(
        '/<td class="px-8 py-5">\s*<div class="flex justify-start items-center gap-2">/s',
        '<td class="pe-6 py-3 text-end">
                                    <div class="flex justify-end items-center gap-2">',
        $content
    );

    // 5. Replace other tds
    $content = preg_replace('/<td class="px-[0-9]+ py-[0-9]+([^"]*)">/s', '<td class="$1">', $content);
    $content = str_replace('<td class="">', '<td>', $content);
    $content = str_replace('<td class=" text-center">', '<td class="text-center">', $content);

    // 6. Replace action links and buttons (SVG icons)
    // Edit icon
    $content = preg_replace(
        '/<a href="([^"]+)"\s*class="w-9 h-9 flex items-center justify-center rounded-xl bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition-all shadow-sm"\s*title="تعديل">\s*<i class="fas fa-edit text-sm"><\/i>\s*<\/a>/s',
        '<a href="$1" class="w-9 h-9 flex items-center justify-center rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-600 hover:text-white shadow-sm transition-all" title="تعديل">'."\n                                            ".$svgEdit."\n                                        ".'</a>',
        $content
    );

    // Delete icon
    $content = preg_replace(
        '/<button type="submit"\s*class="w-9 h-9 flex items-center justify-center rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-500 hover:text-white transition-all shadow-sm"\s*title="حذف">\s*<i class="fas fa-trash text-sm"><\/i>\s*<\/button>/s',
        '<button type="submit" class="w-9 h-9 flex items-center justify-center rounded-lg bg-rose-100 text-rose-700 hover:bg-rose-600 hover:text-white shadow-sm transition-all" title="حذف">'."\n                                                ".$svgDelete."\n                                            ".'</button>',
        $content
    );

    // View icon (if any)
    $content = preg_replace(
        '/<a href="([^"]+)"\s*class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-500 hover:text-white transition-all shadow-sm"\s*title="عرض">\s*<i class="fas fa-eye text-sm"><\/i>\s*<\/a>/s',
        '<a href="$1" class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-600 hover:text-white shadow-sm transition-all" title="عرض">'."\n                                            ".$svgView."\n                                        ".'</a>',
        $content
    );

    // Some pages might use "text-left" in th instead of what we matched
    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "Updated: $file\n";
    }
}
echo "All pages updated successfully!\n";
