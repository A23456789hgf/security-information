<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\BloodType;
use App\Models\MaritalStatus;
use App\Models\PersonalData;
use App\Models\Qualification;
use App\Models\SecondaryData;
use Illuminate\Http\Request;

class SecondaryDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SecondaryData::with(['personalData', 'weapons']);

        if ($search = $request->input('search')) {
            $query->whereHas('personalData', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('jihadi_name', 'like', "%$search%");
            });
        }

        $perPage = $request->input('per_page', 50);
        $secondaryDataList = $query->paginate($perPage);

        return view('system.secondary_data.index', compact('secondaryDataList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $personalDataList = PersonalData::pluck('name', 'id');
        $selectedPersonalDataId = $request->query('personal_data_id');
        $bloodTypes = BloodType::pluck('type', 'id');
        $maritalStatuses = MaritalStatus::pluck('status', 'id');
        $qualifications = Qualification::pluck('name', 'id');

        return view('system.secondary_data.create', compact(
            'personalDataList',
            'selectedPersonalDataId',
            'bloodTypes',
            'maritalStatuses',
            'qualifications'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'personal_data_id' => 'required|exists:personal_data,id',
            'start_date' => 'nullable|date',
            'previous_jihadist_activity' => 'nullable|string',
            'current_jihadist_activity' => 'nullable|string',
            'direct_supervisor' => 'nullable|string|max:255',
            'weapons' => 'nullable|array',
            'weapons.*.weapon_type' => 'nullable|string|max:255',
            'weapons.*.number' => 'nullable|string|max:255',
            'weapons.*.ownership' => 'nullable|string|max:255',
            'weapons.*.tracking' => 'nullable|string|max:255',
        ]);

        $secondaryData = SecondaryData::updateOrCreate(
            ['personal_data_id' => $request->personal_data_id],
            $request->only(['start_date', 'previous_jihadist_activity', 'current_jihadist_activity', 'direct_supervisor'])
        );

        // Clear existing weapons if updating
        $secondaryData->weapons()->delete();

        if ($request->has('weapons')) {
            foreach ($request->weapons as $weaponData) {
                if (!empty($weaponData['weapon_type']) || !empty($weaponData['number'])) {
                    $secondaryData->weapons()->create($weaponData);
                }
            }
        }

        return redirect()->route('system.secondary-data.index')
            ->with('success', 'تم حفظ البيانات الثانوية بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(SecondaryData $secondaryData)
    {
        $secondaryData->load(['personalData', 'weapons']);

        return view('system.secondary_data.show', compact('secondaryData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SecondaryData $secondaryData)
    {
        $secondaryData->load(['personalData', 'weapons']);
        $personalDataList = PersonalData::pluck('name', 'id');

        return view('system.secondary_data.edit', compact('secondaryData', 'personalDataList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SecondaryData $secondaryData)
    {
        $request->validate([
            'personal_data_id' => 'required|exists:personal_data,id',
            'start_date' => 'nullable|date',
            'previous_jihadist_activity' => 'nullable|string',
            'current_jihadist_activity' => 'nullable|string',
            'direct_supervisor' => 'nullable|string|max:255',
            'weapons' => 'nullable|array',
            'weapons.*.weapon_type' => 'nullable|string|max:255',
            'weapons.*.number' => 'nullable|string|max:255',
            'weapons.*.ownership' => 'nullable|string|max:255',
            'weapons.*.tracking' => 'nullable|string|max:255',
        ]);

        $secondaryData->update($request->only([
            'personal_data_id',
            'start_date',
            'previous_jihadist_activity',
            'current_jihadist_activity',
            'direct_supervisor',
        ]));

        // Clear existing weapons and re-add
        $secondaryData->weapons()->delete();

        if ($request->has('weapons')) {
            foreach ($request->weapons as $weaponData) {
                if (!empty($weaponData['weapon_type']) || !empty($weaponData['number'])) {
                    $secondaryData->weapons()->create($weaponData);
                }
            }
        }

        return redirect()->route('system.secondary-data.index')
            ->with('success', 'تم تحديث البيانات الثانوية بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SecondaryData $secondaryData)
    {
        $secondaryData->delete();

        return redirect()->route('system.secondary-data.index')
            ->with('success', 'تم حذف البيانات الثانوية بنجاح');
    }
}
