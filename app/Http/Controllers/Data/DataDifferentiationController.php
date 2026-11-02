<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\DataRecord;
use App\Models\DataTable;
use App\Models\DifferentiationLog;
use App\Models\DifferentiationSavedMatch;
use App\Services\DifferentiationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DataDifferentiationController extends Controller
{
    protected $differentiationService;

    public function __construct(DifferentiationService $differentiationService)
    {
        $this->differentiationService = $differentiationService;
    }

    /**
     * Display differentiation index page with datasets, system sources, and logs.
     */
    public function index()
    {
        $mainTable = DataTable::where('type', 'primary')->latest()->first();
        $mainColumns = $mainTable ? ($mainTable->column_headers ?? []) : [];
        $mainData = $mainTable ? $mainTable->records()->orderBy('sequence')->limit(100)->get()->pluck('data')->toArray() : [];

        $secondaryDatasets = DataTable::where('type', 'secondary')->latest()->get();
        $systemSources = $this->differentiationService->getAvailableSystemSources();
        $auditLogs = DifferentiationLog::with('user')->latest()->limit(50)->get();

        return view('data.differentiation.index', compact(
            'mainTable',
            'mainColumns',
            'mainData',
            'secondaryDatasets',
            'systemSources',
            'auditLogs'
        ));
    }

    /**
     * AJAX: Upload Excel/CSV file and return headers and sample data for field mapping.
     */
    public function parseFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            $parsed = $this->differentiationService->parseUploadedFile($request->file('file'));
            return response()->json([
                'success' => true,
                'headers' => $parsed['headers'],
                'sample' => $parsed['sample'],
                'count' => $parsed['count'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * AJAX: Store Main Dataset with mapping and validation.
     */
    public function storeMain(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
            'name' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'mapping' => 'nullable|array',
        ]);

        try {
            $file = $request->file('file');
            $parsed = $this->differentiationService->parseUploadedFile($file);
            $mapping = $request->input('mapping', []);

            $table = $this->differentiationService->saveMainDataset(
                $request->input('name') ?: 'البيانات الرئيسية',
                $request->input('source') ?: 'استيراد ملف',
                $request->input('description') ?: '',
                $parsed['headers'],
                $parsed['rows'],
                $mapping,
                Auth::id()
            );

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ البيانات الرئيسية بنجاح.',
                'table_id' => $table->id,
                'count' => count($parsed['rows']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * AJAX: Store Secondary Dataset with custom name, source, and mapping.
     */
    public function storeSecondary(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
            'name' => 'required|string|max:255',
            'source' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'mapping' => 'nullable|array',
        ]);

        try {
            $file = $request->file('file');
            $parsed = $this->differentiationService->parseUploadedFile($file);
            $mapping = $request->input('mapping', []);

            $table = $this->differentiationService->saveSecondaryDataset(
                $request->input('name'),
                $request->input('source') ?: $file->getClientOriginalName(),
                $request->input('description') ?: '',
                $parsed['headers'],
                $parsed['rows'],
                $mapping,
                Auth::id()
            );

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ مجموعة البيانات الثانوية بنجاح.',
                'dataset' => [
                    'id' => $table->id,
                    'name' => $table->name,
                    'source' => $table->source,
                    'records_count' => $table->records_count,
                    'created_at' => $table->created_at->format('Y-m-d H:i'),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * AJAX: List saved secondary datasets.
     */
    public function listSecondary()
    {
        $datasets = DataTable::where('type', 'secondary')->with('user')->latest()->get();
        return response()->json([
            'success' => true,
            'datasets' => $datasets,
        ]);
    }

    /**
     * AJAX: Show secondary dataset records and details.
     */
    public function showSecondary($id)
    {
        $table = DataTable::where('type', 'secondary')->find($id);
        if (!$table) {
            return response()->json(['success' => false, 'message' => 'مجموعة البيانات غير موجودة.'], 404);
        }

        $records = $table->records()->orderBy('sequence')->limit(200)->get()->pluck('data')->toArray();

        return response()->json([
            'success' => true,
            'table' => $table,
            'headers' => $table->column_headers,
            'records' => $records,
        ]);
    }

    /**
     * AJAX: Update secondary dataset details.
     */
    public function updateSecondary(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'source' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $table = DataTable::where('type', 'secondary')->find($id);
        if (!$table) {
            return response()->json(['success' => false, 'message' => 'مجموعة البيانات غير موجودة.'], 404);
        }

        $table->update([
            'name' => $request->input('name'),
            'source' => $request->input('source'),
            'description' => $request->input('description'),
        ]);

        DifferentiationLog::create([
            'user_id' => Auth::id(),
            'action' => 'update_dataset',
            'dataset_name' => $table->name,
            'details' => ['table_id' => $table->id],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث بيانات المجموعة بنجاح.',
        ]);
    }

    /**
     * AJAX: Delete a secondary dataset.
     */
    public function deleteSecondary($id)
    {
        $table = DataTable::where('type', 'secondary')->find($id);
        if (!$table) {
            return response()->json(['success' => false, 'message' => 'مجموعة البيانات غير موجودة.'], 404);
        }

        $name = $table->name;
        $table->records()->delete();
        $table->delete();

        DifferentiationLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete_dataset',
            'dataset_name' => $name,
            'details' => ['table_id' => $id],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم حذف مجموعة البيانات الثانوية بنجاح.',
        ]);
    }

    /**
     * AJAX: Get selectable fields for a system page/source.
     */
    public function getSystemSourceFields($sourceKey)
    {
        $fields = $this->differentiationService->getSystemSourceFields($sourceKey);
        return response()->json([
            'success' => true,
            'fields' => $fields,
        ]);
    }

    /**
     * AJAX: Execute Matching and Comparison between Secondary dataset and Main dataset or System Page.
     */
    public function executeComparison(Request $request)
    {
        $request->validate([
            'secondary_id' => 'required|integer',
            'primary_source_type' => 'required|string|in:main_table,system_source',
            'system_source_key' => 'nullable|string',
            'key_fields' => 'required|array|min:1',
            'field_mapping' => 'nullable|array',
        ]);

        try {
            $secondaryTable = DataTable::where('type', 'secondary')->find($request->input('secondary_id'));
            if (!$secondaryTable) {
                return response()->json(['success' => false, 'message' => 'مجموعة البيانات الثانوية غير موجودة.'], 404);
            }

            $secondaryRows = $secondaryTable->records()->orderBy('sequence')->get()->pluck('data')->toArray();
            $secondaryHeaders = $secondaryTable->column_headers ?: [];

            $primaryRows = [];
            $primaryHeaders = [];
            $primaryTitle = '';

            if ($request->input('primary_source_type') === 'main_table') {
                $mainTable = DataTable::where('type', 'primary')->first();
                if (!$mainTable) {
                    return response()->json(['success' => false, 'message' => 'لم يتم إدخال بيانات رئيسية بالنظام بعد.'], 422);
                }
                $primaryRows = $mainTable->records()->orderBy('sequence')->get()->pluck('data')->toArray();
                $primaryHeaders = $mainTable->column_headers ?: [];
                $primaryTitle = $mainTable->name;
            } else {
                $sourceKey = $request->input('system_source_key');
                if (!$sourceKey) {
                    return response()->json(['success' => false, 'message' => 'يرجى اختيار صفحة/مصدر بيانات من النظام.'], 422);
                }
                $primaryRows = $this->differentiationService->fetchSystemSourceRows($sourceKey);
                $fields = $this->differentiationService->getSystemSourceFields($sourceKey);
                $primaryHeaders = array_column($fields, 'key');
                $primaryTitle = 'مصدر النظام: ' . $sourceKey;
            }

            $keyFields = $request->input('key_fields');
            $fieldMapping = $request->input('field_mapping', []);

            $comparison = $this->differentiationService->compareRows(
                $primaryRows,
                $secondaryRows,
                $keyFields,
                $fieldMapping
            );

            // Save match run history
            $savedMatch = DifferentiationSavedMatch::create([
                'user_id' => Auth::id(),
                'title' => 'مقارنة ' . $secondaryTable->name . ' مع ' . $primaryTitle,
                'primary_source_type' => $request->input('primary_source_type'),
                'primary_source_id' => $request->input('system_source_key') ?: 'main_table',
                'secondary_table_id' => $secondaryTable->id,
                'key_fields' => $keyFields,
                'field_mappings' => $fieldMapping,
                'summary' => $comparison['summary'],
                'results_data' => json_encode($comparison['results'], JSON_UNESCAPED_UNICODE),
            ]);

            // Log operation
            DifferentiationLog::create([
                'user_id' => Auth::id(),
                'action' => 'match_datasets',
                'dataset_name' => $secondaryTable->name,
                'details' => [
                    'primary_source' => $primaryTitle,
                    'summary' => $comparison['summary'],
                    'saved_match_id' => $savedMatch->id,
                ],
            ]);

            return response()->json([
                'success' => true,
                'summary' => $comparison['summary'],
                'results' => $comparison['results'],
                'saved_match_id' => $savedMatch->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Export comparison results as CSV stream.
     */
    public function exportResults(Request $request)
    {
        $matchId = $request->input('saved_match_id');
        $category = $request->input('category', 'all');

        $savedMatch = DifferentiationSavedMatch::find($matchId);
        if (!$savedMatch) {
            return response()->json(['message' => 'نتيجة المقارنة غير موجودة.'], 404);
        }

        $results = is_string($savedMatch->results_data) ? json_decode($savedMatch->results_data, true) : $savedMatch->results_data;

        $fileName = 'differentiation_report_' . date('Y-m-d_H-i') . '.csv';

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($results, $category) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // UTF-8 BOM for Arabic Excel opening

            fputcsv($file, ['تقرير نتائج مطابقة ومقارنة البيانات']);
            fputcsv($file, ['التصنيف', 'رقم السجل الثانوي', 'تفاصيل السجل', 'الفرق / الحقول المتأثرة']);

            $rowsToExport = [];
            if ($category === 'all' || $category === 'matched') {
                foreach ($results['matched'] ?? [] as $item) {
                    fputcsv($file, ['متطابق', $item['secondary_index'], json_encode($item['secondary_row'], JSON_UNESCAPED_UNICODE), 'تطابق كامل']);
                }
            }
            if ($category === 'all' || $category === 'different') {
                foreach ($results['different'] ?? [] as $item) {
                    fputcsv($file, ['مختلف', $item['secondary_index'], json_encode($item['secondary_row'], JSON_UNESCAPED_UNICODE), json_encode($item['differences'], JSON_UNESCAPED_UNICODE)]);
                }
            }
            if ($category === 'all' || $category === 'in_sec_only') {
                foreach ($results['in_sec_only'] ?? [] as $item) {
                    fputcsv($file, ['موجود في الثانوية فقط', $item['secondary_index'], json_encode($item['secondary_row'], JSON_UNESCAPED_UNICODE), 'غير موجود في المصدر الرئيسي']);
                }
            }
            if ($category === 'all' || $category === 'in_pri_only') {
                foreach ($results['in_pri_only'] ?? [] as $item) {
                    fputcsv($file, ['موجود في الرئيسية فقط', $item['primary_index'], json_encode($item['primary_row'], JSON_UNESCAPED_UNICODE), 'غير موجود في الثانوية']);
                }
            }
            if ($category === 'all' || $category === 'incomplete') {
                foreach ($results['incomplete'] ?? [] as $item) {
                    fputcsv($file, ['بيانات ناقصة', $item['secondary_index'], json_encode($item['secondary_row'], JSON_UNESCAPED_UNICODE), $item['reason'] ?? 'مفقود قيم المفاتيح']);
                }
            }

            fclose($file);
        };

        DifferentiationLog::create([
            'user_id' => Auth::id(),
            'action' => 'export',
            'dataset_name' => $savedMatch->title,
            'details' => ['category' => $category],
        ]);

        return response()->stream($callback, 200, $headers);
    }
}
