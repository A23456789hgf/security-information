<?php

namespace App\Http\Controllers\Configuration;

use App\Models\Village;
use App\Models\Governorate;
use App\Models\Directorate;
use App\Models\SubArea;
use Illuminate\Http\Request;

class VillageController extends BaseConfigurationController
{
    use \App\Traits\HandlesConfigurationImportExport;

    protected function normalizeArabic($str)
    {
        $str = trim((string)$str);
        $str = str_replace(['أ', 'إ', 'آ'], 'ا', $str);
        $str = str_replace('ة', 'ه', $str);
        $str = str_replace('ى', 'ي', $str);
        return $str;
    }

    protected $modelClass        = Village::class;
    protected $viewPath          = 'configuration.village';
    protected $routeName         = 'configuration.villages';
    protected $field             = 'name';
    protected $variableName      = 'village';
    protected $pluralVariableName = 'villages';

    public function index(Request $request)
    {
        $perPage  = $request->input('per_page', 20);
        $villages = Village::with(['governorate', 'directorate', 'subArea'])
            ->when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $routeName = $this->routeName;
        return view($this->viewPath . '.index', compact('villages', 'routeName'));
    }

    public function create()
    {
        $governorates = Governorate::all();
        return view($this->viewPath . '.create', compact('governorates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'governorate_id' => 'required|exists:governorates,id',
            'directorate_id' => 'required|exists:directorates,id',
            'sub_area_id'    => 'required|exists:sub_areas,id',
            'name'           => 'required|string|max:255|unique:villages,name,NULL,id,sub_area_id,' . $request->sub_area_id,
        ]);

        Village::create($request->all());
        return redirect()->route($this->routeName . '.index')->with('success', 'تمت الإضافة بنجاح.');
    }

    public function edit($id)
    {
        $village      = Village::findOrFail($id);
        $governorates = Governorate::all();
        $directorates = Directorate::where('governorate_id', $village->governorate_id)->get();
        $subAreas     = SubArea::where('directorate_id', $village->directorate_id)->get();
        return view($this->viewPath . '.edit', compact('village', 'governorates', 'directorates', 'subAreas'));
    }

    public function update(Request $request, $id)
    {
        $village = Village::findOrFail($id);
        $request->validate([
            'governorate_id' => 'required|exists:governorates,id',
            'directorate_id' => 'required|exists:directorates,id',
            'sub_area_id'    => 'required|exists:sub_areas,id',
            'name'           => 'required|string|max:255|unique:villages,name,' . $id . ',id,sub_area_id,' . $request->sub_area_id,
        ]);

        $village->update($request->all());
        return redirect()->route($this->routeName . '.index')->with('success', 'تم التعديل بنجاح.');
    }

    public function destroy($id)
    {
        Village::findOrFail($id)->delete();
        return redirect()->route($this->routeName . '.index')->with('success', 'تم الحذف بنجاح.');
    }

    // AJAX helper for dependent dropdowns
    public function getDirectorates($governorate_id)
    {
        $directorates = Directorate::where('governorate_id', $governorate_id)->pluck('name', 'id');
        return response()->json($directorates);
    }

    public function getSubAreas($directorate_id)
    {
        $subAreas = SubArea::where('directorate_id', $directorate_id)->pluck('name', 'id');
        return response()->json($subAreas);
    }

    protected function getImportConfig()
    {
        return [
            'fields' => [
                'governorate_id' => 'المحافظة',
                'directorate_id' => 'المديرية',
                'sub_area_id' => 'العزلة / المنطقة',
                'name' => 'القرية / الحارة'
            ],
            'identity' => ['sub_area_id', 'name']
        ];
    }

