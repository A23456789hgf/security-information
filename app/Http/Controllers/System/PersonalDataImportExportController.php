<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\PersonalData;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ImportProgress;
use App\Traits\HandlesConfigurationImportExport;

class PersonalDataImportExportController extends Controller
{
    use HandlesConfigurationImportExport;

    protected $modelClass = PersonalData::class;
    protected $routeName  = 'system.personal-data';

    /**
     * Enhanced Arabic normalization to handle variations and prefixes
     */
    public static function normalizeString($s)
    {
        $s = trim((string)$s);
        $s = preg_replace('/\s+/', ' ', $s);
        $s = str_replace(['أ','إ','آ','ٱ'], 'ا', $s);
        $s = str_replace('ة', 'ه', $s);
        $s = str_replace('ى', 'ي', $s);
        $s = str_replace(['محافظه ', 'مديريه ', 'عزله ', 'قريه ', 'منطقه '], '', $s);
        return trim($s);
    }

    /**
     * Override to provide the specific PersonalData fields configuration.
     */
    protected function getImportConfig()
    {
        return [
            'fields' => [
                'name' => 'الاسم الكامل',
                'jihadi_name' => 'الاسم الجهادي',
                'national_id_number' => 'رقم الهوية الوطنية',
                'date_of_birth_gregorian' => 'تاريخ الميلاد ميلادي',
                'date_of_birth_hijri' => 'تاريخ الميلاد هجري',
                'blood_type_id' => 'فصيلة الدم',
                'marital_status_id' => 'الحالة الاجتماعية',
                'qualification_id' => 'المؤهل العلمي',
                'number_classification_id' => 'التصنيف الرقمي',
                'job_status_id' => 'الحالة الوظيفية',
                'rank_id' => 'الرتبة',
                'military_district_id' => 'المنطقة العسكرية',
                'division_id' => 'الشعبة',
                'place_of_birth_country_id' => 'دولة الميلاد',
                'place_of_birth_governorate_id' => 'محافظة الميلاد',
                'place_of_birth_directorate_id' => 'مديرية الميلاد',
                'place_of_birth_sub_area_id' => 'عزلة الميلاد',
                'place_of_birth_village_id' => 'قرية الميلاد',
                'country_id' => 'الدولة الحالية',
                'governorate_id' => 'المحافظة الحالية',
                'directorate_id' => 'المديرية الحالية',
                'sub_area_id' => 'العزلة الحالية',
                'village_id' => 'القرية الحالية',
                'unit' => 'الوحدة / القوة',
                'job' => 'الوظيفة',
                'workplace' => 'مكان العمل',
                'job_specialty' => 'التخصص الوظيفي',
                'jihadist_number' => 'الرقم الجهادي',
                'financial_status_id' => 'الحالة المالية',
            ],
            'identity' => ['national_id_number', 'name']
        ];
    }

