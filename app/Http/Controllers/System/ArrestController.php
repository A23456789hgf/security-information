<?php

namespace App\Http\Controllers\System;

use App\Exports\UniversalExport;
use App\Http\Controllers\Controller;
use App\Models\Arrest;
use App\Models\ArrestingAuthority;
use App\Models\CaseType;
use App\Models\PersonalData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArrestController extends Controller
{
    public function index(Request $request)
    {
        $query = Arrest::with(['personalData', 'arrestingAuthority', 'caseType']);
        \App\Services\UserScopeService::applyScope($query);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('personalData', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%$search%")
                        ->orWhere('jihadi_name', 'like', "%$search%");
                })
                    ->orWhereHas('caseType', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('arrestingAuthority', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', "%$search%");
                    });
            });
        }

        if ($request->filled('personal_data_id')) {
            $query->where('personal_data_id', $request->personal_data_id);
        }

        if ($sort = $request->input('sort')) {
            $direction = $request->input('direction', 'asc');
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('arrest_date', 'desc');
        }

        $perPage = $request->input('per_page', 50);
        $arrests = $query->paginate($perPage)->withQueryString();
        $personalDataList = PersonalData::select('id', 'name', 'jihadi_name', 'military_number')->orderBy('name')->get();

        return view('system.cases.arrests.index', compact('arrests', 'personalDataList'));
    }

    public function export(Request $request)
    {
        $query = Arrest::with(['personalData', 'arrestingAuthority', 'caseType']);

        if ($search = $request->input('search')) {
            $query->whereHas('personalData', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('jihadi_name', 'like', "%$search%");
            });
        }

        $headings = [
            'ID',
            'الاسم الكامل',
            'الاسم الجهادي',
            'جهة الضبط',
            'نوع القضية',
            'تاريخ الضبط',
            'حالة القضية',
        ];

        return (new UniversalExport($query, $headings, function ($row) {
            return [
                $row->id,
                $row->personalData->name ?? '-',
                $row->personalData->jihadi_name ?? '-',
                $row->arrestingAuthority->name ?? '-',
                $row->caseType->name ?? '-',
                $row->arrest_date,
                $row->status,
            ];
        }))->download('arrests_'.date('Y-m-d').'.xlsx');
    }

    public function create(Request $request)
    {
        $personalDataList = PersonalData::orderBy('name')->pluck('name', 'id');
        $arrestingAuthorities = ArrestingAuthority::orderBy('name')->pluck('name', 'id');
        $caseTypes = CaseType::orderBy('name')->pluck('name', 'id');

        $selectedPersonalDataId = $request->query('personal_data_id');

        return view('system.cases.arrests.create', compact(
            'personalDataList',
            'arrestingAuthorities',
            'caseTypes',
            'selectedPersonalDataId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'personal_data_id' => 'required|exists:personal_data,id',
            'arresting_authority_id' => 'nullable|exists:arresting_authorities,id',
            'case_type_id' => 'nullable|exists:case_types,id',
            'arrest_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'arrest_location' => 'nullable|string|max:255',
            'case_details' => 'nullable|string',
            'seized_items' => 'nullable|string',
            'case_follow_up_results' => 'nullable|string',
            'related_information' => 'nullable|string',
            'status' => 'nullable|string|max:255',
            'duration_period' => 'nullable|string|max:255',
            'conclusion' => 'nullable|string',
            'investigation_reports' => 'nullable|string',
            'release_date' => 'nullable|date',
            'release_reason' => 'nullable|string',
            'notes' => 'nullable|string',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx|max:10240',
        ]);

        $data = $request->except('attachments');
        $data['attachments'] = [];

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $attachmentPaths = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('arrests', 'public');
                $attachmentPaths[] = $path;
            }
            $data['attachments'] = $attachmentPaths;
        }

        Arrest::create($data);

        return redirect()->route('system.arrests.index')->with('success', 'تمت إضافة الاعتقال بنجاح');
    }

    public function edit(Arrest $arrest)
    {
        $personalDataList = PersonalData::orderBy('name')->pluck('name', 'id');
        $arrestingAuthorities = ArrestingAuthority::orderBy('name')->pluck('name', 'id');
        $caseTypes = CaseType::orderBy('name')->pluck('name', 'id');

        return view('system.cases.arrests.edit', compact(
            'arrest',
            'personalDataList',
            'arrestingAuthorities',
            'caseTypes'
        ));
    }

    public function update(Request $request, Arrest $arrest)
    {
        $request->validate([
            'personal_data_id' => 'required|exists:personal_data,id',
            'arresting_authority_id' => 'nullable|exists:arresting_authorities,id',
            'case_type_id' => 'nullable|exists:case_types,id',
            'arrest_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'arrest_location' => 'nullable|string|max:255',
            'case_details' => 'nullable|string',
            'seized_items' => 'nullable|string',
            'case_follow_up_results' => 'nullable|string',
            'related_information' => 'nullable|string',
            'status' => 'nullable|string|max:255',
            'duration_period' => 'nullable|string|max:255',
            'conclusion' => 'nullable|string',
            'investigation_reports' => 'nullable|string',
            'release_date' => 'nullable|date',
            'release_reason' => 'nullable|string',
            'notes' => 'nullable|string',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx|max:10240',
        ]);

        $data = $request->except('attachments');

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $attachmentPaths = $arrest->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('arrests', 'public');
                $attachmentPaths[] = $path;
            }
            $data['attachments'] = $attachmentPaths;
        }

        $arrest->update($data);

        return redirect()->route('system.arrests.index')->with('success', 'تم تحديث الاعتقال بنجاح');
    }

    public function destroy(Arrest $arrest)
    {
        // Delete associated files
        if ($arrest->attachments) {
            foreach ($arrest->attachments as $filePath) {
                Storage::disk('public')->delete($filePath);
            }
        }

        $arrest->delete();

        return redirect()->route('system.arrests.index')->with('success', 'تم حذف الاعتقال بنجاح');
    }

    public function deleteAttachment(Request $request, Arrest $arrest)
    {
        $fileIndex = $request->input('file_index');
        $attachments = $arrest->attachments ?? [];

        if (isset($attachments[$fileIndex])) {
            Storage::disk('public')->delete($attachments[$fileIndex]);
            unset($attachments[$fileIndex]);
            $arrest->update(['attachments' => array_values($attachments)]);
        }

        return back()->with('success', 'تم حذف المرفق بنجاح');
    }

    public function getPersonalData($id)
    {
        $person = PersonalData::with([
            'bloodType',
            'qualification',
            'governorate',
            'directorate',
            'subArea',
            'village',
        ])->findOrFail($id);

        return response()->json([
            'jihadi_name' => $person->jihadi_name ?? '',
            'date_of_birth' => $person->date_of_birth_gregorian ? $person->date_of_birth_gregorian->format('Y-m-d') : '',
            'blood_type' => $person->bloodType->type ?? '',
            'photo_url' => $person->photo_url,
            'governorate' => $person->governorate->name ?? '',
            'directorate' => $person->directorate->name ?? '',
            'sub_area' => $person->subArea->name ?? '',
            'village' => $person->village->name ?? '',
            'qualification' => $person->qualification->name ?? '',
        ]);
    }

    /**
     * Print single arrest record.
     */
    public function print(Arrest $arrest)
    {
        $arrest->load(['personalData', 'arrestingAuthority', 'caseType', 'governorate', 'directorate']);
        return view('system.cases.arrests.print', compact('arrest'));
    }

    /**
     * Print all arrests listing.
     */
    public function printAll(Request $request)
    {
        $arrests = Arrest::with(['personalData', 'arrestingAuthority', 'caseType'])->get();
        return view('system.cases.arrests.print', [
            'arrests' => $arrests,
            'isList' => true,
        ]);
    }
}
