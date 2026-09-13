<?php

namespace App\Http\Controllers\System;

use App\Exports\UniversalExport;
use App\Http\Controllers\Controller;
use App\Models\PersonalData;
use App\Models\Suspect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuspectController extends Controller
{
    public function index(Request $request)
    {
        $query = Suspect::with('personalData');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('personalData', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%$search%")
                        ->orWhere('jihadi_name', 'like', "%$search%");
                })
                    ->orWhere('suspect_number', 'like', "%$search%")
                    ->orWhere('suspicion_type', 'like', "%$search%");
            });
        }

        if ($request->filled('personal_data_id')) {
            $query->where('personal_data_id', $request->personal_data_id);
        }

        if ($sort = $request->input('sort')) {
            $direction = $request->input('direction', 'asc');
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('suspicion_date', 'desc');
        }

        $perPage = $request->input('per_page', 50);
        $suspects = $query->paginate($perPage)->withQueryString();
        $personalDataList = PersonalData::select('id', 'name', 'jihadi_name', 'military_number')->orderBy('name')->get();

        return view('system.cases.suspicious.index', compact('suspects', 'personalDataList'));
    }

    public function export(Request $request)
    {
        $query = Suspect::with(['personalData']);

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
            'تاريخ الاشتباه',
            'نوع الاشتباه',
            'المستوى',
            'تم الضبط؟',
        ];

        return (new UniversalExport($query, $headings, function ($row) {
            return [
                $row->id,
                $row->personalData->name ?? '-',
                $row->personalData->jihadi_name ?? '-',
                $row->suspicion_date,
                $row->suspicion_type,
                $row->suspicion_level,
                $row->is_arrested ? 'نعم' : 'لا',
            ];
        }))->download('suspicious_'.date('Y-m-d').'.xlsx');
    }

    public function create()
    {
        $personalDataList = PersonalData::orderBy('name')->pluck('name', 'id');

        return view('system.cases.suspicious.create', compact('personalDataList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'personal_data_id' => 'required|exists:personal_data,id',
            'suspect_number' => 'nullable|string|max:255',
            'suspicion_date' => 'nullable|date',
            'suspicion_type' => 'nullable|string|max:255',
            'suspicion_location' => 'nullable|string|max:255',
            'suspicion_level' => 'nullable|string|max:255',
            'information_source' => 'nullable|string|max:255',
            'is_arrested' => 'required|boolean',
            'suspicion_details' => 'nullable|string',
            'monitoring_results' => 'nullable|string',
            'suspicion_follower' => 'nullable|string|max:255',
            'suspicion_report' => 'nullable|string|max:255',
            'observations' => 'nullable|string',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx|max:10240',
        ]);

        $data = $request->except('attachments');
        $data['attachments'] = [];

        if ($request->hasFile('attachments')) {
            $attachmentPaths = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('suspects', 'public');
                $attachmentPaths[] = $path;
            }
            $data['attachments'] = $attachmentPaths;
        }

        Suspect::create($data);

        return redirect()->route('system.suspicious.index')->with('success', 'تمت إضافة المشتبه به بنجاح');
    }

    public function edit(Suspect $suspicious)
    {
        $personalDataList = PersonalData::orderBy('name')->pluck('name', 'id');

        return view('system.cases.suspicious.edit', compact('suspicious', 'personalDataList'));
    }

    public function update(Request $request, Suspect $suspicious)
    {
        $request->validate([
            'personal_data_id' => 'required|exists:personal_data,id',
            'suspect_number' => 'nullable|string|max:255',
            'suspicion_date' => 'nullable|date',
            'suspicion_type' => 'nullable|string|max:255',
            'suspicion_location' => 'nullable|string|max:255',
            'suspicion_level' => 'nullable|string|max:255',
            'information_source' => 'nullable|string|max:255',
            'is_arrested' => 'required|boolean',
            'suspicion_details' => 'nullable|string',
            'monitoring_results' => 'nullable|string',
            'suspicion_follower' => 'nullable|string|max:255',
            'suspicion_report' => 'nullable|string|max:255',
            'observations' => 'nullable|string',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx|max:10240',
        ]);

        $data = $request->except('attachments');

        if ($request->hasFile('attachments')) {
            $attachmentPaths = $suspicious->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('suspects', 'public');
                $attachmentPaths[] = $path;
            }
            $data['attachments'] = $attachmentPaths;
        }

        $suspicious->update($data);

        return redirect()->route('system.suspicious.index')->with('success', 'تم تحديث بيانات المشتبه به بنجاح');
    }

    public function destroy(Suspect $suspicious)
    {
        if ($suspicious->attachments) {
            foreach ($suspicious->attachments as $filePath) {
                Storage::disk('public')->delete($filePath);
            }
        }

        $suspicious->delete();

        return redirect()->route('system.suspicious.index')->with('success', 'تم حذف المشتبه به بنجاح');
    }

    public function deleteAttachment(Request $request, Suspect $suspicious)
    {
        $fileIndex = $request->input('file_index');
        $attachments = $suspicious->attachments ?? [];

        if (isset($attachments[$fileIndex])) {
            Storage::disk('public')->delete($attachments[$fileIndex]);
            unset($attachments[$fileIndex]);
            $suspicious->update(['attachments' => array_values($attachments)]);
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
     * Print single suspect record.
     */
    public function print(Suspect $suspicious)
    {
        $suspicious->load(['personalData', 'caseType', 'governorate', 'directorate']);
        return view('system.cases.suspicious.print', compact('suspicious'));
    }

    /**
     * Print all suspicious records listing.
     */
    public function printAll(Request $request)
    {
        $suspects = Suspect::with(['personalData', 'caseType'])->get();
        return view('system.cases.suspicious.print', [
            'suspects' => $suspects,
            'isList' => true,
        ]);
    }
}
