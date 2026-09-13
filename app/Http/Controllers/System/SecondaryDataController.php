<?php

namespace App\Http\Controllers\System;

use App\Exports\UniversalExport;
use App\Http\Controllers\Controller;
use App\Models\HealthStatus;
use App\Models\IdeologicalAffiliation;
use App\Models\PersonalData;
use App\Models\PoliticalAffiliation;
use App\Models\PreviousJihadistAction;
use App\Models\SecondaryAffiliation;
use App\Models\SecondaryData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SecondaryDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SecondaryData::with([
            'personalData.bloodType',
            'personalData.maritalStatus',
            'personalData.placeOfBirthDirectorate',
            'personalData.governorate',
            'personalData.directorate',
            'personalData.village',
            'healthStatus',
            'weapons',
            'affiliations.ideologicalAffiliation',
            'affiliations.politicalAffiliation',
            'previousJihadistActions',
        ]);

        if ($search = $request->input('search')) {
            $query->whereHas('personalData', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('jihadi_name', 'like', "%$search%")
                    ->orWhere('military_number', 'like', "%$search%");
            });
        }

        if ($request->filled('health_status_id')) {
            $query->where('health_status_id', $request->health_status_id);
        }

        if ($request->filled('personal_data_id')) {
            $query->where('personal_data_id', $request->personal_data_id);
        }

        $perPage = $request->input('per_page', 50);
        $secondaryDataList = $query->paginate($perPage)->withQueryString();
        $healthStatuses = HealthStatus::orderBy('name')->pluck('name', 'id');
        $personalDataList = PersonalData::select('id', 'name', 'jihadi_name', 'military_number')->orderBy('name')->get();

        return view('system.secondary_data.index', compact('secondaryDataList', 'healthStatuses', 'personalDataList'));
    }

    public function export(Request $request)
    {
        $query = SecondaryData::with([
            'personalData',
            'healthStatus',
            'weapons',
            'affiliations.ideologicalAffiliation',
            'affiliations.politicalAffiliation',
            'previousJihadistActions',
        ]);

        if ($search = $request->input('search')) {
            $query->whereHas('personalData', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('jihadi_name', 'like', "%$search%");
            });
        }

        if ($request->filled('health_status_id')) {
            $query->where('health_status_id', $request->health_status_id);
        }

        $headings = [
            'ID',
            'الاسم الكامل',
            'الاسم الجهادي',
            'الحالة الصحية',
            'عدد الأولاد',
            'عام الالتحاق/الانطلاقة',
            'المشرف المباشر',
            'عدد الأسلحة',
            'عدد الانتماءات السابقة',
            'عدد الأنشطة الجهادية السابقة',
            'النشاط الحالي',
        ];

        return (new UniversalExport($query, $headings, function ($row) {
            return [
                $row->id,
                $row->personalData->name ?? '-',
                $row->personalData->jihadi_name ?? '-',
                $row->healthStatus->name ?? '-',
                $row->total_children_count ?? (($row->male_children_count ?? 0) + ($row->female_children_count ?? 0)),
                $row->start_date ? $row->start_date->format('Y') : '-',
                $row->direct_supervisor ?? '-',
                $row->weapons->count(),
                $row->affiliations->count(),
                $row->previousJihadistActions->count(),
                $row->current_jihadist_activity ?? '-',
            ];
        }))->download('secondary_data_'.date('Y-m-d').'.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $personalDataList = PersonalData::orderBy('name')->pluck('name', 'id');
        $selectedPersonalDataId = $request->query('personal_data_id');
        $healthStatuses = HealthStatus::orderBy('name')->pluck('name', 'id');
        $ideologicalAffiliations = IdeologicalAffiliation::orderBy('name')->pluck('name', 'id');
        $politicalAffiliations = PoliticalAffiliation::orderBy('name')->pluck('name', 'id');

        return view('system.secondary_data.create', compact(
            'personalDataList',
            'selectedPersonalDataId',
            'healthStatuses',
            'ideologicalAffiliations',
            'politicalAffiliations'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->normalizeRequestDates($request);

        $request->validate([
            'personal_data_id' => 'required|exists:personal_data,id',
            'health_status_id' => 'nullable|string|max:255',
            'total_children_count' => 'nullable|integer|min:0|max:100',
            'male_children_count' => 'nullable|integer|min:0|max:100',
            'female_children_count' => 'nullable|integer|min:0|max:100',
            'start_date' => 'nullable|date',
            'previous_jihadist_activity' => 'nullable|string',
            'current_jihadist_activity' => 'nullable|string',
            'direct_supervisor' => 'nullable|string|max:255',

            // Weapons
            'weapons' => 'nullable|array',
            'weapons.*.weapon_type' => 'nullable|string|max:255',
            'weapons.*.number' => 'nullable|string|max:255',
            'weapons.*.ownership' => 'nullable|string|max:255',
            'weapons.*.tracking' => 'nullable|string|max:255',

            // Affiliations (Political & Ideological)
            'affiliations' => 'nullable|array',
            'affiliations.*.party' => 'nullable|string|max:255',
            'affiliations.*.ideological_affiliation_id' => 'nullable|string|max:255',
            'affiliations.*.political_affiliation_id' => 'nullable|string|max:255',
            'affiliations.*.affiliation_date' => 'nullable|date',
            'affiliations.*.duration' => 'nullable|string|max:255',
            'affiliations.*.workplace' => 'nullable|string|max:255',
            'affiliations.*.job_type' => 'nullable|string|max:255',
            'affiliations.*.direct_supervisor' => 'nullable|string|max:255',
            'affiliations.*.leaving_date' => 'nullable|date',
            'affiliations.*.leaving_reason' => 'nullable|string',

            // Previous Jihadist Actions
            'previous_jihadist_actions' => 'nullable|array',
            'previous_jihadist_actions.*.activity' => 'nullable|string|max:500',
            'previous_jihadist_actions.*.activity_date' => 'nullable|date',
            'previous_jihadist_actions.*.duration' => 'nullable|string|max:255',
            'previous_jihadist_actions.*.location' => 'nullable|string|max:255',
            'previous_jihadist_actions.*.direct_supervisor' => 'nullable|string|max:255',
            'previous_jihadist_actions.*.leaving_reason' => 'nullable|string',
        ]);

        $totalCount = $request->filled('total_children_count')
            ? (int) $request->input('total_children_count')
            : ($request->filled('male_children_count') || $request->filled('female_children_count')
                ? ((int) $request->input('male_children_count', 0) + (int) $request->input('female_children_count', 0))
                : null);

        $healthStatusId = $this->resolveHealthStatus($request->health_status_id);

        DB::transaction(function () use ($request, $totalCount, $healthStatusId) {
            $secondaryData = SecondaryData::updateOrCreate(
                ['personal_data_id' => $request->personal_data_id],
                [
                    'health_status_id' => $healthStatusId,
                    'total_children_count' => $totalCount,
                    'male_children_count' => $request->filled('male_children_count') ? (int) $request->input('male_children_count') : null,
                    'female_children_count' => $request->filled('female_children_count') ? (int) $request->input('female_children_count') : null,
                    'start_date' => $request->start_date,
                    'previous_jihadist_activity' => $request->previous_jihadist_activity,
                    'current_jihadist_activity' => $request->current_jihadist_activity,
                    'direct_supervisor' => $request->direct_supervisor,
                ]
            );

            // Sync Weapons
            $secondaryData->weapons()->delete();
            if ($request->has('weapons')) {
                foreach ($request->weapons as $weaponData) {
                    if (! empty($weaponData['weapon_type']) || ! empty($weaponData['number'])) {
                        $secondaryData->weapons()->create($weaponData);
                    }
                }
            }

            // Sync Political & Ideological Affiliations
            $secondaryData->affiliations()->delete();
            if ($request->has('affiliations')) {
                foreach ($request->affiliations as $aff) {
                    if (! empty($aff['party']) || ! empty($aff['ideological_affiliation_id']) || ! empty($aff['political_affiliation_id']) || ! empty($aff['workplace']) || ! empty($aff['job_type']) || ! empty($aff['direct_supervisor'])) {
                        $ideoId = $this->resolveIdeologicalAffiliation($aff['ideological_affiliation_id'] ?? null);
                        $polyId = $this->resolvePoliticalAffiliation($aff['political_affiliation_id'] ?? null);

                        $secondaryData->affiliations()->create([
                            'party' => $aff['party'] ?? null,
                            'ideological_affiliation_id' => $ideoId,
                            'political_affiliation_id' => $polyId,
                            'affiliation_date' => $aff['affiliation_date'] ?: null,
                            'duration' => $aff['duration'] ?? null,
                            'workplace' => $aff['workplace'] ?? null,
                            'job_type' => $aff['job_type'] ?? null,
                            'direct_supervisor' => $aff['direct_supervisor'] ?? null,
                            'leaving_date' => $aff['leaving_date'] ?: null,
                            'leaving_reason' => $aff['leaving_reason'] ?? null,
                        ]);
                    }
                }
            }

            // Sync Previous Jihadist Actions
            $secondaryData->previousJihadistActions()->delete();
            if ($request->has('previous_jihadist_actions')) {
                foreach ($request->previous_jihadist_actions as $act) {
                    if (! empty($act['activity']) || ! empty($act['location']) || ! empty($act['direct_supervisor'])) {
                        $secondaryData->previousJihadistActions()->create([
                            'activity' => $act['activity'] ?? null,
                            'activity_date' => $act['activity_date'] ?: null,
                            'duration' => $act['duration'] ?? null,
                            'location' => $act['location'] ?? null,
                            'direct_supervisor' => $act['direct_supervisor'] ?? null,
                            'leaving_reason' => $act['leaving_reason'] ?? null,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('system.secondary-data.index')
            ->with('success', 'تم حفظ البيانات الثانوية بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(SecondaryData $secondaryData)
    {
        $secondaryData->load([
            'personalData.bloodType',
            'personalData.maritalStatus',
            'personalData.placeOfBirthDirectorate',
            'personalData.governorate',
            'personalData.directorate',
            'personalData.village',
            'healthStatus',
            'weapons',
            'affiliations.ideologicalAffiliation',
            'affiliations.politicalAffiliation',
            'previousJihadistActions',
        ]);

        return view('system.secondary_data.show', compact('secondaryData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SecondaryData $secondaryData)
    {
        $secondaryData->load([
            'personalData',
            'healthStatus',
            'weapons',
            'affiliations.ideologicalAffiliation',
            'affiliations.politicalAffiliation',
            'previousJihadistActions',
        ]);

        $personalDataList = PersonalData::orderBy('name')->pluck('name', 'id');
        $healthStatuses = HealthStatus::orderBy('name')->pluck('name', 'id');
        $ideologicalAffiliations = IdeologicalAffiliation::orderBy('name')->pluck('name', 'id');
        $politicalAffiliations = PoliticalAffiliation::orderBy('name')->pluck('name', 'id');

        return view('system.secondary_data.edit', compact(
            'secondaryData',
            'personalDataList',
            'healthStatuses',
            'ideologicalAffiliations',
            'politicalAffiliations'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SecondaryData $secondaryData)
    {
        $this->normalizeRequestDates($request);

        $request->validate([
            'personal_data_id' => 'required|exists:personal_data,id',
            'health_status_id' => 'nullable|string|max:255',
            'total_children_count' => 'nullable|integer|min:0|max:100',
            'male_children_count' => 'nullable|integer|min:0|max:100',
            'female_children_count' => 'nullable|integer|min:0|max:100',
            'start_date' => 'nullable|date',
            'previous_jihadist_activity' => 'nullable|string',
            'current_jihadist_activity' => 'nullable|string',
            'direct_supervisor' => 'nullable|string|max:255',

            // Weapons
            'weapons' => 'nullable|array',
            'weapons.*.weapon_type' => 'nullable|string|max:255',
            'weapons.*.number' => 'nullable|string|max:255',
            'weapons.*.ownership' => 'nullable|string|max:255',
            'weapons.*.tracking' => 'nullable|string|max:255',

            // Affiliations (Political & Ideological)
            'affiliations' => 'nullable|array',
            'affiliations.*.party' => 'nullable|string|max:255',
            'affiliations.*.ideological_affiliation_id' => 'nullable|string|max:255',
            'affiliations.*.political_affiliation_id' => 'nullable|string|max:255',
            'affiliations.*.affiliation_date' => 'nullable|date',
            'affiliations.*.duration' => 'nullable|string|max:255',
            'affiliations.*.workplace' => 'nullable|string|max:255',
            'affiliations.*.job_type' => 'nullable|string|max:255',
            'affiliations.*.direct_supervisor' => 'nullable|string|max:255',
            'affiliations.*.leaving_date' => 'nullable|date',
            'affiliations.*.leaving_reason' => 'nullable|string',

            // Previous Jihadist Actions
            'previous_jihadist_actions' => 'nullable|array',
            'previous_jihadist_actions.*.activity' => 'nullable|string|max:500',
            'previous_jihadist_actions.*.activity_date' => 'nullable|date',
            'previous_jihadist_actions.*.duration' => 'nullable|string|max:255',
            'previous_jihadist_actions.*.location' => 'nullable|string|max:255',
            'previous_jihadist_actions.*.direct_supervisor' => 'nullable|string|max:255',
            'previous_jihadist_actions.*.leaving_reason' => 'nullable|string',
        ]);

        $totalCount = $request->filled('total_children_count')
            ? (int) $request->input('total_children_count')
            : ($request->filled('male_children_count') || $request->filled('female_children_count')
                ? ((int) $request->input('male_children_count', 0) + (int) $request->input('female_children_count', 0))
                : null);

        $healthStatusId = $this->resolveHealthStatus($request->health_status_id);

        DB::transaction(function () use ($request, $secondaryData, $totalCount, $healthStatusId) {
            $secondaryData->update([
                'personal_data_id' => $request->personal_data_id,
                'health_status_id' => $healthStatusId,
                'total_children_count' => $totalCount,
                'male_children_count' => $request->filled('male_children_count') ? (int) $request->input('male_children_count') : null,
                'female_children_count' => $request->filled('female_children_count') ? (int) $request->input('female_children_count') : null,
                'start_date' => $request->start_date,
                'previous_jihadist_activity' => $request->previous_jihadist_activity,
                'current_jihadist_activity' => $request->current_jihadist_activity,
                'direct_supervisor' => $request->direct_supervisor,
            ]);

            // Sync Weapons
            $secondaryData->weapons()->delete();
            if ($request->has('weapons')) {
                foreach ($request->weapons as $weaponData) {
                    if (! empty($weaponData['weapon_type']) || ! empty($weaponData['number'])) {
                        $secondaryData->weapons()->create($weaponData);
                    }
                }
            }

            // Sync Political & Ideological Affiliations
            $secondaryData->affiliations()->delete();
            if ($request->has('affiliations')) {
                foreach ($request->affiliations as $aff) {
                    if (! empty($aff['party']) || ! empty($aff['ideological_affiliation_id']) || ! empty($aff['political_affiliation_id']) || ! empty($aff['workplace']) || ! empty($aff['job_type']) || ! empty($aff['direct_supervisor'])) {
                        $ideoId = $this->resolveIdeologicalAffiliation($aff['ideological_affiliation_id'] ?? null);
                        $polyId = $this->resolvePoliticalAffiliation($aff['political_affiliation_id'] ?? null);

                        $secondaryData->affiliations()->create([
                            'party' => $aff['party'] ?? null,
                            'ideological_affiliation_id' => $ideoId,
                            'political_affiliation_id' => $polyId,
                            'affiliation_date' => $aff['affiliation_date'] ?: null,
                            'duration' => $aff['duration'] ?? null,
                            'workplace' => $aff['workplace'] ?? null,
                            'job_type' => $aff['job_type'] ?? null,
                            'direct_supervisor' => $aff['direct_supervisor'] ?? null,
                            'leaving_date' => $aff['leaving_date'] ?: null,
                            'leaving_reason' => $aff['leaving_reason'] ?? null,
                        ]);
                    }
                }
            }

            // Sync Previous Jihadist Actions
            $secondaryData->previousJihadistActions()->delete();
            if ($request->has('previous_jihadist_actions')) {
                foreach ($request->previous_jihadist_actions as $act) {
                    if (! empty($act['activity']) || ! empty($act['location']) || ! empty($act['direct_supervisor'])) {
                        $secondaryData->previousJihadistActions()->create([
                            'activity' => $act['activity'] ?? null,
                            'activity_date' => $act['activity_date'] ?: null,
                            'duration' => $act['duration'] ?? null,
                            'location' => $act['location'] ?? null,
                            'direct_supervisor' => $act['direct_supervisor'] ?? null,
                            'leaving_reason' => $act['leaving_reason'] ?? null,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('system.secondary-data.index')
            ->with('success', 'تم تحديث البيانات الثانوية بنجاح');
    }

    protected function resolveHealthStatus($input): ?int
    {
        if (empty($input)) {
            return null;
        }
        $input = trim((string) $input);
        if (is_numeric($input) && HealthStatus::where('id', $input)->exists()) {
            return (int) $input;
        }

        return HealthStatus::firstOrCreate(['name' => $input])->id;
    }

    protected function resolveIdeologicalAffiliation($input): ?int
    {
        if (empty($input)) {
            return null;
        }
        $input = trim((string) $input);
        if (is_numeric($input) && IdeologicalAffiliation::where('id', $input)->exists()) {
            return (int) $input;
        }

        return IdeologicalAffiliation::firstOrCreate(['name' => $input])->id;
    }

    protected function resolvePoliticalAffiliation($input): ?int
    {
        if (empty($input)) {
            return null;
        }
        $input = trim((string) $input);
        if (is_numeric($input) && PoliticalAffiliation::where('id', $input)->exists()) {
            return (int) $input;
        }

        return PoliticalAffiliation::firstOrCreate(['name' => $input])->id;
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

    /**
     * Print single secondary data record.
     */
    public function print(SecondaryData $secondaryData)
    {
        $secondaryData->load([
            'personalData.bloodType',
            'personalData.maritalStatus',
            'personalData.placeOfBirthDirectorate',
            'personalData.governorate',
            'personalData.directorate',
            'personalData.village',
            'healthStatus',
            'weapons',
            'affiliations.ideologicalAffiliation',
            'affiliations.politicalAffiliation',
            'previousJihadistActions',
        ]);

        return view('system.secondary_data.print', compact('secondaryData'));
    }

    /**
     * Print all secondary data records list.
     */
    public function printAll(Request $request)
    {
        $query = SecondaryData::with([
            'personalData',
            'healthStatus',
            'weapons',
            'affiliations',
        ]);

        if ($search = $request->input('search')) {
            $query->whereHas('personalData', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('jihadi_name', 'like', "%$search%")
                    ->orWhere('military_number', 'like', "%$search%");
            });
        }

        if ($request->filled('health_status_id')) {
            $query->where('health_status_id', $request->health_status_id);
        }

        $secondaryDataList = $query->get();

        return view('system.secondary_data.print', [
            'secondaryDataList' => $secondaryDataList,
            'isList' => true,
        ]);
    }

    protected function normalizeYearInput($value): ?string
    {
        if (empty($value)) {
            return null;
        }
        $value = trim((string) $value);
        if (preg_match('/^\d{4}$/', $value)) {
            return $value . '-01-01';
        }
        return $value;
    }

    protected function normalizeRequestDates(Request $request): void
    {
        if ($request->has('start_date')) {
            $request->merge(['start_date' => $this->normalizeYearInput($request->start_date)]);
        }
        if ($request->has('affiliations')) {
            $affiliations = $request->affiliations;
            foreach ($affiliations as $k => $aff) {
                if (isset($aff['affiliation_date'])) {
                    $affiliations[$k]['affiliation_date'] = $this->normalizeYearInput($aff['affiliation_date']);
                }
                if (isset($aff['leaving_date'])) {
                    $affiliations[$k]['leaving_date'] = $this->normalizeYearInput($aff['leaving_date']);
                }
            }
            $request->merge(['affiliations' => $affiliations]);
        }
        if ($request->has('previous_jihadist_actions')) {
            $actions = $request->previous_jihadist_actions;
            foreach ($actions as $k => $act) {
                if (isset($act['activity_date'])) {
                    $actions[$k]['activity_date'] = $this->normalizeYearInput($act['activity_date']);
                }
            }
            $request->merge(['previous_jihadist_actions' => $actions]);
        }
    }
}