    /**
     * Validate the mapped data for preview with fuzzy Arabic matching.
     */
    public function validateImport(Request $request)
    {
        $slug = $this->getResourceSlug();
        $filePath = session("pending_config_import_{$slug}_file");
        $headers = session("pending_config_import_{$slug}_headers");
        $startRow = session("pending_config_import_{$slug}_start_row", 2);
        
        if (!$filePath || !file_exists(storage_path('app/' . $filePath))) {
            return response()->json(['success' => false, 'message' => 'الملف غير موجود.']);
        }

        $mapping = $request->input('mapping');
        $mode = $request->input('mode', 'append');
        session(["pending_config_import_{$slug}_mapping" => $mapping]);
        session(["pending_config_import_{$slug}_mode" => $mode]);

        try {
            $import = new class implements \Maatwebsite\Excel\Concerns\ToArray {
                public $data = [];
                public function array(array $array) { $this->data = array_slice($array, 0, 150); }
            };
            Excel::import($import, storage_path('app/' . $filePath));
            $allRows = $import->data;
            
            $headerRowIndex = $startRow - 2;
            $previewRows = array_slice($allRows, $headerRowIndex + 1, 100);

            $fieldsConfig = $this->getImportConfig()['fields'];

            // Pre-load all lookup tables with fuzzy-normalized keys
            $normalize = function($s) {
                return self::normalizeString($s);
            };

            $tableMapping = [
                'blood_type_id'            => ['table' => 'blood_types',           'column' => 'type'],
                'marital_status_id'        => ['table' => 'marital_statuses',       'column' => 'status'],
                'qualification_id'         => ['table' => 'qualifications',         'column' => 'name'],
                'number_classification_id' => ['table' => 'number_classifications', 'column' => 'classification'],
                'job_status_id'            => ['table' => 'job_statuses',           'column' => 'job_status'],
                'rank_id'                  => ['table' => 'ranks',                  'column' => 'rank'],
                'military_district_id'     => ['table' => 'military_districts',     'column' => 'name'],
                'division_id'              => ['table' => 'divisions',              'column' => 'name'],
                'place_of_birth_country_id'=> ['table' => 'countries',              'column' => 'name'],
                'country_id'               => ['table' => 'countries',              'column' => 'name'],
                'financial_status_id'      => ['table' => 'financial_statuses',     'column' => 'name'],
            ];

            // Location tables (need hierarchical resolution)
            $locationFields = [
                'governorate_id', 'directorate_id', 'sub_area_id', 'village_id',
                'place_of_birth_governorate_id', 'place_of_birth_directorate_id',
                'place_of_birth_sub_area_id', 'place_of_birth_village_id',
            ];

            // Pre-load caches with fuzzy keys
            $caches = [];
            foreach ($tableMapping as $field => $m) {
                $table = $m['table'];
                if (!isset($caches[$table])) {
                    $caches[$table] = \DB::table($table)->get(['id', $m['column']])
                        ->mapWithKeys(fn($r) => [$normalize($r->{$m['column']}) => $r->id])
                        ->toArray();
                }
            }

            // Pre-load location lookups (full, not hierarchical for preview)
            $govCache  = \DB::table('governorates')->get(['id','name']);
            $dirCache  = \DB::table('directorates')->get(['id','name','governorate_id']);
            $subCache  = \DB::table('sub_areas')->get(['id','name','directorate_id']);
            $vilCache  = \DB::table('villages')->get(['id','name','sub_area_id']);

            $resolveLocation = function($field, $val, $rowData) use ($normalize, $govCache, $dirCache, $subCache, $vilCache) {
                $norm = $normalize($val);
                if (in_array($field, ['governorate_id','place_of_birth_governorate_id'])) {
                    foreach ($govCache as $g) if ($normalize($g->name) === $norm || $g->id == $val) return $g->id;
                }
                if (in_array($field, ['directorate_id','place_of_birth_directorate_id'])) {
                    $parentKey = str_contains($field,'place_of_birth') ? 'place_of_birth_governorate_id' : 'governorate_id';
                    $parentId = $rowData[$parentKey] ?? null;
                    foreach ($dirCache as $d) {
                        if ($normalize($d->name) === $norm || $d->id == $val) {
                            if (!$parentId || $d->governorate_id == $parentId) return $d->id;
                        }
                    }
                }
                if (in_array($field, ['sub_area_id','place_of_birth_sub_area_id'])) {
                    $parentKey = str_contains($field,'place_of_birth') ? 'place_of_birth_directorate_id' : 'directorate_id';
                    $parentId = $rowData[$parentKey] ?? null;
                    foreach ($subCache as $s) {
                        if ($normalize($s->name) === $norm || $s->id == $val) {
                            if (!$parentId || $s->directorate_id == $parentId) return $s->id;
                        }
                    }
                }
                if (in_array($field, ['village_id','place_of_birth_village_id'])) {
                    $parentKey = str_contains($field,'place_of_birth') ? 'place_of_birth_sub_area_id' : 'sub_area_id';
                    $parentId = $rowData[$parentKey] ?? null;
                    foreach ($vilCache as $v) {
                        if ($normalize($v->name) === $norm || $v->id == $val) {
                            if (!$parentId || $v->sub_area_id == $parentId) return $v->id;
                        }
                    }
                }
                return null;
            };

            $results = [];
            foreach ($previewRows as $index => $row) {
                $rowData = [];
                $errors = [];

                // Non-location fields first
                foreach ($fieldsConfig as $field => $label) {
                    if (in_array($field, $locationFields)) continue;
                    $colIndex = $mapping[$field] ?? null;
                    if ($colIndex === null || !isset($row[$colIndex])) continue;
                    $val = trim((string)$row[$colIndex]);
                    if (empty($val)) continue;

                    if (str_ends_with($field, '_id') && isset($tableMapping[$field])) {
                        $table = $tableMapping[$field]['table'];
                        $id = $caches[$table][$normalize($val)] ?? (is_numeric($val) ? $val : null);
                        if ($id) {
                            $rowData[$field] = $id;
                        } else {
                            $errors[] = "$label: لم يتم العثور على ($val)";
                        }
                    } else {
                        $rowData[$field] = $val;
                    }
                }

                // Location fields in hierarchy order
                foreach ($locationFields as $field) {
                    if (!isset($fieldsConfig[$field])) continue;
                    $colIndex = $mapping[$field] ?? null;
                    if ($colIndex === null || !isset($row[$colIndex])) continue;
                    $val = trim((string)$row[$colIndex]);
                    if (empty($val)) continue;

                    $id = $resolveLocation($field, $val, $rowData);
                    if ($id) {
                        $rowData[$field] = $id;
                    } else {
                        $errors[] = $fieldsConfig[$field] . ": لم يتم العثور على ($val)";
                    }
                }

                $results[] = [
                    'row_index' => $index,
                    'data'      => $row,
                    'errors'    => $errors,
                    'status'    => empty($errors) ? 'ready' : 'error',
                ];
            }

            return response()->json([
                'success' => true,
                'results' => $results,
                'headers' => $headers
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'خطأ معاينة: ' . $e->getMessage()]);
        }
    }

