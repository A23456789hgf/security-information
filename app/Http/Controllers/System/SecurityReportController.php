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
        \App\Services\UserScopeService::applyScope($query);

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
        $entities = \App\Services\UserScopeService::getScopedEntities();
        $personalDataList = \App\Services\UserScopeService::applyScope(\App\Models\PersonalData::query())->pluck('name', 'id');

        return view('system.reports.create', compact('entities', 'personalDataList'));
    }

    /**
     * Resolve input fields from personal_data_id or text tags before validation
     */
    private function resolveInputFields(Request $request): array
    {
        $data = $request->all();

        if (!empty($data['personal_data_id'])) {
            if (is_numeric($data['personal_data_id'])) {
                $person = \App\Models\PersonalData::find($data['personal_data_id']);
                if ($person) {
                    $data['full_name'] = !empty($data['full_name']) ? $data['full_name'] : $person->name;
                    $data['military_number'] = !empty($data['military_number']) ? $data['military_number'] : $person->military_number;
                    $data['id_number'] = !empty($data['id_number']) ? $data['id_number'] : $person->national_id_number;
                    if (empty($data['internal_entity_id']) && !empty($person->internal_entity_id)) {
                        $data['internal_entity_id'] = $person->internal_entity_id;
                    }
                    if (empty($data['date_of_birth']) && $person->date_of_birth_gregorian) {
                        $data['date_of_birth'] = $person->date_of_birth_gregorian->format('Y-m-d');
                    }
                }
            } else {
                $person = \App\Models\PersonalData::where('name', $data['personal_data_id'])->first();
                if ($person) {
                    $data['personal_data_id'] = $person->id;
                    $data['full_name'] = $person->name;
                    $data['military_number'] = !empty($data['military_number']) ? $data['military_number'] : $person->military_number;
                    $data['id_number'] = !empty($data['id_number']) ? $data['id_number'] : $person->national_id_number;
                    if (empty($data['internal_entity_id']) && !empty($person->internal_entity_id)) {
                        $data['internal_entity_id'] = $person->internal_entity_id;
                    }
                } else {
                    $data['full_name'] = $data['personal_data_id'];
                    $data['personal_data_id'] = null;
                }
            }
        }

        if (empty($data['full_name']) && !empty($request->full_name)) {
            $data['full_name'] = $request->full_name;
        }

        return $data;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->resolveInputFields($request);

        $validator = \Illuminate\Support\Facades\Validator::make($data, [
            'full_name' => 'required|string|max:255',
            'report_name' => 'required|string|max:255',
            'report_date' => 'nullable|date',
            'internal_entity_id' => 'nullable|exists:internal_entities,id',
        ], [
            'full_name.required' => 'يرجى تحديد الشخص المعني بالبلاغ.',
            'report_name.required' => 'يرجى إدخال موضوع البلاغ.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        SecurityReport::create($data);

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
        $entities = \App\Services\UserScopeService::getScopedEntities();
        $personalDataList = \App\Services\UserScopeService::applyScope(\App\Models\PersonalData::query())->pluck('name', 'id');

        return view('system.reports.edit', compact('securityReport', 'entities', 'personalDataList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SecurityReport $securityReport)
    {
        $data = $this->resolveInputFields($request);

        $validator = \Illuminate\Support\Facades\Validator::make($data, [
            'full_name' => 'required|string|max:255',
            'report_name' => 'required|string|max:255',
            'report_date' => 'nullable|date',
            'internal_entity_id' => 'nullable|exists:internal_entities,id',
        ], [
            'full_name.required' => 'يرجى تحديد الشخص المعني بالبلاغ.',
            'report_name.required' => 'يرجى إدخال موضوع البلاغ.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $securityReport->update($data);

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
            $data = \Maatwebsite\Excel\Facades\Excel::toArray(new class {}, $file);
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
                    $headers = $potentialHeaders;
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
        $security_report->load('entity');
        return view('system.reports.print', ['report' => $security_report]);
    }

    /**
     * Print all security reports listing.
     */
    public function printAll(Request $request)
    {
        $reports = SecurityReport::with('entity')->get();
        return view('system.reports.print', [
            'reports' => $reports,
            'isList' => true,
        ]);
    }
}
