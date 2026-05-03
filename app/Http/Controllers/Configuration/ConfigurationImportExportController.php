<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class ConfigurationImportExportController extends Controller
{
    /**
     * Map slugs to model classes and their primary field names.
     */
    protected function getModelConfig($type)
    {
        $configs = [
            'blood-types' => [\App\Models\BloodType::class, ['type' => 'فصيلة الدم'], ['type']],
            'qualifications' => [\App\Models\Qualification::class, ['name' => 'المؤهل العلمي'], ['name']],
            'marital-statuses' => [\App\Models\MaritalStatus::class, ['status' => 'الحالة الاجتماعية'], ['status']],
            'job-statuses' => [\App\Models\JobStatus::class, ['job_status' => 'الحالة الوظيفية'], ['job_status']],
            'ranks' => [\App\Models\Rank::class, ['rank' => 'الرتبة'], ['rank']],
            'number-classifications' => [\App\Models\NumberClassification::class, ['classification' => 'التصنيف الرقمي'], ['classification']],
            'military-districts' => [\App\Models\MilitaryDistrict::class, ['name' => 'المنطقة العسكرية'], ['name']],
            'divisions' => [\App\Models\Division::class, [
                'military_district_id' => 'المنطقة العسكرية',
                'name' => 'الشعبة'
            ], ['military_district_id', 'name']],
            'axes' => [\App\Models\Axis::class, [
                'military_district_id' => 'المنطقة العسكرية',
                'name' => 'المحور'
            ], ['military_district_id', 'name']],
            'course-types' => [\App\Models\CourseType::class, ['name' => 'نوع الدورة'], ['name']],
            'filling-agencies' => [\App\Models\FillingAgency::class, ['name' => 'جهة الرفع'], ['name']],
            'electronic-inputs' => [\App\Models\ElectronicInput::class, ['name' => 'المدخل الإلكتروني'], ['name']],
            'countries' => [\App\Models\Country::class, ['name' => 'الدولة'], ['name']],
            'governorates' => [\App\Models\Governorate::class, ['name' => 'المحافظة'], ['name']],
            'directorates' => [\App\Models\Directorate::class, [
                'governorate_id' => 'المحافظة',
                'name' => 'المديرية'
            ], ['governorate_id', 'name']],
            'sub-areas' => [\App\Models\SubArea::class, [
                'governorate_id' => 'المحافظة',
                'directorate_id' => 'المديرية',
                'name' => 'العزلة / المنطقة'
            ], ['directorate_id', 'name']],
            'villages' => [\App\Models\Village::class, [
                'governorate_id' => 'المحافظة',
                'directorate_id' => 'المديرية',
                'sub_area_id' => 'العزلة / المنطقة',
                'name' => 'القرية / الحارة'
            ], ['sub_area_id', 'name']],
            'arresting-authorities' => [\App\Models\ArrestingAuthority::class, ['name' => 'جهة الضبط'], ['name']],
            'case-types' => [\App\Models\CaseType::class, ['name' => 'نوع القضية'], ['name']],
        ];

        return $configs[$type] ?? null;
    }

    /**
     * Store import data in session for review.
     */
    /**
     * Store import data file on disk and meta in session.
     */
    public function import(Request $request, $type)
    {
        $config = $this->getModelConfig($type);
        if (!$config) {
            return response()->json(['success' => false, 'message' => 'نوع التكوين غير صالح']);
        }

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        $file = $request->file('excel_file');
        
        try {
            // Save file to disk
            $path = $file->store('imports');
            $fullPath = storage_path('app/' . $path);

            $data = Excel::toArray(new class {}, $fullPath);
            $rows = $data[0] ?? [];
            
            if (empty($rows)) {
                return response()->json(['success' => false, 'message' => 'الملف فارغ']);
            }

            // Detect headers
            $headers = [];
            while (!empty($rows)) {
                $potentialHeaders = array_shift($rows);
                if (!is_array($potentialHeaders)) continue;
                
                $validColumns = array_filter($potentialHeaders, function($h) { 
                    return !is_null($h) && trim((string)$h) !== ''; 
                });
                
                if (!empty($validColumns)) {
                    $headers = array_map(function($h) { return is_null($h) ? '' : (string)$h; }, $potentialHeaders);
                    break;
                }
            }

            if (empty($headers)) {
                return response()->json(['success' => false, 'message' => 'تعذر العثور على رؤوس أعمدة في الملف']);
            }

            session(["pending_config_import_{$type}_file" => $path]);
            session(["pending_config_import_{$type}_headers" => $headers]);

            $previewRows = array_slice($rows, 0, 5);
            $safePreview = array_map(function($row) {
                if (!is_array($row)) return [];
                return array_map(function($cell) { return is_null($cell) ? '' : (string)$cell; }, $row);
            }, $previewRows);

            return response()->json([
                'success' => true,
                'columns' => $headers,
                'preview_data' => $safePreview,
                'total_rows' => count($rows),
                'target_fields' => $config[1],
                'context' => $request->except(['excel_file', '_token'])
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'خطأ في معالجة الملف: ' . $e->getMessage()]);
        }
    }

    /**
     * Dry run of the import to show a review table (Partial for large files).
     */
    public function validateImport(Request $request, $type)
    {
        $config = $this->getModelConfig($type);
        if (!$config) return response()->json(['success' => false, 'message' => 'نوع التكوين غير صالح']);

        $modelClass = $config[0];
        $fieldsConfig = $config[1];
        $identityKeys = $config[2] ?? [];
        
        $mode = $request->input('mode', 'append');
        $mapping = $request->input('mapping');
        
        $filePath = session("pending_config_import_{$type}_file");
        $headers = session("pending_config_import_{$type}_headers");
        $context = $request->except(['mapping', 'mode', '_token']);

        if (!$filePath || !file_exists(storage_path('app/' . $filePath))) {
            return response()->json(['success' => false, 'message' => 'الملف غير موجود، يرجى إعادة رفعه.']);
        }

        // Save mapping and mode for final store
        session(["pending_config_import_{$type}_mapping" => $mapping]);
        session(["pending_config_import_{$type}_mode" => $mode]);

        try {
            $data = Excel::toArray(new class {}, storage_path('app/' . $filePath));
            $allRows = $data[0] ?? [];
            
            // Skip headers
            while (!empty($allRows)) {
                $potentialHeaders = array_shift($allRows);
                if (!is_array($potentialHeaders)) continue;
                if (!empty(array_filter($potentialHeaders))) break;
            }

            $totalRows = count($allRows);
            $previewRows = array_slice($allRows, 0, 100);

            $results = [];
            foreach ($previewRows as $index => $row) {
                $rowData = [];
                $errors = [];

                foreach ($fieldsConfig as $field => $label) {
                    $value = null;
                    if (isset($mapping[$field]) && $mapping[$field] !== '') {
                        $colIndex = $mapping[$field];
                        $value = trim((string)($row[$colIndex] ?? ''));
                    } elseif (isset($context[$field]) && $context[$field] !== '') {
                        $value = $context[$field];
                    }

                    if (empty($value)) {
                        $errors[] = "الحقل $label مفقود";
                    } else {
                        if (str_ends_with($field, '_id')) {
                            $relationTable = Str::plural(str_replace('_id', '', $field));
                            if ($field === 'military_district_id') $relationTable = 'military_districts';
                            
                            $query = \DB::table($relationTable)->where('name', $value);
                            if ($field === 'directorate_id' && isset($rowData['governorate_id'])) $query->where('governorate_id', $rowData['governorate_id']);
                            if ($field === 'sub_area_id' && isset($rowData['directorate_id'])) $query->where('directorate_id', $rowData['directorate_id']);
                            
                            $id = $query->value('id');
                            if (!$id && !is_numeric($value)) {
                                $errors[] = "$label غير موجود بجداول النظام ($value)";
                            } else {
                                $rowData[$field] = $id ?? $value;
                            }
                        } else {
                            $rowData[$field] = $value;
                        }
                    }
                }

                $currentStatus = 'ready';
                if (empty($errors)) {
                    $query = $modelClass::query();
                    if (!empty($identityKeys)) {
                        foreach ($identityKeys as $k) if (isset($rowData[$k])) $query->where($k, $rowData[$k]);
                    } else {
                        foreach ($rowData as $k => $v) $query->where($k, $v);
                    }
                    
                    $exists = $query->exists();
                    if ($mode === 'append') {
                        if ($exists) $currentStatus = 'exists';
                    } elseif ($mode === 'update') {
                        if (!$exists) { $currentStatus = 'error'; $errors[] = 'السجل غير موجود للتحديث'; }
                        else $currentStatus = 'update';
                    } elseif ($mode === 'upsert') {
                        if ($exists) $currentStatus = 'update';
                        else $currentStatus = 'ready';
                    }
                } else {
                    $currentStatus = 'error';
                }

                $results[] = [
                    'row_index' => $index,
                    'data' => $row,
                    'errors' => $errors,
                    'status' => $currentStatus,
                    'mapped_data' => $rowData
                ];
            }

            return response()->json([
                'success' => true,
                'results' => $results,
                'headers' => $headers,
                'total_rows_original' => $totalRows,
                'total_ready' => $totalRows, 
                'total_update' => 0
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'خطأ في معالجة المعاينة: ' . $e->getMessage()]);
        }
    }

    /**
     * Dispatch background job for actual import.
     */
    public function storeImport(Request $request, $type)
    {
        $filePath = session("pending_config_import_{$type}_file");
        $mapping = session("pending_config_import_{$type}_mapping");
        $mode = session("pending_config_import_{$type}_mode", 'append');

        if (!$filePath || !file_exists(storage_path('app/' . $filePath))) {
            return response()->json(['success' => false, 'message' => 'الملف غير موجود، يرجى إعادة الرفع.']);
        }

        try {
            $config = $this->getModelConfig($type);
            if (!$config) return response()->json(['success' => false, 'message' => 'نوع التكوين غير صالح']);

            // Read full data to count total rows for progress tracking
            $data = Excel::toArray(new class {}, storage_path('app/' . $filePath));
            $allRows = $data[0] ?? [];
            
            // Skip headers
            while (!empty($allRows)) {
                $potentialHeaders = array_shift($allRows);
                if (!is_array($potentialHeaders)) continue;
                if (!empty(array_filter($potentialHeaders))) break;
            }

            $totalRows = count($allRows);
            $sessionId = uniqid('import_', true);

            \App\Models\ImportProgress::create([
                'session_id' => $sessionId,
                'type' => $type,
                'total_rows' => $totalRows,
                'status' => 'pending'
            ]);

            \App\Jobs\ProcessConfigurationImportJob::dispatch(
                $sessionId,
                $filePath,
                $mapping,
                $mode,
                [
                    'model' => $config[0],
                    'fields' => $config[1],
                    'identity' => $config[2] ?? [],
                    'routeName' => "configuration.{$type}"
                ]
            );

            session()->forget([
                "pending_config_import_{$type}_file",
                "pending_config_import_{$type}_headers",
                "pending_config_import_{$type}_mapping",
                "pending_config_import_{$type}_mode"
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم بدء الاستيراد في الخلفية. جاري المعالجة...',
                'session_id' => $sessionId,
                'is_async' => true
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء بدء الاستيراد: ' . $e->getMessage()]);
        }
    }

    /**
     * Export all data to Excel.
     */
    public function export(Request $request, $type)
    {
        $config = $this->getModelConfig($type);
        if (!$config) abort(404);

        $modelClass = $config[0];
        $fieldsConfig = $config[1];

        $query = $modelClass::query();
        
        // Apply filters from URL context
        $context = $request->except(['_token']);
        foreach ($context as $key => $value) {
            if ($value !== null && $value !== '' && \Schema::hasColumn((new $modelClass)->getTable(), $key)) {
                $query->where($key, $value);
            }
        }

        $items = $query->get();

        return Excel::download(new class($items, $fieldsConfig) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            protected $items, $fieldsConfig;
            public function __construct($items, $fieldsConfig) {
                $this->items = $items;
                $this->fieldsConfig = $fieldsConfig;
            }
            public function collection() {
                return $this->items->map(function($item) {
                    $row = [];
                    foreach ($this->fieldsConfig as $field => $label) {
                        if (str_ends_with($field, '_id')) {
                            $relation = Str::camel(str_replace('_id', '', $field));
                            // Special case for military_district_id -> district relation
                            if ($field === 'military_district_id') $relation = 'district';
                            
                            $row[] = $item->{$relation}->name ?? '';
                        } else {
                            $row[] = $item->{$field};
                        }
                    }
                    return $row;
                });
            }
            public function headings(): array {
                return array_values($this->fieldsConfig);
            }
        }, "export_{$type}_" . now()->format('Y-m-d') . ".xlsx");
    }

    /**
     * Download template.
     */
    public function template($type)
    {
        $config = $this->getModelConfig($type);
        if (!$config) abort(404);

        $labels = array_values($config[1]);

        return Excel::download(new class($labels) implements \Maatwebsite\Excel\Concerns\WithHeadings {
            protected $labels;
            public function __construct($labels) { $this->labels = $labels; }
            public function headings(): array { return $this->labels; }
        }, "template_{$type}.xlsx");
    }

    /**
     * Get import progress status
     */
    public function getImportProgress(Request $request)
    {
        $sessionId = $request->input('session_id');
        if (!$sessionId) {
            return response()->json(['success' => false, 'message' => 'معرّف الجلسة مفقود']);
        }

        $progress = \App\Models\ImportProgress::where('session_id', $sessionId)->first();
        if (!$progress) {
            return response()->json(['success' => false, 'message' => 'لم يتم العثور على بيانات الاستيراد']);
        }

        return response()->json([
            'success' => true,
            'progress_percentage' => $progress->progress_percentage,
            'processed_rows' => $progress->processed_rows,
            'total_rows' => $progress->total_rows,
            'created_count' => $progress->created_count,
            'updated_count' => $progress->updated_count,
            'failed_count' => $progress->failed_count,
            'status' => $progress->status,
            'errors' => $progress->errors ?? []
        ]);
    }
}
