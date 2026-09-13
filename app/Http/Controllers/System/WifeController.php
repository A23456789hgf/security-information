<?php

namespace App\Http\Controllers\System;

use App\Exports\UniversalExport;
use App\Http\Controllers\Controller;
use App\Models\Directorate;
use App\Models\Governorate;
use App\Models\PersonalData;
use App\Models\SubArea;
use App\Models\Village;
use App\Models\Wife;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class WifeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Wife::with(['personalData', 'governorate', 'directorate', 'subArea', 'village']);

        if ($request->filled('personal_data_id')) {
            $query->where('personal_data_id', $request->personal_data_id);
        }

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%$search%")
                ->orWhereHas('personalData', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
        }

        $perPage = $request->input('per_page', 50);
        $wives = $query->paginate($perPage)->withQueryString();
        $personalDataList = PersonalData::select('id', 'name', 'jihadi_name', 'military_number')->orderBy('name')->get();

        return view('system.wives.index', compact('wives', 'personalDataList'));
    }

    public function export(Request $request)
    {
        $query = Wife::with(['personalData', 'governorate', 'directorate', 'subArea', 'village']);

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%$search%")
                ->orWhereHas('personalData', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
        }

        $headings = [
            'ID',
            'الزوج',
            'اسم الزوجة',
            'الحالة',
            'رقم الهوية',
            'تاريخ الميلاد',
            'رقم الهاتف',
        ];

        return (new UniversalExport($query, $headings, function ($row) {
            return [
                $row->id,
                $row->personalData->name ?? '-',
                $row->name,
                $row->status,
                $row->wife_id_number,
                $row->wife_birth_date,
                $row->wife_phone,
            ];
        }))->download('wives_'.date('Y-m-d').'.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $personalDataList = PersonalData::pluck('name', 'id');
        $allPeople = PersonalData::select('id', 'name')->get();
        $governorates = Governorate::pluck('name', 'id');
        $directorates = Directorate::pluck('name', 'id');
        $subAreas = SubArea::pluck('name', 'id');
        $villages = Village::pluck('name', 'id');

        $selectedPersonalDataId = $request->query('personal_data_id');

        return view('system.wives.create', compact(
            'personalDataList', 'allPeople', 'governorates', 'directorates', 'subAreas', 'villages', 'selectedPersonalDataId'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->filled('personal_data_id') && !is_numeric($request->personal_data_id)) {
            $person = PersonalData::where('name', $request->personal_data_id)
                ->orWhere('jihadi_name', $request->personal_data_id)
                ->orWhere('military_number', $request->personal_data_id)
                ->first();
            if ($person) {
                $request->merge(['personal_data_id' => $person->id]);
            }
        }

        $request->validate([
            'personal_data_id' => 'required|exists:personal_data,id',
            'wives' => 'required|array|min:1',
            'wives.*.name' => 'required|string|max:255',
            'wives.*.status' => 'required|string|in:مستمرة,متوفية,مطلقة,أخرى',
            'wives.*.wife_id_number' => 'nullable|string|max:255',
            'wives.*.wife_birth_date' => 'nullable|date',
            'wives.*.wife_phone' => 'nullable|string|max:255',
            'wives.*.wife_address' => 'nullable|string|max:255',
            'wives.*.governorate_id' => 'nullable',
            'wives.*.directorate_id' => 'nullable',
            'wives.*.sub_area_id' => 'nullable',
            'wives.*.village_id' => 'nullable',
        ]);

        $wivesData = $request->wives;
        
        // Check "مستمرة" limit
        if (Schema::hasColumn('wives', 'status')) {
            $currentActiveCount = Wife::where('personal_data_id', $request->personal_data_id)
                ->where('status', 'مستمرة')
                ->count();
            
            $newActiveCount = collect($wivesData)->where('status', 'مستمرة')->count();
            
            if (($currentActiveCount + $newActiveCount) > 4) {
                return back()->withErrors(['status' => 'لا يمكن إضافة أكثر من 4 زوجات بحالة (مستمرة) لنفس الشخص.'])->withInput();
            }
        }

        $allFields = [
            'personal_data_id', 'name', 'status', 'wife_id_number',
            'wife_birth_date', 'wife_phone', 'wife_address',
            'governorate_id', 'directorate_id', 'sub_area_id', 'village_id',
        ];

        foreach ($wivesData as $wifeInput) {
            $resolvedWife = $this->resolveWifeFields($wifeInput);
            $data = ['personal_data_id' => $request->personal_data_id];
            foreach ($allFields as $field) {
                if ($field !== 'personal_data_id' && isset($resolvedWife[$field]) && Schema::hasColumn('wives', $field)) {
                    $data[$field] = $resolvedWife[$field];
                }
            }
            Wife::create($data);
        }

        return redirect()->route('system.wives.index', ['personal_data_id' => $request->personal_data_id])
            ->with('success', 'تمت إضافة الزوجات بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Wife $wife)
    {
        return view('system.wives.show', compact('wife'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wife $wife)
    {
        $personalDataList = PersonalData::pluck('name', 'id');
        $allPeople = PersonalData::select('id', 'name')->get();
        $governorates = Governorate::pluck('name', 'id');
        $directorates = Directorate::pluck('name', 'id');
        $subAreas = SubArea::pluck('name', 'id');
        $villages = Village::pluck('name', 'id');

        return view('system.wives.edit', compact(
            'wife', 'allPeople', 'personalDataList', 'governorates', 'directorates', 'subAreas', 'villages'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Wife $wife)
    {
        if ($request->filled('personal_data_id') && !is_numeric($request->personal_data_id)) {
            $person = PersonalData::where('name', $request->personal_data_id)
                ->orWhere('jihadi_name', $request->personal_data_id)
                ->orWhere('military_number', $request->personal_data_id)
                ->first();
            if ($person) {
                $request->merge(['personal_data_id' => $person->id]);
            }
        }

        $request->validate([
            'personal_data_id' => 'required|exists:personal_data,id',
            'name' => 'required|string|max:255',
            'status' => 'required|string|in:مستمرة,متوفية,مطلقة,أخرى',
            'wife_id_number' => 'nullable|string|max:255',
            'wife_birth_date' => 'nullable|date',
            'wife_phone' => 'nullable|string|max:255',
            'wife_address' => 'nullable|string|max:255',
            'governorate_id' => 'nullable',
            'directorate_id' => 'nullable',
            'sub_area_id' => 'nullable',
            'village_id' => 'nullable',
            'new_wives' => 'nullable|array',
            'new_wives.*.name' => 'required_with:new_wives|string|max:255',
            'new_wives.*.status' => 'required_with:new_wives|string|in:مستمرة,متوفية,مطلقة,أخرى',
        ]);

        $resolvedMainWife = $this->resolveWifeFields($request->all());

        if ($resolvedMainWife['status'] == 'مستمرة' && Schema::hasColumn('wives', 'status')) {
            $count = Wife::where('personal_data_id', $request->personal_data_id)
                ->where('status', 'مستمرة')
                ->where('id', '!=', $wife->id)
                ->count();
            
            $newActiveCount = 0;
            if ($request->has('new_wives')) {
                $newActiveCount = collect($request->new_wives)->where('status', 'مستمرة')->count();
            }

            if (($count + 1 + $newActiveCount) > 4) {
                return back()->withErrors(['status' => 'لا يمكن إضافة أكثر من 4 زوجات بحالة (مستمرة) لنفس الشخص.'])->withInput();
            }
        }

        $allFields = [
            'personal_data_id', 'name', 'status', 'wife_id_number',
            'wife_birth_date', 'wife_phone', 'wife_address',
            'governorate_id', 'directorate_id', 'sub_area_id', 'village_id',
        ];

        $data = [];
        foreach ($allFields as $field) {
            if (isset($resolvedMainWife[$field]) && Schema::hasColumn('wives', $field)) {
                $data[$field] = $resolvedMainWife[$field];
            }
        }
        $data['personal_data_id'] = $request->personal_data_id;

        $wife->update($data);

        // Handle New Wives Additions
        if ($request->has('new_wives')) {
            foreach ($request->new_wives as $wifeInput) {
                $resolvedWife = $this->resolveWifeFields($wifeInput);
                $newWifeData = ['personal_data_id' => $request->personal_data_id];
                foreach ($allFields as $field) {
                    if ($field !== 'personal_data_id' && isset($resolvedWife[$field]) && Schema::hasColumn('wives', $field)) {
                        $newWifeData[$field] = $resolvedWife[$field];
                    }
                }
                Wife::create($newWifeData);
            }
        }

        return redirect()->route('system.wives.index', ['personal_data_id' => $request->personal_data_id])
            ->with('success', 'تم تحديث بيانات الزوجة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wife $wife)
    {
        $personalDataId = $wife->personal_data_id;
        $wife->delete();

        return redirect()->route('system.wives.index', ['personal_data_id' => $personalDataId])
            ->with('success', 'تم حذف الزوجة بنجاح');
    }

    /**
     * Get person details for auto-filling (AJAX)
     */
    public function getPersonDetails($id)
    {
        $person = PersonalData::with([
            'bloodType', 'maritalStatus',
            'placeOfBirthGovernorate', 'placeOfBirthDirectorate', 'placeOfBirthSubArea', 'placeOfBirthVillage',
            'governorate', 'directorate', 'subArea', 'village',
        ])->findOrFail($id);

        $placeOfBirth = implode(' - ', array_filter([
            $person->placeOfBirthGovernorate->name ?? null,
            $person->placeOfBirthDirectorate->name ?? null,
            $person->placeOfBirthSubArea->name ?? null,
            $person->placeOfBirthVillage->name ?? null,
        ]));

        $address = implode(' - ', array_filter([
            $person->governorate->name ?? null,
            $person->directorate->name ?? null,
            $person->subArea->name ?? null,
            $person->village->name ?? null,
        ]));

        return response()->json([
            'name' => $person->name,
            'national_id_number' => $person->national_id_number,
            'military_number' => $person->military_number,
            'jihadi_name' => $person->jihadi_name,
            'blood_type' => $person->bloodType->type ?? 'غير محدد',
            'marital_status' => $person->maritalStatus->status ?? 'غير محدد',
            'date_of_birth' => $person->date_of_birth_gregorian ? $person->date_of_birth_gregorian->format('Y-m-d') : 'غير محدد',
            'place_of_birth' => $placeOfBirth ?: 'غير محدد',
            'phone_1' => $person->phone_1 ?? 'غير متوفر',
            'phone_2' => $person->phone_2 ?? 'غير متوفر',
            'photo_url' => $person->photo_url,
            'address' => $address ?: 'غير محدد',
            // Also keep the IDs for cascading dropdowns if needed elsewhere
            'governorate_id' => $person->governorate_id,
            'directorate_id' => $person->directorate_id,
            'sub_area_id' => $person->sub_area_id,
            'village_id' => $person->village_id,
        ]);
    }

    /**
     * Print single wife record.
     */
    public function print(Wife $wife)
    {
        $wife->load(['personalData', 'governorate', 'directorate', 'subArea', 'village']);
        return view('system.wives.print', compact('wife'));
    }

    /**
     * Print all wives listing.
     */
    public function printAll(Request $request)
    {
        $wives = Wife::with(['personalData', 'governorate', 'directorate'])->get();
        return view('system.wives.print', [
            'wives' => $wives,
            'isList' => true,
        ]);
    }

    /**
     * Resolve string tags from Select2 inputs to numeric IDs for database.
     */
    protected function resolveWifeFields(array $wifeInput): array
    {
        $govId = $wifeInput['governorate_id'] ?? null;
        $dirId = $wifeInput['directorate_id'] ?? null;
        $subId = $wifeInput['sub_area_id'] ?? null;
        $vilId = $wifeInput['village_id'] ?? null;

        if ($govId && !is_numeric($govId)) {
            $gov = Governorate::where('name', $govId)->first();
            $wifeInput['governorate_id'] = $gov?->id;
            $govId = $gov?->id;
        }

        if ($dirId && !is_numeric($dirId)) {
            $query = Directorate::where('name', $dirId);
            if ($govId && is_numeric($govId)) $query->where('governorate_id', $govId);
            $dir = $query->first();
            $wifeInput['directorate_id'] = $dir?->id;
            $dirId = $dir?->id;
        }

        if ($subId && !is_numeric($subId)) {
            $query = SubArea::where('name', $subId);
            if ($dirId && is_numeric($dirId)) $query->where('directorate_id', $dirId);
            $sub = $query->first();
            if (!$sub && $dirId && is_numeric($dirId)) {
                $sub = SubArea::create([
                    'name' => $subId,
                    'directorate_id' => $dirId,
                    'governorate_id' => $govId
                ]);
            }
            $wifeInput['sub_area_id'] = $sub?->id;
            $subId = $sub?->id;
        }

        if ($vilId && !is_numeric($vilId)) {
            $query = Village::where('name', $vilId);
            if ($subId && is_numeric($subId)) $query->where('sub_area_id', $subId);
            $vil = $query->first();
            if (!$vil && $subId && is_numeric($subId)) {
                $vil = Village::create([
                    'name' => $vilId,
                    'sub_area_id' => $subId
                ]);
            }
            $wifeInput['village_id'] = $vil?->id;
        }

        return $wifeInput;
    }
}
