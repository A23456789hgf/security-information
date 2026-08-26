<?php
$baseDir = __DIR__ . '/resources/views/configuration/';
$sourceDir = $baseDir . 'blood_types/';

$targets = [
    [
        'dir' => 'kinships',
        'replacements' => [
            'فصائل الدم' => 'صلات القرابة',
            'فصيلة الدم' => 'صلة القرابة',
            'blood-types' => 'kinships',
            'type' => 'name',
            'إدارة فصائل الدم' => 'إدارة صلات القرابة',
            'إضافة فصيلة دم' => 'إضافة صلة قرابة',
            'تعديل فصيلة الدم' => 'تعديل صلة القرابة',
        ]
    ],
    [
        'dir' => 'ideological_affiliations',
        'replacements' => [
            'فصائل الدم' => 'الانتماءات العقائدية',
            'فصيلة الدم' => 'الانتماء العقائدي',
            'blood-types' => 'ideological-affiliations',
            'type' => 'name',
            'إدارة فصائل الدم' => 'إدارة الانتماءات العقائدية',
            'إضافة فصيلة دم' => 'إضافة انتماء عقائدي',
            'تعديل فصيلة الدم' => 'تعديل الانتماء العقائدي',
        ]
    ],
    [
        'dir' => 'political_affiliations',
        'replacements' => [
            'فصائل الدم' => 'الانتماءات السياسية',
            'فصيلة الدم' => 'الانتماء السياسي',
            'blood-types' => 'political-affiliations',
            'type' => 'name',
            'إدارة فصائل الدم' => 'إدارة الانتماءات السياسية',
            'إضافة فصيلة دم' => 'إضافة انتماء سياسي',
            'تعديل فصيلة الدم' => 'تعديل الانتماء السياسي',
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
        
        // Also ensure the singular variable replacements
        // In blood_types, the variable is $bloodType. So we replace $bloodType with $kinship, $ideologicalAffiliation, $politicalAffiliation
        if ($target['dir'] === 'kinships') {
            $content = str_replace('$bloodType', '$kinship', $content);
        } elseif ($target['dir'] === 'ideological_affiliations') {
            $content = str_replace('$bloodType', '$ideologicalAffiliation', $content);
        } elseif ($target['dir'] === 'political_affiliations') {
            $content = str_replace('$bloodType', '$politicalAffiliation', $content);
        }

        // Replace plural variables
        // $bloodTypes -> $kinships, $ideologicalAffiliations, $politicalAffiliations
        if ($target['dir'] === 'kinships') {
            $content = str_replace('$bloodTypes', '$kinships', $content);
        } elseif ($target['dir'] === 'ideological_affiliations') {
            $content = str_replace('$bloodTypes', '$ideologicalAffiliations', $content);
        } elseif ($target['dir'] === 'political_affiliations') {
            $content = str_replace('$bloodTypes', '$politicalAffiliations', $content);
        }

        file_put_contents($targetPath . '/' . $file, $content);
        echo "Created " . $targetPath . '/' . $file . "\n";
    }
}
echo "Done.\n";
