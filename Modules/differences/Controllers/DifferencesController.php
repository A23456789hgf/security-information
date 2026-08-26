<?php

namespace Modules\Differences\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Differences\Models\DifferenceAnalysis;
use Modules\Differences\Models\DifferenceAnalysisResult;
use Modules\Differences\Models\DifferenceSearchTarget;
use Modules\Differences\Models\DifferenceAnalysisSetting;
use Modules\Differences\Imports\ExcelImporter;
use Modules\Differences\Jobs\ProcessDifferenceAnalysisJob;
use Modules\Differences\Services\AnalysisLogService;
use App\Models\Page;
use App\Models\DataTable;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class DifferencesController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->check() && (auth()->user()->can('manage_differences') || auth()->user()->hasRole('super-admin'))) {
                return $next($request);
            }
            abort(403, 'This action is unauthorized.');
        });
    }

    /**
     * Display list of previous analysis runs and upload form
     */
    public function index()
    {
        $analyses = DifferenceAnalysis::orderBy('created_at', 'desc')->get();
        return view('differences::index', compact('analyses'));
    }

    /**
     * Store uploaded file, auto-map headers and dispatch background matching job
     */
    public function store(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:20480',
        ]);

        $file = $request->file('excel_file');
        $filename = $file->getClientOriginalName();
        
        // Save file to a secure, isolated storage path
        $path = $file->storeAs('differences', time() . '_' . $filename, 'local');
        $fullPath = storage_path('app/' . $path);

        // 1. Create a pending difference analysis run record
        $analysis = DifferenceAnalysis::create([
            'filename' => $filename,
            'status' => 'pending',
            'progress' => 0,
            'total_rows' => 0,
            'processed_rows' => 0,
            'stats' => [
                'matches' => 0,
                'similarities' => 0,
                'duplicates' => 0,
                'not_found' => 0
            ]
        ]);

        // Write upload logs
        AnalysisLogService::info($analysis->id, "تم رفع الملف بنجاح وتخزينه في المسار الآمن. اسم الملف: $filename");

        try {
            // 2. Parse Excel headers and perform auto-column-mapping
            $importer = new ExcelImporter();
            $fileData = $importer->getHeaderAndTotalRows($fullPath);
            $headers = array_values($fileData['headers']);

            $mappings = $this->autoMapHeaders($headers);

            // Record snapshot of settings used for auditability
            $settings = DifferenceAnalysisSetting::first();
            if ($settings) {
                $analysis->update([
                    'settings_snapshot' => [
                        'threshold' => $settings->similarity_threshold,
                        'strategy' => $settings->selected_strategy,
                        'rules' => $settings->matching_rules,
                        'excluded_words' => $settings->excluded_words
                    ]
                ]);
            }

            // 3. Dispatch the background similarity analysis Job
            ProcessDifferenceAnalysisJob::dispatch($analysis->id, $fullPath, $mappings);

            return redirect()->route('data.differences.show', $analysis->id)
                ->with('success', 'تم بدء معالجة ومطابقة السجلات بنجاح في الخلفية.');

        } catch (\Exception $e) {
            $analysis->update(['status' => 'failed']);
            AnalysisLogService::error($analysis->id, 'فشل بدء معالجة الملف المستورد: ' . $e->getMessage());

            return redirect()->route('data.differences.index')
                ->with('error', 'حدث خطأ أثناء قراءة ملف البيانات المستورد: ' . $e->getMessage());
        }
    }

    /**
     * Show analysis progress dashboard and tabulated results
     */
    public function show(DifferenceAnalysis $analysis, Request $request)
    {
        $query = $analysis->results();

        // 1. Server-side text search
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('external_name', 'like', "%{$search}%")
                  ->orWhere('matching_name', 'like', "%{$search}%");
            });
        }

        // 2. Server-side status filter
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // 3. Server-side registry target filter
        if ($target = $request->input('target')) {
            $query->where('source_target_id', $target);
        }

        // 4. Server-side sorting
        $sortBy = $request->input('sort_by', 'similarity_percentage');
        $sortDir = $request->input('sort_dir', 'desc');
        
        // Safety validation for sorting parameters
        $validSorts = ['external_name', 'matching_name', 'similarity_percentage', 'occurrences_count', 'record_status', 'source_target_name', 'status'];
        if (in_array($sortBy, $validSorts)) {
            $query->orderBy($sortBy, $sortDir);
        } else {
            $query->orderBy('similarity_percentage', 'desc');
        }

        $results = $query->paginate(50)->withQueryString();
        $targets = DifferenceSearchTarget::where('is_active', true)->get();

        return view('differences::show', compact('analysis', 'results', 'targets'));
    }

    /**
     * AJAX Endpoint to poll progress percentage, stats counters and latest logs
     */
    public function progress(DifferenceAnalysis $analysis)
    {
        $logs = $analysis->logs()->orderBy('created_at', 'desc')->take(5)->get()->map(function($l) {
            return [
                'level' => $l->level,
                'message' => $l->message,
                'time' => $l->created_at->format('H:i:s')
            ];
        });

        return response()->json([
            'status' => $analysis->status,
            'progress' => $analysis->progress,
            'total_rows' => $analysis->total_rows,
            'processed_rows' => $analysis->processed_rows,
            'stats' => $analysis->stats ?? [
                'matches' => 0,
                'similarities' => 0,
                'duplicates' => 0,
                'not_found' => 0
            ],
            'logs' => $logs
        ]);
    }

    /**
     * Fetch JSON detailed card for a single match result (Popup Details)
     */
    public function detail(DifferenceAnalysisResult $result)
    {
        $originalUrl = null;
        if ($result->original_record_id) {
            // Generate standard show/edit link dynamically based on source type
            if ($result->source_page_type === 'static') {
                if ($result->source_table === 'personal_data') {
                    $originalUrl = route('system.personal-data.show', $result->original_record_id);
                } elseif ($result->source_table === 'wives') {
                    $originalUrl = route('system.wives.show', $result->original_record_id);
                } elseif ($result->source_table === 'security_reports') {
                    $originalUrl = route('system.security-reports.show', $result->original_record_id);
                }
            } elseif ($result->source_page_type === 'dynamic') {
                // For dynamic pages, we look up page slug and route it to the dynamic record editor
                $target = DifferenceSearchTarget::find($result->source_target_id);
                if ($target) {
                    $page = Page::where('slug', $target->source_identifier)->first();
                    if ($page) {
                        $originalUrl = "/system/pages/{$page->id}/records/{$result->original_record_id}/edit";
                    }
                }
            } elseif ($result->source_page_type === 'custom_table') {
                $originalUrl = "/data/utility/{$result->source_target_id}";
            }
        }

        return response()->json([
            'id' => $result->id,
            'external_name' => $result->external_name,
            'matching_name' => $result->matching_name,
            'status' => $result->status,
            'similarity_percentage' => $result->similarity_percentage,
            'source_target_name' => $result->source_target_name,
            'source_page_type' => $result->source_page_type,
            'occurrences_count' => $result->occurrences_count,
            'record_status' => $result->record_status,
            'original_record_id' => $result->original_record_id,
            'original_record_data' => $result->original_record_data ?? [],
            'all_matches' => $result->all_matches ?? [],
            'matching_details' => $result->matching_details ?? [],
            'original_url' => $originalUrl
        ]);
    }

    /**
     * Settings configuration page
     */
    public function settings()
    {
        $setting = DifferenceAnalysisSetting::first();
        $targets = DifferenceSearchTarget::orderBy('created_at', 'asc')->get();
        
        $dynamicPages = Page::where('is_active', true)->get();
        $customTables = DataTable::all();

        return view('differences::settings', compact('setting', 'targets', 'dynamicPages', 'customTables'));
    }

    /**
     * Update threshold, stop words and strategy
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'similarity_threshold' => 'required|integer|between:50,100',
            'selected_strategy' => 'required|string|in:standard,soundex,nlp',
            'excluded_words' => 'nullable|string'
        ]);

        $setting = DifferenceAnalysisSetting::first();
        if (!$setting) {
            $setting = new DifferenceAnalysisSetting();
        }

        // Convert excluded words string to JSON array
        $excludedStr = $request->input('excluded_words', '');
        $wordsArray = array_filter(array_map('trim', explode(',', $excludedStr)));

        // Handle dynamic rules update if passed in request
        $rules = $request->input('rules');
        if (!empty($rules) && is_array($rules)) {
            $setting->matching_rules = $rules;
        }

        $setting->similarity_threshold = $request->similarity_threshold;
        $setting->selected_strategy = $request->selected_strategy;
        $setting->excluded_words = $wordsArray;
        $setting->save();

        return redirect()->route('data.differences.settings')
            ->with('success', 'تم تحديث إعدادات نظام التمايز الذكي بنجاح.');
    }

    /**
     * Add new search target dynamic registry
     */
    public function addTarget(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|in:static,dynamic,custom_table',
            'source_identifier' => 'required|string',
            'col_name' => 'required|string',
            'col_id' => 'nullable|string',
            'col_phone' => 'nullable|string',
            'col_address' => 'nullable|string'
        ]);

        DifferenceSearchTarget::create([
            'name' => $request->name,
            'type' => $request->type,
            'source_identifier' => $request->source_identifier,
            'columns' => [
                'name' => $request->col_name,
                'id' => $request->col_id ?? '',
                'phone' => $request->col_phone ?? '',
                'address' => $request->col_address ?? ''
            ],
            'is_active' => true
        ]);

        return redirect()->route('data.differences.settings')
            ->with('success', 'تم تسجيل وإضافة مستهدف مطابقة جديد إلى السجل الديناميكي بنجاح.');
    }

    /**
     * Toggle target status
     */
    public function toggleTarget(DifferenceSearchTarget $target)
    {
        $target->is_active = !$target->is_active;
        $target->save();

        return response()->json([
            'success' => true,
            'is_active' => $target->is_active
        ]);
    }

    /**
     * Export analysis results to a highly styled Excel file
     */
    public function exportExcel(DifferenceAnalysis $analysis)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setRightToLeft(true); // RTL support for Arabic

        // Style helper arrays
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2B4C7E'] // Premium Dark Navy
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ];

        // 1. Write metadata title
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'تقرير نتائج تمايز ومطابقة البيانات الذكية - ملف: ' . $analysis->filename);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('2B4C7E'));
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(35);

        // 2. Write Table Headers
        $headers = [
            'الاسم المستورد',
            'الاسم المطابق بالنظام',
            'نسبة التطابق',
            'مستهدف البحث',
            'اسم الجدول',
            'نوع الصفحة',
            'عدد التكرارات',
            'حالة المطابقة',
            'حالة السجل'
        ];

        $colChar = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($colChar . '3', $h);
            $sheet->getStyle($colChar . '3')->applyFromArray($headerStyle);
            $sheet->getColumnDimension($colChar)->setAutoSize(true);
            $colChar++;
        }
        $sheet->getRowDimension(3)->setRowHeight(28);

        // 3. Write Data Rows
        $results = $analysis->results()->orderBy('similarity_percentage', 'desc')->get();
        $rowIdx = 4;

        $borderStyle = [
            'borders' => [
                'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']],
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']],
                'left' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']],
                'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]
            ]
        ];

        foreach ($results as $res) {
            $sheet->setCellValue('A' . $rowIdx, $res->external_name);
            $sheet->setCellValue('B' . $rowIdx, $res->matching_name ?? '---');
            $sheet->setCellValue('C' . $rowIdx, $res->matching_name ? $res->similarity_percentage . '%' : '0.00%');
            $sheet->setCellValue('D' . $rowIdx, $res->source_target_name ?? '---');
            $sheet->setCellValue('E' . $rowIdx, $res->source_table ?? '---');
            
            $typeAr = '---';
            if ($res->source_page_type === 'static') $typeAr = 'صفحة ثابتة';
            if ($res->source_page_type === 'dynamic') $typeAr = 'استمارة ديناميكية';
            if ($res->source_page_type === 'custom_table') $typeAr = 'جدول مخصص';
            $sheet->setCellValue('F' . $rowIdx, $typeAr);

            $sheet->setCellValue('G' . $rowIdx, $res->occurrences_count);

            $statusAr = 'غير موجود';
            $statusColor = 'FEE2E2'; // Light red
            if ($res->status === 'match') {
                $statusAr = 'مطابقة تامة';
                $statusColor = 'D1FAE5'; // Light green
            } elseif ($res->status === 'similarity') {
                $statusAr = 'تشابه قوي';
                $statusColor = 'FEF3C7'; // Light yellow
            } elseif ($res->status === 'duplicate') {
                $statusAr = 'سجل مكرر بالنظام';
                $statusColor = 'FFEDD5'; // Light orange
            }
            
            $sheet->setCellValue('H' . $rowIdx, $statusAr);
            
            // Apply status cell background
            $sheet->getStyle('H' . $rowIdx)->getFill()->setFillType(Fill::FILL_SOLID);
            $sheet->getStyle('H' . $rowIdx)->getFill()->getStartColor()->setRGB($statusColor);

            $sheet->setCellValue('I' . $rowIdx, $res->record_status ?? '---');

            // Format alignment and borders
            for ($c = 'A'; $c <= 'I'; $c++) {
                $sheet->getStyle($c . $rowIdx)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle($c . $rowIdx)->applyFromArray($borderStyle);
            }

            $sheet->getRowDimension($rowIdx)->setRowHeight(22);
            $rowIdx++;
        }

        $writer = new Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="تقرير_التمايز_' . time() . '.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    /**
     * Print-optimized view formatted like a premium government dossier
     */
    public function printPdf(DifferenceAnalysis $analysis)
    {
        $results = $analysis->results()->orderBy('similarity_percentage', 'desc')->get();
        return view('differences::print', compact('analysis', 'results'));
    }

    /**
     * Parse headers and perform automated string match mapping
     */
    private function autoMapHeaders(array $headers): array
    {
        $mappings = [];
        foreach ($headers as $h) {
            $hNorm = mb_strtolower(trim($h));
            if (str_contains($hNorm, 'اسم') || str_contains($hNorm, 'name')) {
                $mappings[$h] = 'name';
            } elseif (str_contains($hNorm, 'هوي') || str_contains($hNorm, 'وطن') || str_contains($hNorm, 'عسكري') || str_contains($hNorm, 'عام') || str_contains($hNorm, 'id') || str_contains($hNorm, 'رقم')) {
                // If it indicates ID or general number
                $mappings[$h] = 'id';
            } elseif (str_contains($hNorm, 'هاتف') || str_contains($hNorm, 'جوال') || str_contains($hNorm, 'تلفون') || str_contains($hNorm, 'phone') || str_contains($hNorm, 'mobile')) {
                $mappings[$h] = 'phone';
            } elseif (str_contains($hNorm, 'عنوان') || str_contains($hNorm, 'سكن') || str_contains($hNorm, 'address') || str_contains($hNorm, 'بلد') || str_contains($hNorm, 'محافظ')) {
                $mappings[$h] = 'address';
            }
        }

        // Safety: Ensure name mapping exists, otherwise map first column
        if (!in_array('name', $mappings) && !empty($headers)) {
            $mappings[reset($headers)] = 'name';
        }

        return $mappings;
    }

    /**
     * Programmatically seed 50 mock records for all target schemas
     */
    public function seedMockData()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\DummyDataSeeder',
                '--force' => true
            ]);
            return redirect()->route('data.differences.index')
                ->with('success', 'تم توليد وتغذية النظام بـ 50 سجل محاكاة لكل صفحة افتراضية بنجاح!');
        } catch (\Exception $e) {
            return redirect()->route('data.differences.index')
                ->with('error', 'فشل توليد السجلات التجريبية: ' . $e->getMessage());
        }
    }
}
