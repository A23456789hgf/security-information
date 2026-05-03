<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\PersonalData;
use App\Models\Governorate;
use App\Models\Directorate;
use App\Models\SubArea;
use App\Models\Village;
use App\Models\Wife;

class WifeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Wife::with(['personalData', 'governorate', 'directorate', 'subArea', 'village']);

        if ($request->has('personal_data_id')) {
            $query->where('personal_data_id', $request->personal_data_id);
        }

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%$search%")
                  ->orWhereHas('personalData', function($q) use ($search) {
                      $q->where('name', 'like', "%$search%");
                  });
        }

        $perPage = $request->input('per_page', 50);
        $wives = $query->paginate($perPage);
        return view('system.wives.index', compact('wives'));
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
        $request->validate([
            'personal_data_id' => 'required|exists:personal_data,id',
            'name' => 'required|string|max:255',
            'status' => 'required|string|in:مستمرة,متوفية,مطلقة,أخرى',
            'wife_id_number' => 'nullable|string|max:255',
            'wife_birth_date' => 'nullable|date',
            'wife_phone' => 'nullable|string|max:255',
            'wife_address' => 'nullable|string|max:255',
            'governorate_id' => 'nullable|exists:governorates,id',
            'directorate_id' => 'nullable|exists:directorates,id',
            'sub_area_id' => 'nullable|exists:sub_areas,id',
            'village_id' => 'nullable|exists:villages,id',
        ]);

        if ($request->status == 'مستمرة' && Schema::hasColumn('wives', 'status')) {
            $count = Wife::where('personal_data_id', $request->personal_data_id)
                ->where('status', 'مستمرة')
                ->count();
            if ($count >= 4) {
                return back()->withErrors(['status' => 'لا يمكن إضافة أكثر من 4 زوجات بحالة (مستمرة) لنفس الشخص.'])->withInput();
            }
        }

        // Get only the fields that exist in the database to prevent "Column not found" errors
        $allFields = [
            'personal_data_id', 'name', 'status', 'wife_id_number', 
            'wife_birth_date', 'wife_phone', 'wife_address', 
            'governorate_id', 'directorate_id', 'sub_area_id', 'village_id'
        ];
        
        $data = [];
        foreach ($allFields as $field) {
            if ($request->has($field) && Schema::hasColumn('wives', $field)) {
                $data[$field] = $request->get($field);
            }
        }

        Wife::create($data);

        return redirect()->route('system.wives.index', ['personal_data_id' => $request->personal_data_id])
            ->with('success', 'تمت إضافة الزوجة بنجاح');
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
        $request->validate([
            'personal_data_id' => 'required|exists:personal_data,id',
            'name' => 'required|string|max:255',
            'status' => 'required|string|in:مستمرة,متوفية,مطلقة,أخرى',
            'wife_id_number' => 'nullable|string|max:255',
            'wife_birth_date' => 'nullable|date',
            'wife_phone' => 'nullable|string|max:255',
            'wife_address' => 'nullable|string|max:255',
            'governorate_id' => 'nullable|exists:governorates,id',
            'directorate_id' => 'nullable|exists:directorates,id',
            'sub_area_id' => 'nullable|exists:sub_areas,id',
            'village_id' => 'nullable|exists:villages,id',
        ]);

        if ($request->status == 'مستمرة' && Schema::hasColumn('wives', 'status')) {
            $count = Wife::where('personal_data_id', $request->personal_data_id)
                ->where('status', 'مستمرة')
                ->where('id', '!=', $wife->id)
                ->count();
            if ($count >= 4) {
                return back()->withErrors(['status' => 'لا يمكن إضافة أكثر من 4 زوجات بحالة (مستمرة) لنفس الشخص.'])->withInput();
            }
        }

        // Get only the fields that exist in the database
        $allFields = [
            'personal_data_id', 'name', 'status', 'wife_id_number', 
            'wife_birth_date', 'wife_phone', 'wife_address', 
            'governorate_id', 'directorate_id', 'sub_area_id', 'village_id'
        ];
        
        $data = [];
        foreach ($allFields as $field) {
            if ($request->has($field) && Schema::hasColumn('wives', $field)) {
                $data[$field] = $request->get($field);
            }
        }

        $wife->update($data);

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
            'governorate', 'directorate', 'subArea', 'village'
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
            'address' => $address ?: 'غير محدد',
            // Also keep the IDs for cascading dropdowns if needed elsewhere
            'governorate_id' => $person->governorate_id,
            'directorate_id' => $person->directorate_id,
            'sub_area_id' => $person->sub_area_id,
            'village_id' => $person->village_id,
        ]);
    }
}
