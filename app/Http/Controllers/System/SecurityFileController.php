<?php

namespace App\Http\Controllers\System;

use App\Exports\UniversalExport;
use App\Http\Controllers\Controller;
use App\Models\InternalEntity;
use App\Models\PersonalData;
use App\Models\SecurityFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SecurityFileController extends Controller
{
    /**
     * Display a listing of the security files.
     */
    public function index(Request $request)
    {
        $query = SecurityFile::with(['personalData', 'preparingEntity']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('file_number', 'like', "%{$search}%")
                    ->orWhere('file_preparer', 'like', "%{$search}%")
                    ->orWhere('summary_preparer', 'like', "%{$search}%")
                    ->orWhereHas('personalData', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%")
                            ->orWhere('jihadi_name', 'like', "%{$search}%")
                            ->orWhere('military_number', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('preparing_entity_id')) {
            $query->where('preparing_entity_id', $request->preparing_entity_id);
        }

        if ($request->filled('personal_data_id')) {
            $query->where('personal_data_id', $request->personal_data_id);
        }

        $perPage = $request->input('per_page', 25);
        $securityFiles = $query->latest()->paginate($perPage)->withQueryString();
        $entities = InternalEntity::orderBy('name')->pluck('name', 'id');
        $personalDataList = PersonalData::select('id', 'name', 'jihadi_name', 'military_number')->orderBy('name')->get();
        $totalCount = SecurityFile::count();

        return view('system.security_files.index', compact('securityFiles', 'entities', 'personalDataList', 'totalCount'));
    }

    /**
     * Show the form for creating a new security file.
     */
    public function create(Request $request)
    {
        $allPeople = PersonalData::select('id', 'name', 'jihadi_name', 'military_number')->get();
        $entities = InternalEntity::orderBy('name')->pluck('name', 'id');
        $selectedPersonId = $request->input('personal_data_id');

        return view('system.security_files.create', compact('allPeople', 'entities', 'selectedPersonId'));
    }

    /**
     * Store a newly created security file in storage.
     */
    public function store(Request $request)
    {
        $this->sanitizeFileInputs($request);

        $request->validate([
            'personal_data_id' => 'required|exists:personal_data,id',
            'file_number' => 'nullable|string|max:255',
            'file_date_gregorian' => 'nullable|date',
            'file_date_hijri' => 'nullable|string|max:255',
            'preparing_entity_id' => 'nullable|exists:internal_entities,id',
            'file_preparer' => 'nullable|string|max:255',
            'highlight_summary' => 'nullable|string',
            'jihadist_status' => 'nullable|string',
            'summary_preparer' => 'nullable|string|max:255',
            'summary_date_gregorian' => 'nullable|date',
            'summary_date_hijri' => 'nullable|string|max:255',
            'file_attachments' => 'nullable|array',
            'file_attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp,zip,rar,txt|max:20480',
            'summary_attachments' => 'nullable|array',
            'summary_attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp,zip,rar,txt|max:20480',
        ], [
            'personal_data_id.required' => 'يرجى اختيار الفرد المرتبط بالملف من البيانات الشخصية.',
            'file_attachments.*.file' => 'الملف المرفق في مرفقات الملف غير صالح.',
            'file_attachments.*.mimes' => 'صيغة الملف غير مدعومة في مرفقات الملف. الصيغ المسموحة: pdf, doc, docx, jpg, jpeg, png, webp',
            'file_attachments.*.max' => 'حجم الملف في مرفقات الملف يجب ألا يتجاوز 20 ميجابايت.',
            'summary_attachments.*.file' => 'الملف المرفق في مرفقات الملخص غير صالح.',
            'summary_attachments.*.mimes' => 'صيغة الملف غير مدعومة في مرفقات الملخص. الصيغ المسموحة: pdf, doc, docx, jpg, jpeg, png, webp',
            'summary_attachments.*.max' => 'حجم الملف في مرفقات الملخص يجب ألا يتجاوز 20 ميجابايت.',
        ]);

        $data = $request->except(['file_attachments', 'summary_attachments']);

        // Handle file attachments upload
        if ($request->hasFile('file_attachments')) {
            $filePaths = [];
            foreach ($request->file('file_attachments') as $file) {
                if ($file && $file->isValid()) {
                    $filePaths[] = $file->store('security_files/files', 'public');
                }
            }
            $data['file_attachments'] = $filePaths;
        }

        // Handle summary attachments upload
        if ($request->hasFile('summary_attachments')) {
            $summaryPaths = [];
            foreach ($request->file('summary_attachments') as $file) {
                if ($file && $file->isValid()) {
                    $summaryPaths[] = $file->store('security_files/summaries', 'public');
                }
            }
            $data['summary_attachments'] = $summaryPaths;
        }

        SecurityFile::create($data);

        return redirect()->route('system.security-files.index')
            ->with('success', 'تم إضافة الملف الأمني بنجاح');
    }

    /**
     * Display the specified security file.
     */
    public function show(SecurityFile $securityFile)
    {
        $securityFile->load(['personalData.governorate', 'personalData.directorate', 'personalData.village', 'preparingEntity']);

        return view('system.security_files.show', compact('securityFile'));
    }

    /**
     * Show the form for editing the specified security file.
     */
    public function edit(SecurityFile $securityFile)
    {
        $allPeople = PersonalData::select('id', 'name', 'jihadi_name', 'military_number')->get();
        $entities = InternalEntity::orderBy('name')->pluck('name', 'id');

        return view('system.security_files.edit', compact('securityFile', 'allPeople', 'entities'));
    }

    /**
     * Update the specified security file in storage.
     */
    public function update(Request $request, SecurityFile $securityFile)
    {
        $this->sanitizeFileInputs($request);

        $request->validate([
            'personal_data_id' => 'required|exists:personal_data,id',
            'file_number' => 'nullable|string|max:255',
            'file_date_gregorian' => 'nullable|date',
            'file_date_hijri' => 'nullable|string|max:255',
            'preparing_entity_id' => 'nullable|exists:internal_entities,id',
            'file_preparer' => 'nullable|string|max:255',
            'highlight_summary' => 'nullable|string',
            'jihadist_status' => 'nullable|string',
            'summary_preparer' => 'nullable|string|max:255',
            'summary_date_gregorian' => 'nullable|date',
            'summary_date_hijri' => 'nullable|string|max:255',
            'file_attachments' => 'nullable|array',
            'file_attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp,zip,rar,txt|max:20480',
            'summary_attachments' => 'nullable|array',
            'summary_attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp,zip,rar,txt|max:20480',
        ], [
            'personal_data_id.required' => 'يرجى اختيار الفرد المرتبط بالملف من البيانات الشخصية.',
            'file_attachments.*.file' => 'الملف المرفق في مرفقات الملف غير صالح.',
            'file_attachments.*.mimes' => 'صيغة الملف غير مدعومة في مرفقات الملف. الصيغ المسموحة: pdf, doc, docx, jpg, jpeg, png, webp',
            'file_attachments.*.max' => 'حجم الملف في مرفقات الملف يجب ألا يتجاوز 20 ميجابايت.',
            'summary_attachments.*.file' => 'الملف المرفق في مرفقات الملخص غير صالح.',
            'summary_attachments.*.mimes' => 'صيغة الملف غير مدعومة في مرفقات الملخص. الصيغ المسموحة: pdf, doc, docx, jpg, jpeg, png, webp',
            'summary_attachments.*.max' => 'حجم الملف في مرفقات الملخص يجب ألا يتجاوز 20 ميجابايت.',
        ]);

        $data = $request->except(['file_attachments', 'summary_attachments']);

        // Preserve existing or append new file attachments
        $filePaths = $securityFile->file_attachments ?? [];
        if ($request->hasFile('file_attachments')) {
            foreach ($request->file('file_attachments') as $file) {
                if ($file && $file->isValid()) {
                    $filePaths[] = $file->store('security_files/files', 'public');
                }
            }
        }
        $data['file_attachments'] = $filePaths;

        // Preserve existing or append new summary attachments
        $summaryPaths = $securityFile->summary_attachments ?? [];
        if ($request->hasFile('summary_attachments')) {
            foreach ($request->file('summary_attachments') as $file) {
                if ($file && $file->isValid()) {
                    $summaryPaths[] = $file->store('security_files/summaries', 'public');
                }
            }
        }
        $data['summary_attachments'] = $summaryPaths;

        $securityFile->update($data);

        return redirect()->route('system.security-files.index')
            ->with('success', 'تم تحديث الملف الأمني بنجاح');
    }

    /**
     * Filter out empty or un-uploaded file items from input arrays.
     */
    private function sanitizeFileInputs(Request $request)
    {
        foreach (['file_attachments', 'summary_attachments'] as $field) {
            if ($request->hasFile($field)) {
                $files = array_filter((array) $request->file($field), function ($file) {
                    return $file && $file->isValid();
                });
                if (empty($files)) {
                    $request->files->remove($field);
                } else {
                    $request->files->set($field, array_values($files));
                }
            }
        }
    }

    /**
     * Remove the specified security file from storage.
     */
    public function destroy(SecurityFile $securityFile)
    {
        // Clean up attached files
        if ($securityFile->file_attachments) {
            foreach ($securityFile->file_attachments as $path) {
                Storage::disk('public')->delete($path);
            }
        }
        if ($securityFile->summary_attachments) {
            foreach ($securityFile->summary_attachments as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $securityFile->delete();

        return redirect()->route('system.security-files.index')
            ->with('success', 'تم حذف الملف الأمني بنجاح');
    }

    /**
     * Delete single attachment from security file.
     */
    public function deleteAttachment(Request $request, SecurityFile $securityFile)
    {
        $type = $request->input('type'); // 'file' or 'summary'
        $index = $request->input('index');

        if ($type === 'file') {
            $attachments = $securityFile->file_attachments ?? [];
            if (isset($attachments[$index])) {
                Storage::disk('public')->delete($attachments[$index]);
                unset($attachments[$index]);
                $securityFile->update(['file_attachments' => array_values($attachments)]);
            }
        } elseif ($type === 'summary') {
            $attachments = $securityFile->summary_attachments ?? [];
            if (isset($attachments[$index])) {
                Storage::disk('public')->delete($attachments[$index]);
                unset($attachments[$index]);
                $securityFile->update(['summary_attachments' => array_values($attachments)]);
            }
        }

        return back()->with('success', 'تم حذف المرفق بنجاح');
    }

    /**
     * AJAX endpoint to fetch person details.
     */
    public function getPersonDetails($id)
    {
        $person = PersonalData::with(['governorate', 'directorate', 'subArea', 'village', 'bloodType', 'maritalStatus'])->findOrFail($id);

        $address = implode(' / ', array_filter([
            $person->governorate->name ?? null,
            $person->directorate->name ?? null,
            $person->subArea->name ?? null,
            $person->village->name ?? null,
        ]));

        return response()->json([
            'id' => $person->id,
            'name' => $person->name,
            'jihadi_name' => $person->jihadi_name ?? 'غير محدد',
            'military_number' => $person->military_number ?? 'غير مسجل',
            'national_id_number' => $person->national_id_number ?? 'غير مسجل',
            'photo_url' => $person->photo_url,
            'address' => $address ?: 'غير محدد',
        ]);
    }

    /**
     * Print single security file.
     */
    public function print(SecurityFile $securityFile)
    {
        $securityFile->load(['personalData.governorate', 'personalData.directorate', 'preparingEntity']);

        return view('system.security_files.print', compact('securityFile'));
    }

    /**
     * Print all security files listing.
     */
    public function printAll(Request $request)
    {
        $securityFiles = SecurityFile::with(['personalData', 'preparingEntity'])->get();

        return view('system.security_files.print', [
            'securityFiles' => $securityFiles,
            'isList' => true,
        ]);
    }

    /**
     * Export security files to Excel.
     */
    public function export(Request $request)
    {
        $query = SecurityFile::with(['personalData', 'preparingEntity']);

        if ($search = $request->input('search')) {
            $query->where('file_number', 'like', "%{$search}%")
                ->orWhereHas('personalData', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        }

        $records = $query->get()->map(function ($item) {
            return [
                'رقم الملف الأمني' => $item->file_number ?? '-',
                'اسم الفرد' => $item->personalData->name ?? '-',
                'الاسم الجهادي' => $item->personalData->jihadi_name ?? '-',
                'الرقم العسكري' => $item->personalData->military_number ?? '-',
                'تاريخ الملف' => $item->file_date_gregorian ? $item->file_date_gregorian->format('Y-m-d') : '-',
                'تاريخ الملف (هجري)' => $item->file_date_hijri ?? '-',
                'الجهة المعدة' => $item->preparingEntity->name ?? '-',
                'معد الملف' => $item->file_preparer ?? '-',
                'معد التلخيص' => $item->summary_preparer ?? '-',
                'تاريخ التلخيص' => $item->summary_date_gregorian ? $item->summary_date_gregorian->format('Y-m-d') : '-',
                'أبرز ملخص الملف' => $item->highlight_summary ?? '-',
                'الوضع الجهادي' => $item->jihadist_status ?? '-',
            ];
        });

        return (new UniversalExport($records, 'كشف_الملفات_الأمنية'))->download('Security_Files_Export.xlsx');
    }
}
