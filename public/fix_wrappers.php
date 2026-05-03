<?php
$dir = __DIR__ . '/../resources/views/system';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$files = [];
foreach ($iterator as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), 'index.blade.php')) {
        $files[] = $file->getPathname();
    }
}

foreach ($files as $file) {
    $content = file_get_contents($file);
    $original = $content;

    $content = str_replace('<div class="d-flex justify-content-center gap-1">', '<div class="flex justify-end items-center gap-2">', $content);
    $content = str_replace('<div class="btn-group">', '<div class="flex justify-end items-center gap-2">', $content);

    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "Updated wrapper in " . basename(dirname($file)) . "/" . basename($file) . "<br>\n";
    }
}
echo "Done.";
