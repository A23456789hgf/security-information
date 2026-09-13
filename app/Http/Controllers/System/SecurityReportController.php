<?php

namespace App\Http\Controllers\System;

use App\Exports\UniversalExport;
use App\Http\Controllers\Controller;
use App\Models\InternalEntity;
use App\Models\SecurityReport;
use Illuminate\Http\Request;

class SecurityReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SecurityReport::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('full_name', 'like', "%{$search}%")
                ->orWhere('report_name', 'like', "%{$search}%")
                ->orWhere('id_number', 'like', "%{$search}%")
                ->orWhere('military_number', 'like', "%{$search}%");
        }

        $perPage = $request->input('per_page', 50);
        $reports = $query->with('entity')->latest()->paginate($perPage);

        return view('system.reports.index', compact('reports'));
    }

    public function export(Request $request)
    {
        $query = SecurityReport::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('full_name', 'like', "%{$search}%")
                ->orWhere('report_name', 'like', "%{$search}%");
        }

        $headings = [
            'ID',
            'الاسم الكامل',
            'موضوع البلاغ',
            'تاريخ البلاغ',
            'الجهة',
            'الرقم الوطني',
            'الرقم العسكري',
        ];

        return (new UniversalExport($query, $headings, function ($row) {
            return [
                $row->id,
                $row->full_name,
                $row->report_name,
                $row->report_date,
                $row->entity->name ?? '-',
                $row->id_number,
                $row->military_number,
            ];
        }))->download('security_reports_'.date('Y-m-d').'.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $entities = InternalEntity::all();
        $personalDataList = \App\Models\PersonalData::pluck('name', 'id');

        return view('system.reports.create', compact('entities', 'personalDataList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'report_name' => 'required|string|max:255',
            'report_date' => 'nullable|date',
            'internal_entity_id' => 'nullable|exists:internal_entities,id',
        ]);

        SecurityReport::create($request->all());

        return redirect()->route('system.security-reports.index')
            ->with('success', 'تم إضافة البلاغ بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(SecurityReport $securityReport)
    {
        return view('system.reports.show', compact('securityReport'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SecurityReport $securityReport)
    {
        $entities = InternalEntity::all();
        $personalDataList = \App\Models\PersonalData::pluck('name', 'id');

        return view('system.reports.edit', compact('securityReport', 'entities', 'personalDataList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SecurityReport $securityReport)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'report_name' => 'required|string|max:255',
            'report_date' => 'nullable|date',
            'internal_entity_id' => 'nullable|exists:internal_entities,id',
        ]);

        $securityReport->update($request->all());

        return redirect()->route('system.security-reports.index')
            ->with('success', 'تم تحديث البلاغ بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SecurityReport $securityReport)
    {
        $securityReport->delete();

        return redirect()->route('system.security-reports.index')
            ->with('success', 'تم حذف البلاغ بنجاح');
    }

    /**
     * Show the import page.
     */
    public function import()
    {
        return view('system.reports.import');
    }

    /**
     * Handle Excel file upload and parse for mapping.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('excel_file');

        try {
            // Use a plain array import to extract data without requiring a custom import class
            $data = \Maatwebsite\Excel\Facades\Excel::toArray([], $file);
            $rows = $data[0] ?? [];

            if (empty($rows)) {
                return response()->json(['success' => false, 'message' => 'الملف فارغ']);
            }

            // Detect headers
            $headers = [];
            while (! empty($rows)) {
                $potentialHeaders = array_shift($rows);
                $validColumns = array_filter($potentialHeaders, function ($h) {
                    return ! is_null($h) && trim((string) $h) !== '';
                });

                if (! empty($validColumns)) {
                    $headers = $potentialHeaders; // First non-empty row assumed as headers
                    break;
                }
            }

            if (empty($headers)) {
                return response()->json(['success' => false, 'message' => 'تعذر العثور على رؤوس أعمدة في الملف']);
            }

            // Save data to session for the next step
            session(['pending_import_data' => $rows]);
            session(['pending_import_headers' => $headers]);

            return response()->json([
                'success' => true,
                'columns' => $headers,
                'preview_data' => array_slice($rows, 0, 5),
                'total_rows' => count($rows),
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'خطأ في معالجة الملف: '.$e->getMessage()]);
        }
    }

    /**
     * Store imported data from database table.
     */
    public function storeImport(Request $request)
    {
        $mapping = $request->input('mapping'); // Array: [field => columnIndex]
        $rows = session('pending_import_data');

        if (empty($rows)) {
            return response()->json(['success' => false, 'message' => 'لا توجد بيانات للمعالجة. يرجى إعادة رفع الملف.']);
        }

        // List of date fields to handle conversions
        $dateFields = ['report_date', 'date_of_birth', 'arrest_date', 'deposit_date', 'date_of_release'];

        $count = 0;
        foreach ($rows as $row) {
            $recordData = [];
            $hasPrimaryData = false;

            foreach ($mapping as $field => $colIndex) {
                if ($colIndex !== '' && isset($row[$colIndex])) {
                    $value = trim($row[$colIndex]);

                    if (in_array($field, $dateFields) && ! empty($value)) {
                        try {
                            $value = date('Y-m-d', strtotime($value));
                        } catch (\Exception $e) {
                            $value = null;
                        }
                    }

                    $recordData[$field] = $value;

                    if (($field === 'full_name' || $field === 'report_name') && ! empty($value)) {
                        $hasPrimaryData = true;
                    }
                }
            }

            if ($hasPrimaryData) {
                SecurityReport::create($recordData);
                $count++;
            }
        }

        // Clear session
        session()->forget(['pending_import_data', 'pending_import_headers']);

        return response()->json([
            'success' => true,
            'message' => "تم استيراد $count سجل بنجاح إلى قاعدة بيانات البلاغات",
            'redirect' => route('system.security-reports.index'),
        ]);
    }

    /**
     * Print single security report.
     */
    public function print(SecurityReport $security_report)
    {
        return view('system.reports.print', ['report' => $security_report]);
    }

    /**
     * Print all security reports listing.
     */
    public function printAll(Request $request)
    {
        $reports = SecurityReport::all();
        return view('system.reports.print', [
            'reports' => $reports,
            'isList' => true,
        ]);
    }
}
