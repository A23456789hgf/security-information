<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Record;
use App\Services\DynamicImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class DynamicImportExportController extends Controller
{
    /**
     * Show import modal.
     */
    public function importForm(Page $page)
    {
        abort_unless(Gate::allows('import_' . $page->slug), 403, 'عفواً، لا تملك الصلاحية لاستيراد البيانات.');
        return view('system.dynamic_data.import_form', compact('page'));
    }

    /**
     * Download the XLSX template (Data + Instructions sheets).
     */
    public function downloadTemplate(Page $page)
    {
        abort_unless(Gate::allows('import_' . $page->slug), 403, 'عفواً، لا تملك الصلاحية لتحميل قالب الاستيراد.');
        $service = new DynamicImportService();
        return $service->downloadTemplate($page);
    }

    /**
     * Upload the XLSX file, store temporarily and return headers preview.
     */
    public function upload(Request $request, Page $page)
    {
        // Log entry point for upload
        Log::info('Dynamic import upload initiated', [
            'page_id' => $page->id,
            'slug' => $page->slug,
        ]);
        abort_unless(Gate::allows('import_' . $page->slug), 403, 'عفواً، لا تملك الصلاحية لاستيراد البيانات.');

        try {
            $request->validate([
                // Increase max file size to 50MB (51200KB) to accommodate larger Excel files and Arabic filenames
                'file' => 'required|file|mimes:xlsx,xls,csv|max:51200',
            ]);
            Log::info('Validation passed for upload request');
        } catch (\Illuminate\Validation\ValidationException $ve) {
            Log::error('Validation failed for upload request', ['errors' => $ve->errors()]);
            throw $ve; // rethrow to let Laravel handle the redirect
        }
        $uuid = (string) Str::uuid();
        $originalName = $request->file('file')->getClientOriginalName();
        $originalExtension = $request->file('file')->getClientOriginalExtension();
        Log::info('Upload request received', [
            'original_name' => $originalName,
            'original_extension' => $originalExtension,
            'uuid' => $uuid,
        ]);

        $originalExtension = $request->file('file')->getClientOriginalExtension();
        // Ensure the temporary directory exists
        if (! Storage::exists('import_tmp')) {
            Storage::makeDirectory('import_tmp');
            Log::info('Created import_tmp directory');
        }
        $filename = "import_{$page->id}_{$uuid}.{$originalExtension}";
        $path = $request->file('file')->storeAs('import_tmp', $filename);
        Log::info('File stored for import', ['stored_path' => $path, 'original_name' => $originalName]);
        // Create import_progress entry (adds optional columns if they exist)
        $progressId = DynamicImportService::createProgressRecord($uuid, $page, $originalName);
        Log::info('Import progress record created', ['progress_id' => $progressId]);

        $service = new DynamicImportService();
        try {
            $headers = $service->extractHeaders(storage_path('app/' . $path));
            Log::info('Headers extracted', ['headers' => $headers]);
        } catch (\Throwable $e) {
            // Log the exception details for debugging
            Log::error('Failed to extract headers from uploaded file', ['exception' => $e]);
            // Cleanup uploaded file
            Storage::delete($path);
            return back()->with('error', 'فشل استخراج رؤوس الأعمدة من الملف. يرجى التأكد من صحة تنسيق الملف.');
        }

        // Store session data linked to UUID
        session(["dynamic_import_{$uuid}" => [
            'path' => $path,
            'page_id' => $page->id,
            'progress_id' => $progressId,
        ]]);

        return view('system.dynamic_data.import_mapping', [
            'page' => $page,
            'headers' => $headers,
            'preview' => [],
            'uuid' => $uuid,
        ]);
    }

    /**
     * Process column mapping, validate rows and return preview/errors.
     */
    public function processMapping(Request $request, Page $page)
    {
        abort_unless(Gate::allows('import_' . $page->slug), 403, 'عفواً، لا تملك الصلاحية لاستيراد البيانات.');

        $uuid = $request->input('uuid');
        $sessionKey = "dynamic_import_{$uuid}";
        $session = session($sessionKey);
        if (! $session || $session['page_id'] !== $page->id) {
            abort(403, 'جلسة الاستيراد غير صالحة أو غير مخصصة لهذا المستخدم.');
        }

        $mapping = $request->input('mapping'); // field_id => header_index (or null)
        // Reject duplicate column mapping
        $headerIndexes = array_filter($mapping, fn($v) => $v !== null && $v !== '');
        if (count($headerIndexes) !== count(array_unique($headerIndexes))) {
            return response()->json(['error' => 'لا يُسمح بربط أكثر من عمود Excel بنفس الحقل.'], 422);
        }
        // Ensure required fields are mapped
        $requiredIds = $page->fields()->where('required', true)->pluck('id')->toArray();
        foreach ($requiredIds as $fid) {
            if (!isset($mapping[$fid]) || $mapping[$fid] === null || $mapping[$fid] === '') {
                return response()->json([
                    'error' => "حقل مطلوب غير مُعرَّف في الـ Mapping (field_id: {$fid})",
                ], 422);
            }
        }

        $service = new DynamicImportService();
        $rows = $service->extractRows(storage_path('app/' . $session['path']));
        $validation = $service->validateRows($rows, $mapping, $page);

        if (empty($validation['errors'])) {
            // Cache valid rows for later confirmation (2 hour TTL)
            Cache::put("import_valid_rows_{$uuid}", $validation['validRows'], now()->addHours(2));
        }

        return response()->json([
            'preview' => $service->previewRows($validation['validRows']),
            'errors'  => $validation['errors'],
        ]);
    }

    /**
     * Confirm import after user approves preview.
     */
    public function confirmImport(Request $request, Page $page)
    {
        abort_unless(Gate::allows('import_' . $page->slug), 403, 'عفواً، لا تملك الصلاحية لاستيراد البيانات.');

        $uuid = $request->input('uuid');
        $sessionKey = "dynamic_import_{$uuid}";
        $session = session($sessionKey);
        if (! $session || $session['page_id'] !== $page->id) {
            abort(403, 'جلسة الاستيراد غير صالحة أو غير مخصصة لهذا المستخدم.');
        }

        $validRows = Cache::pull("import_valid_rows_{$uuid}");
        if (empty($validRows)) {
            return redirect()->route('system.dynamic-data.show', $page->slug)
                ->with('error', 'لا توجد صفوف صالحة للاستيراد.');
        }

        $mapping = $request->input('mapping');
        $service = new DynamicImportService();
        $service->storeRows($validRows, $mapping, $page);

        // Mark progress as completed
        DynamicImportService::finalizeProgressRecord($session['progress_id'], count($validRows));

        // Cleanup temporary file and session data
        Storage::delete($session['path']);
        session()->forget($sessionKey);

        return redirect()->route('system.dynamic-data.show', $page->slug)
            ->with('success', 'تم استيراد البيانات بنجاح.');
    }

    /**
     * Export existing records to XLSX (unchanged).
     */
    public function export(Page $page)
    {
        abort_unless(Gate::allows('export_' . $page->slug), 403, 'عفواً، لا تملك الصلاحية لتصدير البيانات.');
        $records = Record::where('page_id', $page->id)
            ->visibleToUserForModule('dynamic_pages')
            ->with(['values', 'user', 'entity'])
            ->get();
        $fields = $page->fields;
        return Excel::download(new class($records, $fields) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithMapping {
            private $records;
            private $fields;
            public function __construct($records, $fields) { $this->records = $records; $this->fields = $fields; }
            public function collection() { return $this->records; }
            public function headings(): array { $heads = $this->fields->pluck('label')->toArray(); $heads[] = 'بواسطة'; $heads[] = 'تاريخ الإضافة'; return $heads; }
            public function map($record): array { $values = $record->values->pluck('value', 'field_id'); $row = []; foreach ($this->fields as $field) { $row[] = $values[$field->id] ?? '-'; } $row[] = $record->user->name ?? 'مجهول'; $row[] = $record->created_at->format('Y-m-d'); return $row; }
        }, "export_{$page->slug}.xlsx");
    }

    /**
     * Legacy template route – now delegated to service.
     */
    public function template(Page $page)
    {
        abort_unless(Gate::allows('import_' . $page->slug), 403, 'عفواً، لا تملك الصلاحية لتحميل قالب الاستيراد.');
        $service = new DynamicImportService();
        return $service->downloadTemplate($page);
    }
}
?>
