<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

use Illuminate\Support\Facades\Artisan;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\StringInput('migrate --force'),
    new Symfony\Component\Console\Output\ConsoleOutput
);

echo "Migration finished with status: $status\n";
