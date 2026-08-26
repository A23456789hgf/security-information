<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

trait HandlesConfigurationImportExport
{
    /**
     * Get the import configuration (fields and identity keys).
     * Child controllers should override this.
     */
    protected function getImportConfig()
    {
        return [
            'fields' => [$this->field => 'الاسم'],
            'identity' => [$this->field],
        ];
    }

    /**
     * Export data to Excel.
     */
    public function export()
    {
        $modelClass = $this->modelClass;
        $config = $this->getImportConfig();
        $fields = $config['fields'];

        $items = $modelClass::all();

        return Excel::download(new class($items, $fields) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithMapping
        {
            private $items;

            private $fields;

            public function __construct($items, $fields)
            {
                $this->items = $items;
                $this->fields = $fields;
            }

            public function collection()
            {
                return $this->items;
            }

            public function headings(): array
            {
                return array_values($this->fields);
            }

            public function map($item): array
            {
                $row = [];
                foreach (array_keys($this->fields) as $field) {
                    $value = $item->$field;
                    // Resolve foreign keys for export if possible
                    if (str_ends_with($field, '_id')) {
                        $relation = str_replace('_id', '', $field);
                        if (method_exists($item, $relation) && $item->$relation) {
                            $value = $item->$relation->name ?? $item->$relation->type ?? $item->$relation->status ?? $value;
                        }
                    }
                    $row[] = $value;
                }

                return $row;
            }
        }, Str::slug(class_basename($modelClass)).'_export_'.date('Y-m-d').'.xlsx');
    }

    /**
     * Download template.
     */
    public function template()
    {
        $config = $this->getImportConfig();
        $headings = array_values($config['fields']);

        return Excel::download(new class($headings) implements \Maatwebsite\Excel\Concerns\FromCollection
        {
            private $headings;

            public function __construct($headings)
            {
                $this->headings = $headings;
            }

            public function collection()
            {
                return collect([$this->headings]);
            }
        }, 'template_'.Str::slug(class_basename($this->modelClass)).'.xlsx');
    }

    /**
     * Show the dedicated import page.
     */
    public function importForm()
    {
        $slug = $this->getResourceSlug();
        $config = $this->getImportConfig();
        $title = class_basename($this->modelClass);

        return view('configuration.import', [
            'type' => $slug,
            'title' => $title,
            'fields' => $config['fields'],
            'routeName' => $this->routeName ?? "configuration.$slug",
        ]);
    }

    /**
     * Parse and validate file, store in session.
     */
    /**
     * Parse and validate file, store file path in session.
     */
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('excel_file');
        $slug = $this->getResourceSlug();

