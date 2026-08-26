<?php

$dir = __DIR__.'/../resources/views';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$files = [];
foreach ($iterator as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), 'index.blade.php')) {
        $files[] = $file->getPathname();
    }
}

$editSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                                <path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                                <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                            </svg>';

$deleteSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                                    <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75V4H5a2 2 0 00-2 2v.25C3 6.664 3.336 7 3.75 7h12.5a.75.75 0 000-1.5h-.25V4A2.75 2.75 0 0013.25 1h-4.5zM6 5.5v-1.75C6 3.224 6.448 2.75 7 2.75h6c.552 0 1 .474 1 1V5.5H6z" clip-rule="evenodd" />
                                                    <path d="M4 8.25V15.5A2.75 2.75 0 006.75 18.25h6.5A2.75 2.75 0 0016 15.5V8.25H4zM7.5 10a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 017.5 10zm5.25.75a.75.75 0 00-1.5 0v4.5a.75.75 0 001.5 0v-4.5z" />
                                                </svg>';

$viewSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                                <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                                <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                            </svg>';

$editClass = 'w-9 h-9 flex items-center justify-center rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-600 hover:text-white shadow-sm transition-all';
$deleteClass = 'w-9 h-9 flex items-center justify-center rounded-lg bg-rose-100 text-rose-700 hover:bg-rose-600 hover:text-white shadow-sm transition-all';
$viewClass = 'w-9 h-9 flex items-center justify-center rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-600 hover:text-white shadow-sm transition-all';

$updatedCount = 0;

foreach ($files as $file) {
    if (str_contains($file, 'personal_data')) {
        continue;
    }

    $content = file_get_contents($file);
    $original = $content;

    // A safer way to match `<a ... title="تعديل" ...> <i class="fa-edit"></i> </a>`
    $content = preg_replace_callback(
        '/<a([^>]*?title="تعديل"[^>]*?)>\s*<i[^>]*?fa-edit[^>]*?><\/i>\s*<\/a>/is',
        function ($matches) use ($editClass, $editSvg) {
            $attrs = $matches[1];
            $attrs = preg_replace('/class="[^"]*"/', 'class="'.$editClass.'"', $attrs);

            return "<a$attrs>\n                                            ".$editSvg."\n                                        </a>";
        },
        $content
    );

    // And where title is absent but class has btn
    $content = preg_replace_callback(
        '/<a([^>]*?href="[^"]+edit[^"]*"[^>]*?)>\s*<i[^>]*?fa-edit[^>]*?><\/i>\s*<\/a>/is',
        function ($matches) use ($editClass, $editSvg) {
            $attrs = $matches[1];
            // ensure we don't double replace
            if (! str_contains($attrs, 'title=')) {
                $attrs .= ' title="تعديل"';
            }
            if (preg_match('/class="[^"]*"/', $attrs)) {
                $attrs = preg_replace('/class="[^"]*"/', 'class="'.$editClass.'"', $attrs);
            } else {
                $attrs .= ' class="'.$editClass.'"';
            }

            return "<a$attrs>\n                                            ".$editSvg."\n                                        </a>";
        },
        $content
    );

    $content = preg_replace_callback(
        '/<button([^>]*?title="حذف"[^>]*?)>\s*<i[^>]*?fa-trash[^>]*?><\/i>\s*<\/button>/is',
        function ($matches) use ($deleteClass, $deleteSvg) {
            $attrs = $matches[1];
            $attrs = preg_replace('/class="[^"]*"/', 'class="'.$deleteClass.'"', $attrs);

            return "<button$attrs>\n                                                ".$deleteSvg."\n                                            </button>";
        },
        $content
    );

    $content = preg_replace_callback(
        '/<a([^>]*?title="عرض"[^>]*?)>\s*<i[^>]*?fa-eye[^>]*?><\/i>\s*<\/a>/is',
        function ($matches) use ($viewClass, $viewSvg) {
            $attrs = $matches[1];
            $attrs = preg_replace('/class="[^"]*"/', 'class="'.$viewClass.'"', $attrs);

            return "<a$attrs>\n                                            ".$viewSvg."\n                                        </a>";
        },
        $content
    );

    $content = preg_replace_callback(
        '/<a([^>]*?href="[^"]+show[^"]*"[^>]*?)>\s*<i[^>]*?fa-eye[^>]*?><\/i>\s*<\/a>/is',
        function ($matches) use ($viewClass, $viewSvg) {
            $attrs = $matches[1];
            if (! str_contains($attrs, 'title=')) {
                $attrs .= ' title="عرض"';
            }
            if (preg_match('/class="[^"]*"/', $attrs)) {
                $attrs = preg_replace('/class="[^"]*"/', 'class="'.$viewClass.'"', $attrs);
            } else {
                $attrs .= ' class="'.$viewClass.'"';
            }

            return "<a$attrs>\n                                            ".$viewSvg."\n                                        </a>";
        },
        $content
    );

    // Some pages already have SVG but with old formatting
    $content = preg_replace_callback(
        '/<a([^>]*?title="تعديل"[^>]*?)>\s*<svg.*?<\/svg>\s*<\/a>/is',
        function ($matches) use ($editClass, $editSvg) {
            $attrs = $matches[1];
            if (str_contains($attrs, 'text-amber-700') && str_contains($attrs, 'rounded-lg')) {
                return $matches[0];
            }
            $attrs = preg_replace('/class="[^"]*"/', 'class="'.$editClass.'"', $attrs);

            return "<a$attrs>\n                                            ".$editSvg."\n                                        </a>";
        },
        $content
    );

    $content = preg_replace_callback(
        '/<button([^>]*?title="حذف"[^>]*?)>\s*<svg.*?<\/svg>\s*<\/button>/is',
        function ($matches) use ($deleteClass, $deleteSvg) {
            $attrs = $matches[1];
            if (str_contains($attrs, 'text-rose-700') && str_contains($attrs, 'rounded-lg')) {
                return $matches[0];
            }
            $attrs = preg_replace('/class="[^"]*"/', 'class="'.$deleteClass.'"', $attrs);

            return "<button$attrs>\n                                                ".$deleteSvg."\n                                            </button>";
        },
        $content
    );

    $content = preg_replace_callback(
        '/<a([^>]*?title="عرض"[^>]*?)>\s*<svg.*?<\/svg>\s*<\/a>/is',
        function ($matches) use ($viewClass, $viewSvg) {
            $attrs = $matches[1];
            if (str_contains($attrs, 'text-blue-700') && str_contains($attrs, 'rounded-lg')) {
                return $matches[0];
            }
            $attrs = preg_replace('/class="[^"]*"/', 'class="'.$viewClass.'"', $attrs);

            return "<a$attrs>\n                                            ".$viewSvg."\n                                        </a>";
        },
        $content
    );

    $content = preg_replace(
        '/<div\s+class="flex\s+justify-start\s+items-center\s+gap-2"\s*>/is',
        '<div class="flex justify-end items-center gap-2">',
        $content
    );
    $content = preg_replace(
        '/<div\s+class="d-flex\s+justify-content-center\s+gap-2"\s*>/is',
        '<div class="flex justify-end items-center gap-2">',
        $content
    );

    if ($original !== $content) {
        file_put_contents($file, $content);
        $updatedCount++;
        echo 'Updated '.basename(dirname($file)).'/'.basename($file)."<br>\n";
    }
}
echo "<br>\nTotal updated: $updatedCount files.";