    public function fastEntry()
    {
        $governorates = Governorate::all();
        return view($this->viewPath . '.fast_entry', compact('governorates'));
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'governorate_id' => 'required|exists:governorates,id',
            'directorate_id' => 'required|exists:directorates,id',
            'sub_area_id'    => 'required|exists:sub_areas,id',
            'villages'      => 'required|array',
            'villages.*'    => 'required|string|max:255',
        ]);

        $governorate_id = $request->governorate_id;
        $directorate_id = $request->directorate_id;
        $sub_area_id    = $request->sub_area_id;
        $names          = $request->villages;

        $results = ['success' => 0, 'failed' => 0, 'errors' => []];

        \DB::transaction(function () use ($governorate_id, $directorate_id, $sub_area_id, $names, &$results) {
            foreach ($names as $name) {
                $name = trim($name);
                if (empty($name)) continue;

                try {
                    // Check uniqueness within the same sub_area
                    $exists = Village::where('sub_area_id', $sub_area_id)->where('name', $name)->exists();
                    if ($exists) {
                        throw new \Exception("القرية '$name' موجودة مسبقاً في هذه المنطقة");
                    }

                    Village::create([
                        'governorate_id' => $governorate_id,
                        'directorate_id' => $directorate_id,
                        'sub_area_id'    => $sub_area_id,
                        'name'           => $name
                    ]);
                    $results['success']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = $e->getMessage();
                }
            }
        });

        return response()->json([
            'success' => true,
            'results' => $results,
            'message' => "تمت معالجة " . count($names) . " سجلات (بنجاح: {$results['success']}, فشل: {$results['failed']})"
        ]);
    }

    /**
     * Override validateImport to add fuzzy Arabic matching for relations
     */
    public function validateImport(Request $request)
    {
        $config = $this->getImportConfig();
        $fieldsConfig = $config['fields'];
        $identityKeys = $config['identity'] ?? [];
        $slug = 'villages';
        
        $mode = $request->input('mode', 'append');
        $mapping = $request->input('mapping');
        
        $filePath = session("pending_config_import_{$slug}_file");
        $headers = session("pending_config_import_{$slug}_headers");
        $context = $request->except(['mapping', 'mode', '_token']);

        if (!$filePath || !file_exists(storage_path('app/' . $filePath))) {
            return response()->json(['success' => false, 'message' => 'الملف غير موجود، يرجى إعادة رفعه.']);
        }

        session(["pending_config_import_{$slug}_mapping" => $mapping]);
        session(["pending_config_import_{$slug}_mode" => $mode]);

        try {
            $import = new class implements \Maatwebsite\Excel\Concerns\ToArray, \Maatwebsite\Excel\Concerns\WithLimit {
                public $data = [];
                public function array(array $array) { $this->data = $array; }
                public function limit(): int { return 150; }
            };
            \Maatwebsite\Excel\Facades\Excel::import($import, storage_path('app/' . $filePath));
            $allRows = $import->data;
            
            $startRow = session("pending_config_import_{$slug}_start_row", 2);
            $headerRowIndex = $startRow - 2;
            $previewRows = array_slice($allRows, $headerRowIndex + 1, 100);

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
                            $relationTable = \Illuminate\Support\Str::plural(str_replace('_id', '', $field));
                            
                            // Fuzzy matching for relations
                            $normalizedValue = $this->normalizeArabic($value);
                            $items = \DB::table($relationTable)->get(['id', 'name']);
                            
                            $id = null;
                            foreach ($items as $item) {
                                if ($this->normalizeArabic($item->name) === $normalizedValue || $item->id == $value) {
                                    $id = $item->id;
                                    break;
                                }
                            }

                            if ($field === 'directorate_id' && $id && isset($rowData['governorate_id'])) {
                                $isValid = \DB::table('directorates')->where('id', $id)->where('governorate_id', $rowData['governorate_id'])->exists();
                                if (!$isValid) $id = null;
                            }
                            if ($field === 'sub_area_id' && $id && isset($rowData['directorate_id'])) {
                                $isValid = \DB::table('sub_areas')->where('id', $id)->where('directorate_id', $rowData['directorate_id'])->exists();
                                if (!$isValid) $id = null;
                            }
                            
                            if (!$id) {
                                $errors[] = "$label غير موجود أو غير مرتبط بشكل صحيح ($value)";
                            } else {
                                $rowData[$field] = $id;
                            }
                        } else {
                            $rowData[$field] = $value;
                        }
                    }
                }

                $currentStatus = 'ready';
                if (empty($errors)) {
                    $query = Village::query();
                    foreach ($identityKeys as $k) {
                        if (isset($rowData[$k])) $query->where($k, $rowData[$k]);
                    }
                    
                    $exists = $query->exists();
                    if ($mode === 'append' && $exists) $currentStatus = 'exists';
                    elseif ($mode === 'update' && !$exists) { $currentStatus = 'error'; $errors[] = 'السجل غير موجود للتحديث'; }
                    elseif ($mode === 'upsert' && $exists) $currentStatus = 'update';
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
                'total_rows_original' => '...',
                'total_ready' => '...',
                'total_update' => 0
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'خطأ في معالجة المعاينة: ' . $e->getMessage()]);
        }
    }

    public function importStore(Request $request)
    {
        $slug = 'villages';
        $filePath = session("pending_config_import_{$slug}_file");
        $mapping = session("pending_config_import_{$slug}_mapping");
        $mode = session("pending_config_import_{$slug}_mode", 'append');
        $startRow = session("pending_config_import_{$slug}_start_row", 2);

        if (!$filePath || !file_exists(storage_path('app/' . $filePath))) {
            return response()->json(['success' => false, 'message' => 'الملف غير موجود، حاول التحديث وإعادة الرفع.']);
        }

        try {
            $sessionId = uniqid('import_', true);
            \App\Models\ImportProgress::create([
                'session_id' => $sessionId,
                'type' => $slug,
                'status' => 'processing',
                'processed_rows' => 0,
                'total_rows' => 0,
            ]);

            $config = [
                'model' => $this->modelClass,
                'fields' => $this->getImportConfig()['fields'],
                'identity' => $this->getImportConfig()['identity'] ?? [],
                'routeName' => $this->routeName ?? "configuration.$slug"
            ];

            // Perform Synchronous Import
            \Maatwebsite\Excel\Facades\Excel::import(
                new \App\Imports\VillageImporter(
                    $sessionId, 
                    $mapping, 
                    $mode, 
                    $config,
                    $startRow
                ), 
                storage_path('app/' . $filePath)
            );

            $progress = \App\Models\ImportProgress::where('session_id', $sessionId)->first();

            session()->forget([
                "pending_config_import_{$slug}_file",
                "pending_config_import_{$slug}_headers",
                "pending_config_import_{$slug}_mapping",
                "pending_config_import_{$slug}_mode",
                "pending_config_import_{$slug}_start_row"
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'تم استيراد القرى بنجاح.', 
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
}