        try {
            // Save file to disk
            $path = $file->store('imports');
            $fullPath = storage_path('app/'.$path);

            // Scalable: Read only the beginning of the file to detect headers using WithLimit
            $import = new class implements \Maatwebsite\Excel\Concerns\ToArray, \Maatwebsite\Excel\Concerns\WithLimit
            {
                public $data = [];

                public function array(array $array)
                {
                    $this->data = $array;
                }

                public function limit(): int
                {
                    return 100;
                }
            };
            Excel::import($import, $fullPath);
            $rows = $import->data;

            if (empty($rows)) {
                return response()->json(['success' => false, 'message' => 'الملف فارغ']);
            }

            // Detect headers and data start row
            $headers = [];
            $headerRowIndex = 0;
            $dataStartRow = 1;

            foreach ($rows as $index => $row) {
                if (! is_array($row)) {
                    continue;
                }

                $validColumns = array_filter($row, function ($h) {
                    return ! is_null($h) && trim((string) $h) !== '';
                });

                if (! empty($validColumns)) {
                    $headers = array_map(function ($h) {
                        return is_null($h) ? '' : (string) $h;
                    }, $row);
                    $headerRowIndex = $index;
                    $dataStartRow = $index + 2; // +1 for 0-index vs 1-index, +1 for row after headers
                    break;
                }
            }

            if (empty($headers)) {
                return response()->json(['success' => false, 'message' => 'تعذر العثور على رؤوس أعمدة (عناوين) في أول 100 سطر من الملف. تأكد من أن الصف الأول ليس فارغاً.']);
            }

            // Slice rows for preview (after headers)
            $previewRows = array_slice($rows, $headerRowIndex + 1, 5);

            session(["pending_config_import_{$slug}_file" => $path]);
            session(["pending_config_import_{$slug}_headers" => $headers]);
            session(["pending_config_import_{$slug}_start_row" => $dataStartRow]);

            $safePreview = array_map(function ($row) {
                if (! is_array($row)) {
                    return [];
                }

                return array_map(function ($cell) {
                    return is_null($cell) ? '' : (string) $cell;
                }, $row);
            }, $previewRows);

            return response()->json([
                'success' => true,
                'columns' => $headers,
                'preview_data' => $safePreview,
                'total_rows' => 'غير معروف (جاري المعالجة)', // For large files, we don't count everything now
                'target_fields' => $this->getImportConfig()['fields'],
                'context' => $request->except(['excel_file', '_token']),
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'خطأ في معالجة الملف: '.$e->getMessage()]);
        }
    }

    /**
     * Validate the mapped data (Partial preview for large files).
     */
    public function validateImport(Request $request)
    {
        $config = $this->getImportConfig();
        $fieldsConfig = $config['fields'];
        $identityKeys = $config['identity'] ?? [];
        $modelClass = $this->modelClass;
        $slug = $this->getResourceSlug();

        $mode = $request->input('mode', 'append');
        $mapping = $request->input('mapping');

        $filePath = session("pending_config_import_{$slug}_file");
        $headers = session("pending_config_import_{$slug}_headers");
        $context = $request->except(['mapping', 'mode', '_token']);

        if (! $filePath || ! file_exists(storage_path('app/'.$filePath))) {
            return response()->json(['success' => false, 'message' => 'الملف غير موجود، يرجى إعادة رفعه.']);
        }

        // Save mapping and mode for final store
        session(["pending_config_import_{$slug}_mapping" => $mapping]);
        session(["pending_config_import_{$slug}_mode" => $mode]);

        try {
            // Scalable: Read only enough rows for preview using WithLimit
            $import = new class implements \Maatwebsite\Excel\Concerns\ToArray, \Maatwebsite\Excel\Concerns\WithLimit
            {
                public $data = [];

                public function array(array $array)
                {
                    $this->data = $array;
                }

                public function limit(): int
                {
                    return 150;
                }
            };
            Excel::import($import, storage_path('app/'.$filePath));
            $allRows = $import->data;

            $startRow = session("pending_config_import_{$slug}_start_row", 2);
            $totalRows = '...';

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
                        $value = trim((string) ($row[$colIndex] ?? ''));
                    } elseif (isset($context[$field]) && $context[$field] !== '') {
                        $value = $context[$field];
                    }

                    if (empty($value)) {
                        $errors[] = "الحقل $label مفقود";
                    } else {
                        if (str_ends_with($field, '_id')) {
                            $relationTable = Str::plural(str_replace('_id', '', $field));
                            if ($field === 'military_district_id') {
                                $relationTable = 'military_districts';
                            }

                            // Fuzzy matching
                            $normVal = method_exists($this, 'normalizeArabic') ? $this->normalizeArabic($value) : trim((string) $value);
                            $query = \DB::table($relationTable);

                            // Specific hierarchical filters for locations
                            if ($field === 'directorate_id' && isset($rowData['governorate_id'])) {
                                $query->where('governorate_id', $rowData['governorate_id']);
                            }
                            if ($field === 'sub_area_id' && isset($rowData['directorate_id'])) {
                                $query->where('directorate_id', $rowData['directorate_id']);
                            }
                            if ($field === 'village_id' && isset($rowData['sub_area_id'])) {
                                $query->where('sub_area_id', $rowData['sub_area_id']);
                            }

                            $items = $query->get(['id', 'name']);
                            $id = null;
                            foreach ($items as $item) {
                                if ((method_exists($this, 'normalizeArabic') ? $this->normalizeArabic($item->name) : trim((string) $item->name)) === $normVal || $item->id == $value) {
                                    $id = $item->id;
                                    break;
                                }
                            }

                            if (! $id) {
                                $errors[] = "$label غير موجود بجداول النظام أو غير مرتبط بالهيكل ($value)";
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
                    $query = $modelClass::query();
                    if (! empty($identityKeys)) {
                        foreach ($identityKeys as $k) {
                            if (isset($rowData[$k])) {
                                $query->where($k, $rowData[$k]);
                            }
                        }
                    } else {
                        foreach ($rowData as $k => $v) {
                            $query->where($k, $v);
                        }
                    }

                    $exists = $query->exists();
                    if ($mode === 'append') {
                        if ($exists) {
                            $currentStatus = 'exists';
                        }
                    } elseif ($mode === 'update') {
                        if (! $exists) {
                            $currentStatus = 'error';
                            $errors[] = 'السجل غير موجود للتحديث';
                        } else {
                            $currentStatus = 'update';
                        }
                    } elseif ($mode === 'upsert') {
                        if ($exists) {
                            $currentStatus = 'update';
                        } else {
                            $currentStatus = 'ready';
                        }
                    }
                } else {
                    $currentStatus = 'error';
                }

                $results[] = [
                    'row_index' => $index,
                    'data' => $row,
                    'errors' => $errors,
                    'status' => $currentStatus,
                    'mapped_data' => $rowData,
                ];
            }

            return response()->json([
                'success' => true,
                'results' => $results,
                'headers' => $headers,
                'total_rows_original' => $totalRows,
                'total_ready' => $totalRows,
                'total_update' => 0,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'خطأ في معالجة المعاينة: '.$e->getMessage()]);
        }
    }

    /**
     * Final store logic: Synchronous Execution.
     */
    public function importStore(Request $request)
    {
        $slug = $this->getResourceSlug();
        $filePath = session("pending_config_import_{$slug}_file");
        $mapping = session("pending_config_import_{$slug}_mapping");
        $mode = session("pending_config_import_{$slug}_mode", 'append');
        $startRow = session("pending_config_import_{$slug}_start_row", 2);

        if (! $filePath || ! file_exists(storage_path('app/'.$filePath))) {
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
                'routeName' => $this->routeName ?? "configuration.$slug",
            ];

            // Perform Synchronous Import
            // We use the specific importer if defined, otherwise generic
            $importerClass = property_exists($this, 'importerClass') ? $this->importerClass : \App\Imports\ConfigurationImporter::class;

            \Maatwebsite\Excel\Facades\Excel::import(
                new $importerClass($sessionId, $mapping, $mode, $config, $startRow),
                storage_path('app/'.$filePath)
            );

            $progress = \App\Models\ImportProgress::where('session_id', $sessionId)->first();

            // Cleanup session
            session()->forget([
                "pending_config_import_{$slug}_file",
                "pending_config_import_{$slug}_headers",
                "pending_config_import_{$slug}_mapping",
                "pending_config_import_{$slug}_mode",
                "pending_config_import_{$slug}_start_row",
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم استيراد البيانات بنجاح.',
                'session_id' => $sessionId,
                'is_async' => false,
                'results' => [
                    'processed' => $progress->processed_rows,
                    'created' => $progress->created_count,
                    'updated' => $progress->updated_count,
                    'failed' => $progress->failed_count,
                    'errors' => $progress->errors,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء الاستيراد: '.$e->getMessage()]);
        }
    }
}