    protected function getResourceSlug(): string
    {
        return 'personal_data';
    }

    /**
     * Show the import form.
     */
    public function importForm()
    {
        $config = $this->getImportConfig();
        return view('system.personal_data.import', [
            'type' => 'personal_data',
            'title' => 'البيانات الشخصية',
            'fields' => $config['fields'],
            'routeName' => $this->routeName
        ]);
    }

    /**
     * Start the import process in background.
     */
    public function importStore(Request $request)
    {
        $slug = $this->getResourceSlug();
        $filePath = session("pending_config_import_{$slug}_file");
        $mapping = session("pending_config_import_{$slug}_mapping");
        $mode = session("pending_config_import_{$slug}_mode", 'append');
        $startRow = session("pending_config_import_{$slug}_start_row", 2);

        if (!$filePath || !file_exists(storage_path('app/' . $filePath))) {
            return response()->json(['success' => false, 'message' => 'الملف غير موجود، حاول التحديث وإعادة الرفع.']);
        }

        try {
            $sessionId = uniqid('import_pd_', true);

            ImportProgress::create([
                'session_id' => $sessionId,
                'type' => $slug,
                'status' => 'processing',
                'processed_rows' => 0,
                'total_rows' => 0,
            ]);

            $config = [
                'model' => $this->modelClass,
                'fields' => $this->getImportConfig()['fields'],
                'identity' => $this->getImportConfig()['identity'],
                'routeName' => $this->routeName
            ];

            // Allow long-running import for large files
            set_time_limit(0);

            $importer = new class($sessionId, $mapping, $mode, $config, $startRow) implements 
                \Maatwebsite\Excel\Concerns\ToCollection, 
                \Maatwebsite\Excel\Concerns\WithChunkReading, 
                \Maatwebsite\Excel\Concerns\WithStartRow 
            {
                private $sessionId, $mapping, $mode, $config, $progress, $startRow;
                private $created = 0, $updated = 0, $failed = 0, $errors = [], $caches = [];

                public function __construct($sId, $map, $mode, $cfg, $sRow) {
                    $this->sessionId = $sId; $this->mapping = $map; $this->mode = $mode;
                    $this->config = $cfg; $this->startRow = $sRow;
                    $this->progress = \App\Models\ImportProgress::where('session_id', $this->sessionId)->first();
                }
                
                public function startRow(): int { return $this->startRow; }
                public function chunkSize(): int { return 500; }
                
                public function collection(\Illuminate\Support\Collection $rows) {
                    $fieldsConfig = $this->config['fields'];
                    $identityKeys = $this->config['identity'] ?? ['national_id_number'];
                    $locationFields = ['governorate_id','directorate_id','sub_area_id','village_id',
                        'place_of_birth_governorate_id','place_of_birth_directorate_id','place_of_birth_sub_area_id','place_of_birth_village_id'];

                    foreach ($rows as $rowIndex => $row) {
                        $rowArray = $row->toArray();
                        if (empty(array_filter($rowArray))) continue;

                        $rowData = [];
                        $rowErrors = [];

                        foreach ($fieldsConfig as $field => $label) {
                            if (in_array($field, $locationFields)) continue;
                            $colIndex = $this->mapping[$field] ?? null;
                            if ($colIndex === null || !isset($rowArray[$colIndex])) continue;
                            
                            $val = trim((string)$rowArray[$colIndex]);
                            if (empty($val)) continue;

                            if (str_ends_with($field, '_id')) {
                                $id = $this->resolveId($field, $val);
                                if ($id) $rowData[$field] = $id;
                                else $rowErrors[] = "$label غير موجود ($val)";
                            } else {
                                $rowData[$field] = $val;
                            }
                        }

                        foreach ($locationFields as $field) {
                            if (!isset($fieldsConfig[$field])) continue;
                            $colIndex = $this->mapping[$field] ?? null;
                            if ($colIndex === null || !isset($rowArray[$colIndex])) continue;
                            
                            $val = trim((string)$rowArray[$colIndex]);
                            if (empty($val)) continue;

                            $id = $this->resolveLocationId($field, $val, $rowData);
                            if ($id) $rowData[$field] = $id;
                            else $rowErrors[] = "{$fieldsConfig[$field]} غير موجود ($val)";
                        }

                        if (empty($rowErrors)) {
                            try { $this->processRecord($identityKeys, $rowData); } 
                            catch (\Exception $e) {
                                $this->failed++;
                                if(count($this->errors) < 100) $this->errors[] = "خطأ حفظ في الصف " . ($rowIndex + $this->startRow) . ": " . $e->getMessage();
                            }
                        } else {
                            $this->failed++;
                            if(count($this->errors) < 100) $this->errors[] = "الصف " . ($rowIndex + $this->startRow) . ": " . implode(", ", $rowErrors);
                        }
                    }
                    if ($this->progress) {
                        $this->progress->update([
                            'processed_rows' => $this->created + $this->updated + $this->failed,
                            'created_count' => $this->created, 'updated_count' => $this->updated, 'failed_count' => $this->failed
                        ]);
                    }
                }

                private function resolveId($field, $val) {
                    $tableMapping = [
                        'blood_type_id' => ['table' => 'blood_types', 'column' => 'type'],
                        'marital_status_id' => ['table' => 'marital_statuses', 'column' => 'status'],
                        'qualification_id' => ['table' => 'qualifications', 'column' => 'name'],
                        'number_classification_id' => ['table' => 'number_classifications', 'column' => 'classification'],
                        'job_status_id' => ['table' => 'job_statuses', 'column' => 'job_status'],
                        'rank_id' => ['table' => 'ranks', 'column' => 'rank'],
                        'military_district_id' => ['table' => 'military_districts', 'column' => 'name'],
                        'division_id' => ['table' => 'divisions', 'column' => 'name'],
                        'place_of_birth_country_id' => ['table' => 'countries', 'column' => 'name'],
                        'country_id' => ['table' => 'countries', 'column' => 'name'],
                        'financial_status_id' => ['table' => 'financial_statuses', 'column' => 'name'],
                    ];
                    if (!isset($tableMapping[$field])) return null;
                    $t = $tableMapping[$field]['table']; $c = $tableMapping[$field]['column'];
                    if (!isset($this->caches[$t])) {
                        $this->caches[$t] = \Illuminate\Support\Facades\DB::table($t)->get(['id', $c])->mapWithKeys(function($item) use ($c) {
                            return [\App\Http\Controllers\System\PersonalDataImportExportController::normalizeString($item->$c) => $item->id];
                        })->toArray();
                    }
                    $normVal = \App\Http\Controllers\System\PersonalDataImportExportController::normalizeString($val);
                    return $this->caches[$t][$normVal] ?? (is_numeric($val) ? \Illuminate\Support\Facades\DB::table($t)->where('id', $val)->value('id') : null);
                }

                private function resolveLocationId($field, $val, $rowData) {
                    $normVal = \App\Http\Controllers\System\PersonalDataImportExportController::normalizeString($val);
                    if ($field === 'governorate_id' || $field === 'place_of_birth_governorate_id') {
                        if (!isset($this->caches['governorates'])) {
                            $this->caches['governorates'] = \Illuminate\Support\Facades\DB::table('governorates')->get(['id', 'name'])->mapWithKeys(function($i) {
                                return [\App\Http\Controllers\System\PersonalDataImportExportController::normalizeString($i->name) => $i->id];
                            })->toArray();
                        }
                        return $this->caches['governorates'][$normVal] ?? (is_numeric($val) ? $val : null);
                    }
                    
                    $tables = [
                        'directorate_id' => 'directorates', 'place_of_birth_directorate_id' => 'directorates',
                        'sub_area_id' => 'sub_areas', 'place_of_birth_sub_area_id' => 'sub_areas',
                        'village_id' => 'villages', 'place_of_birth_village_id' => 'villages'
                    ];
                    $pgMap = [
                        'directorate_id' => 'governorate_id', 'place_of_birth_directorate_id' => 'place_of_birth_governorate_id',
                        'sub_area_id' => 'directorate_id', 'place_of_birth_sub_area_id' => 'place_of_birth_directorate_id',
                        'village_id' => 'sub_area_id', 'place_of_birth_village_id' => 'place_of_birth_sub_area_id'
                    ];
                    $dbPgMap = [
                        'directorate_id' => 'governorate_id', 'place_of_birth_directorate_id' => 'governorate_id',
                        'sub_area_id' => 'directorate_id', 'place_of_birth_sub_area_id' => 'directorate_id',
                        'village_id' => 'sub_area_id', 'place_of_birth_village_id' => 'sub_area_id'
                    ];

                    $table = $tables[$field];
                    $parentKey = $pgMap[$field];
                    $dbParentCol = $dbPgMap[$field];

                    $parentId = $rowData[$parentKey] ?? null;
                    if (!isset($this->caches[$table])) {
                        $this->caches[$table] = \Illuminate\Support\Facades\DB::table($table)->get(['id', 'name', $dbParentCol]);
                    }
                    foreach ($this->caches[$table] as $row) {
                        if (\App\Http\Controllers\System\PersonalDataImportExportController::normalizeString($row->name) === $normVal || $row->id == $val) {
                            if (!$parentId || $row->$dbParentCol == $parentId) return $row->id;
                        }
                    }
                    return null;
                }

                private function processRecord($identityKeys, $rowData) {
                    $query = \App\Models\PersonalData::query();
                    foreach ($identityKeys as $k) {
                        if (!isset($rowData[$k])) continue;
                        $query->where($k, $rowData[$k]);
                    }
                    $record = $query->first();

                    if ($this->mode === 'append' && $record) return;
                    if ($this->mode === 'update' && !$record) { $this->failed++; return; }

                    if ($record) {
                        $record->update($rowData);
                        $this->updated++;
                    } else {
                        \App\Models\PersonalData::create($rowData);
                        $this->created++;
                    }
                }

                public function __destruct() {
                    if ($this->progress) {
                        $this->progress->update([
                            'status' => 'completed',
                            'processed_rows' => $this->created + $this->updated + $this->failed,
                            'created_count' => $this->created, 'updated_count' => $this->updated, 'failed_count' => $this->failed,
                            'errors' => array_unique($this->errors)
                        ]);
                    }
                }
            };

            // Perform Synchronous Import
            Excel::import($importer, storage_path('app/' . $filePath));

            $progress = ImportProgress::where('session_id', $sessionId)->first();

            session()->forget([
                "pending_config_import_{$slug}_file",
                "pending_config_import_{$slug}_headers",
                "pending_config_import_{$slug}_mapping",
                "pending_config_import_{$slug}_mode",
                "pending_config_import_{$slug}_start_row"
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم استيراد البيانات الشخصية بنجاح.',
                'session_id' => $sessionId,
                'is_async' => false,
                'results' => [
                    'processed' => $progress->processed_rows,
                    'created' => $progress->created_count,
                    'updated' => $progress->updated_count,
                    'failed' => $progress->failed_count,
                    'errors' => $progress->errors
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء بدء الاستيراد: ' . $e->getMessage()]);
        }
    }

    /**
     * Show export form.
     */
    public function exportForm()
    {
        return view('system.personal_data.export');
    }

    /**
     * Perform the export.
     */
    public function export(Request $request)
    {
        $query = PersonalData::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('jihadi_name', 'like', "%$search%")
                  ->orWhere('national_id_number', 'like', "%$search%");
            });
        }

        if ($request->filled('governorate_id')) {
            $query->where('governorate_id', $request->governorate_id);
        }

        $items = $query->get();
        $fields = $this->getImportConfig()['fields'];

        return Excel::download(new class($items, $fields) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithMapping {
            private $items;
            private $fields;

            public function __construct($items, $fields) {
                $this->items = $items;
                $this->fields = $fields;
            }

            public function collection() {
                return $this->items;
            }

            public function headings(): array {
                return array_values($this->fields);
            }

            public function map($item): array {
                $row = [];
                foreach (array_keys($this->fields) as $field) {
                    $value = $item->$field;
                    if (str_ends_with($field, '_id')) {
                        $relation = Str::camel(str_replace(['place_of_birth_', '_id'], ['', ''], $field));
                        // Fix for place of birth relations which might need manual mapping if camelCase doesn't match
                        if (str_starts_with($field, 'place_of_birth_')) {
                            $base = str_replace('place_of_birth_', '', $field);
                            $relation = 'placeOfBirth' . Str::studly(str_replace('_id', '', $base));
                        }

                        if (method_exists($item, $relation) && $item->$relation) {
                            $value = $item->$relation->name ?? $item->$relation->type ?? $item->$relation->status ?? $value;
                        }
                    }
                    $row[] = $value;
                }
                return $row;
            }
        }, 'personal_data_export_' . date('Y-m-d') . '.xlsx');
    }
}
