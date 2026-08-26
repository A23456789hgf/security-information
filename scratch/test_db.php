<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$start = microtime(true);

$t1 = microtime(true);
$personalCount = \App\Models\PersonalData::count();
$timePersonal = round((microtime(true) - $t1) * 1000, 2);

$t2 = microtime(true);
$villagesCount = \App\Models\Village::count();
$timeVillages = round((microtime(true) - $t2) * 1000, 2);

$t3 = microtime(true);
$personalToday = \App\Models\PersonalData::whereDate('created_at', now())->count();
$timePersonalToday = round((microtime(true) - $t3) * 1000, 2);

$t4 = microtime(true);
$villagesToday = \App\Models\Village::whereDate('created_at', now())->count();
$timeVillagesToday = round((microtime(true) - $t4) * 1000, 2);

$totalTime = round((microtime(true) - $start) * 1000, 2);

echo "Total Time: {$totalTime} ms\n";
echo "Personal Count ({$personalCount}): {$timePersonal} ms\n";
echo "Villages Count ({$villagesCount}): {$timeVillages} ms\n";
echo "Personal Today ({$personalToday}): {$timePersonalToday} ms\n";
echo "Villages Today ({$villagesToday}): {$timeVillagesToday} ms\n";
