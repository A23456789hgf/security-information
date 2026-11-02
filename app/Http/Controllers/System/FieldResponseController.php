<?php

namespace App\Http\Controllers\System;

use App\Exports\UniversalExport;
use App\Http\Controllers\Controller;
use App\Models\FieldResponse;
use App\Models\InternalEntity;
use App\Models\PersonalData;
use App\Services\UserScopeService;
use Illuminate\Http\Request;

class FieldResponseController extends Controller
{
    /**
     * Display a listing of the field responses.
     */
    public function index(Request $request)
    {
        $query = FieldResponse::query()->with(['personalData', 'entity', 'user']);
        UserScopeService::applyScope($query);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('field_response', 'like', "%{$search}%")
                  ->orWhere('source', 'like', "%{$search}%")
                  ->orWhere('source_job', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('personalData', function ($p) use ($search) {
                      $p->where('name', 'like', "%{$search}%")
                        ->orWhere('national_id_number', 'like', "%{$search}%")
                        ->orWhere('military_number', 'like', "%{$search}%");
                  });
            });
        }

        $perPage = $request->input('per_page', 50);
        $responses = $query->latest()->paginate($perPage);

        return view('system.field_responses.index', compact('responses'));
    }

    /**
     * Show the form for creating a new field response.
     */
    public function create()
    {
        $entities = UserScopeService::getScopedEntities();
        $personalDataList = UserScopeService::applyScope(PersonalData::query())
            ->orderBy('name')
            ->get(['id', 'name', 'national_id_number']);

        return view('system.field_responses.create', compact('entities', 'personalDataList'));
    }

    /**
     * Store a newly created field response in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'personal_data_id'   => 'required|exists:personal_data,id',
            'field_response'     => 'required|string',
            'response_date'      => 'nullable|date',
            'source'             => 'nullable|string|max:255',
            'source_job'         => 'nullable|string|max:255',
            'notes'              => 'nullable|string',
            'internal_entity_id' => 'nullable|exists:internal_entities,id',
        ]);

        if (empty($validated['internal_entity_id'])) {
            $person = PersonalData::find($validated['personal_data_id']);
            if ($person && !empty($person->internal_entity_id)) {
                $validated['internal_entity_id'] = $person->internal_entity_id;
            }
        }

        $validated['created_by'] = auth()->id();

        FieldResponse::create($validated);

        return redirect()->route('system.field-responses.index')
            ->with('success', 'تم إضافة الرد الميداني بنجاح');
    }

    /**
     * Display the specified field response.
     */
    public function show(FieldResponse $fieldResponse)
    {
        $fieldResponse->load(['personalData', 'entity', 'user']);

        return view('system.field_responses.show', compact('fieldResponse'));
    }

    /**
     * Show the form for editing the specified field response.
     */
    public function edit(FieldResponse $fieldResponse)
    {
        $entities = UserScopeService::getScopedEntities();
        $personalDataList = UserScopeService::applyScope(PersonalData::query())
            ->orderBy('name')
            ->get(['id', 'name', 'national_id_number']);

        $fieldResponse->load(['personalData', 'entity']);

        return view('system.field_responses.edit', compact('fieldResponse', 'entities', 'personalDataList'));
    }

    /**
     * Update the specified field response in storage.
     */
    public function update(Request $request, FieldResponse $fieldResponse)
    {
        $validated = $request->validate([
            'personal_data_id'   => 'required|exists:personal_data,id',
            'field_response'     => 'required|string',
            'response_date'      => 'nullable|date',
            'source'             => 'nullable|string|max:255',
            'source_job'         => 'nullable|string|max:255',
            'notes'              => 'nullable|string',
            'internal_entity_id' => 'nullable|exists:internal_entities,id',
        ]);

        $fieldResponse->update($validated);

        return redirect()->route('system.field-responses.index')
            ->with('success', 'تم تحديث الرد الميداني بنجاح');
    }

    /**
     * Remove the specified field response from storage.
     */
    public function destroy(FieldResponse $fieldResponse)
    {
        $fieldResponse->delete();

        return redirect()->route('system.field-responses.index')
            ->with('success', 'تم حذف الرد الميداني بنجاح');
    }

    /**
     * Get JSON details and photo URL for personal data selection.
     */
    public function getPersonalData($id)
    {
        $person = PersonalData::with([
            'entity',
            'governorate',
            'directorate',
            'subArea',
            'village',
            'qualification',
            'bloodType',
        ])->find($id);

        if (!$person) {
            return response()->json(['success' => false, 'message' => 'الشخص غير موجود'], 404);
        }

        $photoUrl = null;
        if (!empty($person->photo_path)) {
            $photoUrl = asset('storage/' . $person->photo_path);
        } elseif (!empty($person->photo)) {
            $photoUrl = asset('storage/' . $person->photo);
        }

        return response()->json([
            'success' => true,
            'person'  => [
                'id'                 => $person->id,
                'name'               => $person->name,
                'national_id_number' => $person->national_id_number ?? 'غير مدخل',
                'military_number'    => $person->military_number ?? 'غير مدخل',
                'jihadist_number'    => $person->jihadist_number ?? ($person->jihadi_name ?? 'غير مدخل'),
                'job_title'          => $person->job_title ?? ($person->job ?? 'غير مدخل'),
                'entity_name'        => $person->entity->name ?? 'غير محدد',
                'governorate'        => $person->governorate->name ?? '-',
                'directorate'        => $person->directorate->name ?? '-',
                'sub_area'           => $person->subArea->name ?? '-',
                'village'            => $person->village->name ?? '-',
                'qualification'      => $person->qualification->name ?? '-',
                'blood_type'         => $person->bloodType->type ?? '-',
                'date_of_birth'      => $person->date_of_birth_gregorian ? $person->date_of_birth_gregorian->format('Y-m-d') : '-',
                'photo_url'          => $photoUrl,
            ],
        ]);
    }

    /**
     * Print a single field response report.
     */
    public function print(FieldResponse $fieldResponse)
    {
        $fieldResponse->load(['personalData', 'entity', 'user']);

        return view('system.field_responses.print', [
            'response' => $fieldResponse,
            'isList'   => false,
        ]);
    }

    /**
     * Print all or filtered field responses.
     */
    public function printAll(Request $request)
    {
        $query = FieldResponse::query()->with(['personalData', 'entity', 'user']);
        UserScopeService::applyScope($query);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('field_response', 'like', "%{$search}%")
                  ->orWhere('source', 'like', "%{$search}%")
                  ->orWhere('source_job', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('personalData', function ($p) use ($search) {
                      $p->where('name', 'like', "%{$search}%")
                        ->orWhere('national_id_number', 'like', "%{$search}%");
                  });
            });
        }

        $responses = $query->latest()->get();

        return view('system.field_responses.print', [
            'responses' => $responses,
            'isList'    => true,
        ]);
    }

    /**
     * Export field responses to Excel.
     */
    public function export(Request $request)
    {
        $query = FieldResponse::query()->with(['personalData', 'entity']);
        UserScopeService::applyScope($query);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('field_response', 'like', "%{$search}%")
                  ->orWhere('source', 'like', "%{$search}%")
                  ->orWhere('source_job', 'like', "%{$search}%")
                  ->orWhereHas('personalData', function ($p) use ($search) {
                      $p->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $headings = [
            'م',
            'اسم الشخص المعني',
            'الرقم الوطني',
            'الرد الميداني',
            'تاريخ الرد',
            'المصدر',
            'عمل المصدر',
            'الجهة',
            'الملاحظات',
        ];

        return (new UniversalExport($query, $headings, function ($row) {
            return [
                $row->id,
                $row->personalData->name ?? '-',
                $row->personalData->national_id_number ?? '-',
                $row->field_response,
                $row->response_date ? $row->response_date->format('Y-m-d') : '-',
                $row->source ?? '-',
                $row->source_job ?? '-',
                $row->entity->name ?? '-',
                $row->notes ?? '-',
            ];
        }))->download('field_responses_' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Show import page.
     */
    public function import()
    {
        return view('system.field_responses.import');
    }

    /**
     * Upload and parse Excel/CSV for column mapping.
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

            $headers = [];
            while (!empty($rows)) {
                $potentialHeaders = array_shift($rows);
                $validColumns = array_filter($potentialHeaders, function ($h) {
                    return !is_null($h) && trim((string) $h) !== '';
                });

                if (!empty($validColumns)) {
                    $headers = $potentialHeaders;
                    break;
                }
            }

            if (empty($headers)) {
                return response()->json(['success' => false, 'message' => 'تعذر العثور على رؤوس أعمدة في الملف']);
            }

            session(['pending_field_responses_import_data' => $rows]);
            session(['pending_field_responses_import_headers' => $headers]);

            return response()->json([
                'success'      => true,
                'columns'      => $headers,
                'preview_data' => array_slice($rows, 0, 5),
                'total_rows'   => count($rows),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'خطأ في معالجة الملف: ' . $e->getMessage()]);
        }
    }

    /**
     * Save imported rows into database.
     */
    public function storeImport(Request $request)
    {
        $mapping = $request->input('mapping');
        $rows = session('pending_field_responses_import_data');

        if (empty($rows)) {
            return response()->json(['success' => false, 'message' => 'لا توجد بيانات للمعالجة. يرجى إعادة رفع الملف.']);
        }

        $count = 0;
        foreach ($rows as $row) {
            $recordData = [
                'created_by' => auth()->id(),
            ];
            $personName = null;
            $nationalId = null;

            foreach ($mapping as $field => $colIndex) {
                if ($colIndex !== '' && isset($row[$colIndex])) {
                    $val = trim($row[$colIndex]);

                    if ($field === 'person_name') {
                        $personName = $val;
                    } elseif ($field === 'national_id_number') {
                        $nationalId = $val;
                    } elseif ($field === 'response_date' && !empty($val)) {
                        try {
                            $recordData['response_date'] = date('Y-m-d', strtotime($val));
                        } catch (\Exception $e) {
                            $recordData['response_date'] = null;
                        }
                    } else {
                        $recordData[$field] = $val;
                    }
                }
            }

            // Find or link personal_data_id
            $person = null;
            if (!empty($nationalId)) {
                $person = PersonalData::where('national_id_number', $nationalId)->first();
            }
            if (!$person && !empty($personName)) {
                $person = PersonalData::where('name', 'like', "%{$personName}%")->first();
            }

            if ($person) {
                $recordData['personal_data_id'] = $person->id;
                if (empty($recordData['internal_entity_id']) && !empty($person->internal_entity_id)) {
                    $recordData['internal_entity_id'] = $person->internal_entity_id;
                }
            }

            if (!empty($recordData['personal_data_id']) && !empty($recordData['field_response'])) {
                FieldResponse::create($recordData);
                $count++;
            }
        }

        session()->forget(['pending_field_responses_import_data', 'pending_field_responses_import_headers']);

        return response()->json([
            'success'  => true,
            'message'  => "تم استيراد {$count} سجل بنجاح إلى جدول الرد الميداني",
            'redirect' => route('system.field-responses.index'),
        ]);
    }
}
