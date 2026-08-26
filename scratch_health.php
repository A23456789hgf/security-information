<?php
$baseDir = __DIR__ . '/resources/views/configuration/';
$sourceDir = $baseDir . 'blood_types/';

$targets = [
    [
        'dir' => 'health_statuses',
        'replacements' => [
            'فصائل الدم' => 'الحالات الصحية',
            'فصيلة الدم' => 'الحالة الصحية',
            'blood-types' => 'health-statuses',
            'type' => 'name',
            'إدارة الحالات الصحية' => 'إدارة الحالات الصحية',
            'إضافة فصيلة دم' => 'إضافة حالة صحية',
            'تعديل فصيلة الدم' => 'تعديل الحالة الصحية',
            '$bloodType' => '$healthStatus',
            '$bloodTypes' => '$healthStatuses'
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
        
        // Apply replacements
        foreach ($target['replacements'] as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        file_put_contents($targetPath . '/' . $file, $content);
        echo "Created " . $targetPath . '/' . $file . "\n";
    }
}
echo "Done.\n";
