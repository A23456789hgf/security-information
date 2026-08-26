<?php
$baseDir = __DIR__ . '/resources/views/configuration/';
$sourceDir = $baseDir . 'job_status/';

$targets = [
    [
        'dir' => 'kinships',
        'replacements' => [
            'job-statuses' => 'kinships',
            '$jobStatuses' => '$kinships',
            '$status->job_status' => '$item->name',
            '$status->id' => '$item->id',
            '$status->created_at' => '$item->created_at',
            '$status' => '$item',
            'job_status' => 'name',
            'الحالات الوظيفية' => 'صلات القرابة',
            'الحالة الوظيفية' => 'صلة القرابة',
            'حالة وظيفية' => 'صلة قرابة',
            'حالة جديدة' => 'صلة قرابة جديدة',
            'مثال: موظف، متقاعد، متعاقد...' => 'مثال: أب، أم، أخ، ابن...',
        ]
    ],
    [
        'dir' => 'ideological_affiliations',
        'replacements' => [
            'job-statuses' => 'ideological-affiliations',
            '$jobStatuses' => '$ideologicalAffiliations',
            '$status->job_status' => '$item->name',
            '$status->id' => '$item->id',
            '$status->created_at' => '$item->created_at',
            '$status' => '$item',
            'job_status' => 'name',
            'الحالات الوظيفية' => 'الانتماءات العقائدية',
            'الحالة الوظيفية' => 'الانتماء العقائدي',
            'حالة وظيفية' => 'انتماء عقائدي',
            'حالة جديدة' => 'انتماء جديد',
            'مثال: موظف، متقاعد، متعاقد...' => 'مثال: سني، زيدي...',
        ]
    ],
    [
        'dir' => 'political_affiliations',
        'replacements' => [
            'job-statuses' => 'political-affiliations',
            '$jobStatuses' => '$politicalAffiliations',
            '$status->job_status' => '$item->name',
            '$status->id' => '$item->id',
            '$status->created_at' => '$item->created_at',
            '$status' => '$item',
            'job_status' => 'name',
            'الحالات الوظيفية' => 'الانتماءات السياسية',
            'الحالة الوظيفية' => 'الانتماء السياسي',
            'حالة وظيفية' => 'انتماء سياسي',
            'حالة جديدة' => 'انتماء جديد',
            'مثال: موظف، متقاعد، متعاقد...' => 'مثال: مؤتمر، إصلاح...',
        ]
    ],
    [
        'dir' => 'health_statuses',
        'replacements' => [
            'job-statuses' => 'health-statuses',
            '$jobStatuses' => '$healthStatuses',
            '$status->job_status' => '$item->name',
            '$status->id' => '$item->id',
            '$status->created_at' => '$item->created_at',
            '$status' => '$item',
            'job_status' => 'name',
            'الحالات الوظيفية' => 'الحالات الصحية',
            'الحالة الوظيفية' => 'الحالة الصحية',
            'حالة وظيفية' => 'حالة صحية',
            'حالة جديدة' => 'حالة صحية جديدة',
            'مثال: موظف، متقاعد، متعاقد...' => 'مثال: سليم، مريض، معاق...',
        ]
    ]
];

$files = ['index.blade.php', 'create.blade.php', 'edit.blade.php'];

foreach ($targets as $target) {
    $targetPath = $baseDir . $target['dir'];
    if (!is_dir($targetPath)) {
        mkdir($targetPath, 0777, true);
    }
    
    foreach ($files as $file) {
        $content = file_get_contents($sourceDir . $file);
        
        // We have a special case: <div class="..."> {{ substr($status->job_status, 0, 2) }} </div>
        // which we want to turn into {{ mb_substr($item->name, 0, 2, 'UTF-8') }}
        $content = str_replace('substr($status->job_status, 0, 2)', "mb_substr(\$item->name, 0, 2, 'UTF-8')", $content);

        // Apply replacements
        foreach ($target['replacements'] as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        // Special icon replacements:
        // Job status uses <i class="fas fa-briefcase text-2xl"></i>
        if ($target['dir'] === 'kinships') {
            $content = str_replace('fa-briefcase', 'fa-users', $content);
        } elseif ($target['dir'] === 'ideological_affiliations') {
            $content = str_replace('fa-briefcase', 'fa-praying-hands', $content);
        } elseif ($target['dir'] === 'political_affiliations') {
            $content = str_replace('fa-briefcase', 'fa-flag', $content);
        } elseif ($target['dir'] === 'health_statuses') {
            $content = str_replace('fa-briefcase', 'fa-heartbeat', $content);
        }

        file_put_contents($targetPath . '/' . $file, $content);
        echo "Created " . $targetPath . '/' . $file . "\n";
    }
}
echo "Done.\n";
