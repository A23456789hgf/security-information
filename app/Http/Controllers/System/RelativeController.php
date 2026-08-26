<?php

namespace App\Http\Controllers\System;

use App\Exports\UniversalExport;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\EnemyAreaRelative;
use App\Models\FinancialStatus;
use App\Models\IdeologicalAffiliation;
use App\Models\Kinship;
use App\Models\PersonalData;
use App\Models\PoliticalAffiliation;
use App\Models\Relative;
use App\Models\TravelingRelative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelativeController extends Controller
{
    /**
     * Display a listing of the relatives.
     */
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'relatives');
        $search = $request->input('search');
        $perPage = $request->input('per_page', 25);

        // Counts for tab badges
        $relativesCount = Relative::count();
        $enemyCount = EnemyAreaRelative::count();
        $travelingCount = TravelingRelative::count();

        $kinships = Kinship::orderBy('name')->pluck('name', 'id');
        $ideologicalAffiliations = IdeologicalAffiliation::orderBy('name')->pluck('name', 'id');
        $politicalAffiliations = PoliticalAffiliation::orderBy('name')->pluck('name', 'id');
        $countries = Country::orderBy('name')->pluck('name', 'id');
        $financialStatuses = FinancialStatus::orderBy('name')->pluck('name', 'id');

        if ($tab === 'enemy') {
            $query = EnemyAreaRelative::with(['personalData', 'kinship', 'ideologicalAffiliation', 'politicalAffiliation', 'financialStatus']);

            if ($request->filled('personal_data_id')) {
                $query->where('personal_data_id', $request->personal_data_id);
            }
            if ($request->filled('kinship_id')) {
                $query->where('kinship_id', $request->kinship_id);
            }
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('residence_place', 'like', "%{$search}%")
                        ->orWhere('job', 'like', "%{$search}%")
                        ->orWhereHas('personalData', function ($sub) use ($search) {
                            $sub->where('name', 'like', "%{$search}%")
                                ->orWhere('jihadi_name', 'like', "%{$search}%")
                                ->orWhere('military_number', 'like', "%{$search}%");
                        });
                });
            }
            $enemyRelatives = $query->latest()->paginate($perPage)->withQueryString();
            $relatives = collect();
            $travelingRelatives = collect();
        } elseif ($tab === 'traveling') {
            $query = TravelingRelative::with(['personalData', 'kinship', 'country', 'ideologicalAffiliation', 'politicalAffiliation']);

            if ($request->filled('personal_data_id')) {
                $query->where('personal_data_id', $request->personal_data_id);
            }
            if ($request->filled('kinship_id')) {
                $query->where('kinship_id', $request->kinship_id);
            }
            if ($request->filled('country_id')) {
                $query->where('country_id', $request->country_id);
            }
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('travel_reason', 'like', "%{$search}%")
                        ->orWhere('current_job', 'like', "%{$search}%")
                        ->orWhere('workplace', 'like', "%{$search}%")
                        ->orWhere('affiliated_entity', 'like', "%{$search}%")
                        ->orWhereHas('personalData', function ($sub) use ($search) {
                            $sub->where('name', 'like', "%{$search}%")
                                ->orWhere('jihadi_name', 'like', "%{$search}%")
                                ->orWhere('military_number', 'like', "%{$search}%");
                        });
                });
            }
            $travelingRelatives = $query->latest()->paginate($perPage)->withQueryString();
            $relatives = collect();
            $enemyRelatives = collect();
        } else {
            $query = Relative::with(['personalData', 'kinship', 'ideologicalAffiliation', 'politicalAffiliation']);

            if ($request->filled('personal_data_id')) {
                $query->where('personal_data_id', $request->personal_data_id);
            }
            if ($request->filled('kinship_id')) {
                $query->where('kinship_id', $request->kinship_id);
            }
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('current_job', 'like', "%{$search}%")
                        ->orWhereHas('personalData', function ($sub) use ($search) {
                            $sub->where('name', 'like', "%{$search}%")
                                ->orWhere('jihadi_name', 'like', "%{$search}%")
                                ->orWhere('military_number', 'like', "%{$search}%");
                        });
                });
            }
            $relatives = $query->latest()->paginate($perPage)->withQueryString();
            $enemyRelatives = collect();
            $travelingRelatives = collect();
        }

        return view('system.relatives.index', compact(
            'tab',
            'relatives',
            'enemyRelatives',
            'travelingRelatives',
            'relativesCount',
            'enemyCount',
            'travelingCount',
            'kinships',
            'ideologicalAffiliations',
            'politicalAffiliations',
            'countries',
            'financialStatuses'
        ));
    }

    /**
     * Show the form for creating a new resource (Master-Details).
     */
    public function create(Request $request)
    {
        $personalDataList = PersonalData::orderBy('name')->pluck('name', 'id');
        $allPeople = PersonalData::select('id', 'name')->orderBy('name')->get();
        $kinships = Kinship::orderBy('name')->pluck('name', 'id');
        $ideologicalAffiliations = IdeologicalAffiliation::orderBy('name')->pluck('name', 'id');
        $politicalAffiliations = PoliticalAffiliation::orderBy('name')->pluck('name', 'id');
        $countries = Country::orderBy('name')->pluck('name', 'id');
        $financialStatuses = FinancialStatus::orderBy('name')->pluck('name', 'id');

        $selectedPersonalDataId = $request->query('personal_data_id');
        $existingRelatives = collect();
        $existingEnemyRelatives = collect();
        $existingTravelingRelatives = collect();

        if ($selectedPersonalDataId) {
            $existingRelatives = Relative::with(['kinship', 'ideologicalAffiliation', 'politicalAffiliation'])
                ->where('personal_data_id', $selectedPersonalDataId)
                ->get();

            $existingEnemyRelatives = EnemyAreaRelative::with(['kinship', 'ideologicalAffiliation', 'politicalAffiliation', 'financialStatus'])
                ->where('personal_data_id', $selectedPersonalDataId)
                ->get();

            $existingTravelingRelatives = TravelingRelative::with(['kinship', 'country', 'ideologicalAffiliation', 'politicalAffiliation'])
                ->where('personal_data_id', $selectedPersonalDataId)
                ->get();
        }

        return view('system.relatives.create', compact(
            'personalDataList',
            'allPeople',
            'kinships',
            'ideologicalAffiliations',
            'politicalAffiliations',
            'countries',
            'financialStatuses',
            'selectedPersonalDataId',
            'existingRelatives',
            'existingEnemyRelatives',
            'existingTravelingRelatives'
        ));
    }

    /**
     * Store newly created resources in storage (Supports all master-detail tables).
     */
    public function store(Request $request)
    {
        $request->validate([
            'personal_data_id' => 'required|exists:personal_data,id',
            'relatives' => 'nullable|array',
            'enemy_area_relatives' => 'nullable|array',
            'traveling_relatives' => 'nullable|array',
        ], [
            'personal_data_id.required' => 'يرجى اختيار الفرد الأساسي من البيانات الشخصية.',
            'personal_data_id.exists' => 'الفرد المختار غير موجود في قاعدة البيانات.',
        ]);

        $personalDataId = $request->input('personal_data_id');
        $relativesData = $request->input('relatives', []);
        $enemyRelativesData = $request->input('enemy_area_relatives', []);
        $travelingRelativesData = $request->input('traveling_relatives', []);

        // Single flat form fallback for basic relatives
        if (empty($relativesData) && empty($enemyRelativesData) && empty($travelingRelativesData) && $request->filled('full_name')) {
            $relativesData = [[
                'full_name' => $request->input('full_name'),
                'age' => $request->input('age'),
                'kinship_id' => $request->input('kinship_id'),
                'current_job' => $request->input('current_job'),
                'ideological_affiliation_id' => $request->input('ideological_affiliation_id'),
                'political_affiliation_id' => $request->input('political_affiliation_id'),
                'notes' => $request->input('notes'),
            ]];
        }

        // Filter valid rows
        $validRelatives = array_filter($relativesData, fn ($row) => ! empty(trim($row['full_name'] ?? '')));
        $validEnemyRelatives = array_filter($enemyRelativesData, fn ($row) => ! empty(trim($row['full_name'] ?? '')));
        $validTravelingRelatives = array_filter($travelingRelativesData, fn ($row) => ! empty(trim($row['full_name'] ?? '')));

        if (empty($validRelatives) && empty($validEnemyRelatives) && empty($validTravelingRelatives)) {
            return back()
                ->withErrors(['relatives' => 'يرجى إدخال قريب واحد على الأقل في أحد الجداول.'])
                ->withInput();
        }

        $totalAdded = 0;

        DB::transaction(function () use ($personalDataId, $validRelatives, $validEnemyRelatives, $validTravelingRelatives, &$totalAdded) {
            // 1. Standard Relatives
            foreach ($validRelatives as $row) {
                Relative::create([
                    'personal_data_id' => $personalDataId,
                    'full_name' => trim($row['full_name']),
                    'age' => ! empty($row['age']) ? (int) $row['age'] : null,
                    'kinship_id' => ! empty($row['kinship_id']) ? $row['kinship_id'] : null,
                    'current_job' => ! empty($row['current_job']) ? trim($row['current_job']) : null,
                    'ideological_affiliation_id' => ! empty($row['ideological_affiliation_id']) ? $row['ideological_affiliation_id'] : null,
                    'political_affiliation_id' => ! empty($row['political_affiliation_id']) ? $row['political_affiliation_id'] : null,
                    'notes' => ! empty($row['notes']) ? trim($row['notes']) : null,
                ]);
                $totalAdded++;
            }

            // 2. Enemy Area Relatives
            foreach ($validEnemyRelatives as $row) {
                EnemyAreaRelative::create([
                    'personal_data_id' => $personalDataId,
                    'full_name' => trim($row['full_name']),
                    'kinship_id' => ! empty($row['kinship_id']) ? $row['kinship_id'] : null,
                    'residence_place' => ! empty($row['residence_place']) ? trim($row['residence_place']) : null,
                    'job' => ! empty($row['job']) ? trim($row['job']) : null,
                    'political_affiliation_id' => ! empty($row['political_affiliation_id']) ? $row['political_affiliation_id'] : null,
                    'ideological_affiliation_id' => ! empty($row['ideological_affiliation_id']) ? $row['ideological_affiliation_id'] : null,
                    'communication_method' => ! empty($row['communication_method']) ? trim($row['communication_method']) : null,
                    'relationship' => ! empty($row['relationship']) ? trim($row['relationship']) : null,
                    'financial_status_id' => ! empty($row['financial_status_id']) ? $row['financial_status_id'] : null,
                ]);
                $totalAdded++;
            }

            // 3. Traveling Relatives
            foreach ($validTravelingRelatives as $row) {
                TravelingRelative::create([
                    'personal_data_id' => $personalDataId,
                    'full_name' => trim($row['full_name']),
                    'age' => ! empty($row['age']) ? (int) $row['age'] : null,
                    'kinship_id' => ! empty($row['kinship_id']) ? $row['kinship_id'] : null,
                    'travel_reason' => ! empty($row['travel_reason']) ? trim($row['travel_reason']) : null,
                    'current_job' => ! empty($row['current_job']) ? trim($row['current_job']) : null,
                    'workplace' => ! empty($row['workplace']) ? trim($row['workplace']) : null,
                    'affiliated_entity' => ! empty($row['affiliated_entity']) ? trim($row['affiliated_entity']) : null,
                    'country_id' => ! empty($row['country_id']) ? $row['country_id'] : null,
                    'political_affiliation_id' => ! empty($row['political_affiliation_id']) ? $row['political_affiliation_id'] : null,
                    'ideological_affiliation_id' => ! empty($row['ideological_affiliation_id']) ? $row['ideological_affiliation_id'] : null,
                    'march_impression' => ! empty($row['march_impression']) ? trim($row['march_impression']) : null,
                ]);
                $totalAdded++;
            }
        });

        return redirect()->route('system.relatives.index')
            ->with('success', "تمت إضافة {$totalAdded} من سجلات الأقارب بنجاح.");
    }

    /**
     * Display the specified relative.
     */
    public function show(Relative $relative)
    {
        $relative->load([
            'personalData.relatives.kinship',
            'personalData.enemyAreaRelatives.kinship',
            'personalData.enemyAreaRelatives.politicalAffiliation',
            'personalData.enemyAreaRelatives.ideologicalAffiliation',
            'personalData.enemyAreaRelatives.financialStatus',
            'personalData.travelingRelatives.kinship',
            'personalData.travelingRelatives.country',
            'personalData.travelingRelatives.politicalAffiliation',
            'personalData.travelingRelatives.ideologicalAffiliation',
            'kinship',
            'ideologicalAffiliation',
            'politicalAffiliation',
        ]);

        return view('system.relatives.show', compact('relative'));
    }

    /**
     * Show the form for editing the specified relative.
     */
    public function edit(Relative $relative)
    {
        $relative->load('personalData');
        $personalDataList = PersonalData::orderBy('name')->pluck('name', 'id');
        $allPeople = PersonalData::select('id', 'name')->orderBy('name')->get();
        $kinships = Kinship::orderBy('name')->pluck('name', 'id');
        $ideologicalAffiliations = IdeologicalAffiliation::orderBy('name')->pluck('name', 'id');
        $politicalAffiliations = PoliticalAffiliation::orderBy('name')->pluck('name', 'id');
        $countries = Country::orderBy('name')->pluck('name', 'id');
        $financialStatuses = FinancialStatus::orderBy('name')->pluck('name', 'id');

        $selectedPersonalDataId = $relative->personal_data_id;
        $existingRelatives = Relative::with(['kinship', 'ideologicalAffiliation', 'politicalAffiliation'])
            ->where('personal_data_id', $selectedPersonalDataId)
            ->get();

        $existingEnemyRelatives = EnemyAreaRelative::with(['kinship', 'ideologicalAffiliation', 'politicalAffiliation', 'financialStatus'])
            ->where('personal_data_id', $selectedPersonalDataId)
            ->get();

        $existingTravelingRelatives = TravelingRelative::with(['kinship', 'country', 'ideologicalAffiliation', 'politicalAffiliation'])
            ->where('personal_data_id', $selectedPersonalDataId)
            ->get();

        return view('system.relatives.edit', compact(
            'relative',
            'personalDataList',
            'allPeople',
            'kinships',
            'ideologicalAffiliations',
            'politicalAffiliations',
            'countries',
            'financialStatuses',
            'selectedPersonalDataId',
            'existingRelatives',
            'existingEnemyRelatives',
            'existingTravelingRelatives'
        ));
    }

    /**
     * Update the specified relative or related tables.
     */
    public function update(Request $request, Relative $relative)
    {
        $request->validate([
            'personal_data_id' => 'required|exists:personal_data,id',
            'full_name' => 'required|string|max:255',
            'age' => 'nullable|integer|min:0|max:150',
            'kinship_id' => 'nullable|exists:kinships,id',
            'current_job' => 'nullable|string|max:255',
            'ideological_affiliation_id' => 'nullable|exists:ideological_affiliations,id',
            'political_affiliation_id' => 'nullable|exists:political_affiliations,id',
            'notes' => 'nullable|string',
        ], [
            'full_name.required' => 'الاسم الكامل مطلوب.',
            'personal_data_id.required' => 'يرجى اختيار الفرد الأساسي.',
        ]);

        $relative->update([
            'personal_data_id' => $request->personal_data_id,
            'full_name' => trim($request->full_name),
            'age' => $request->filled('age') ? (int) $request->age : null,
            'kinship_id' => $request->kinship_id ?: null,
            'current_job' => $request->current_job ? trim($request->current_job) : null,
            'ideological_affiliation_id' => $request->ideological_affiliation_id ?: null,
            'political_affiliation_id' => $request->political_affiliation_id ?: null,
            'notes' => $request->notes ? trim($request->notes) : null,
        ]);

        return redirect()->route('system.relatives.index')
            ->with('success', 'تم تحديث بيانات القريب بنجاح.');
    }

    /**
     * Remove standard relative.
     */
    public function destroy(Relative $relative)
    {
        $relative->delete();

        return back()->with('success', 'تم حذف سجل القريب بنجاح.');
    }

    /**
     * Remove enemy area relative.
     */
    public function destroyEnemyRelative($id)
    {
        $record = EnemyAreaRelative::findOrFail($id);
        $record->delete();

        return back()->with('success', 'تم حذف سجل القريب في مناطق العدو بنجاح.');
    }

    /**
     * Remove traveling relative.
     */
    public function destroyTravelingRelative($id)
    {
        $record = TravelingRelative::findOrFail($id);
        $record->delete();

        return back()->with('success', 'تم حذف سجل القريب المسافر خارج البلد بنجاح.');
    }

    /**
     * Export relatives to Excel.
     */
    public function export(Request $request)
    {
        $tab = $request->input('tab', 'relatives');
        $search = $request->input('search');

        if ($tab === 'enemy') {
            $query = EnemyAreaRelative::with(['personalData', 'kinship', 'ideologicalAffiliation', 'politicalAffiliation', 'financialStatus']);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('residence_place', 'like', "%{$search}%")
                        ->orWhere('job', 'like', "%{$search}%")
                        ->orWhereHas('personalData', function ($sub) use ($search) {
                            $sub->where('name', 'like', "%{$search}%");
                        });
                });
            }

            $headings = [
                'ID',
                'الفرد الأساسي',
                'الاسم',
                'صلة القرابة',
                'مكان السكن',
                'العمل',
                'الانتماء السياسي',
                'الانتماء العقائدي',
                'طريقة التواصل',
                'العلاقة',
                'الحالة المادية',
            ];

            return (new UniversalExport($query, $headings, function ($row) {
                return [
                    $row->id,
                    $row->personalData->name ?? '-',
                    $row->full_name,
                    $row->kinship->name ?? '-',
                    $row->residence_place ?? '-',
                    $row->job ?? '-',
                    $row->politicalAffiliation->name ?? '-',
                    $row->ideologicalAffiliation->name ?? '-',
                    $row->communication_method ?? '-',
                    $row->relationship ?? '-',
                    $row->financialStatus->name ?? '-',
                ];
            }))->download('enemy_area_relatives_'.date('Y-m-d').'.xlsx');
        } elseif ($tab === 'traveling') {
            $query = TravelingRelative::with(['personalData', 'kinship', 'country', 'ideologicalAffiliation', 'politicalAffiliation']);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('travel_reason', 'like', "%{$search}%")
                        ->orWhere('current_job', 'like', "%{$search}%")
                        ->orWhereHas('personalData', function ($sub) use ($search) {
                            $sub->where('name', 'like', "%{$search}%");
                        });
                });
            }

            $headings = [
                'ID',
                'الفرد الأساسي',
                'الاسم',
                'العمر',
                'صلة القرابة',
                'سبب السفر',
                'العمل الحالي',
                'مكان العمل',
                'الجهة التابع لها',
                'الدولة',
                'الانتماء السياسي',
                'الانتماء العقائدي',
                'انطباعه عن المسيرة',
            ];

            return (new UniversalExport($query, $headings, function ($row) {
                return [
                    $row->id,
                    $row->personalData->name ?? '-',
                    $row->full_name,
                    $row->age ?? '-',
                    $row->kinship->name ?? '-',
                    $row->travel_reason ?? '-',
                    $row->current_job ?? '-',
                    $row->workplace ?? '-',
                    $row->affiliated_entity ?? '-',
                    $row->country->name ?? '-',
                    $row->politicalAffiliation->name ?? '-',
                    $row->ideologicalAffiliation->name ?? '-',
                    $row->march_impression ?? '-',
                ];
            }))->download('traveling_relatives_'.date('Y-m-d').'.xlsx');
        }

        $query = Relative::with(['personalData', 'kinship', 'ideologicalAffiliation', 'politicalAffiliation']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('current_job', 'like', "%{$search}%")
                    ->orWhereHas('personalData', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $headings = [
            'ID',
            'اسم الفرد الأساسي',
            'اسم القريب الكامل',
            'العمر',
            'صلة القرابة',
            'العمل الحالي',
            'الانتماء العقائدي',
            'الانتماء السياسي',
            'ملاحظات',
        ];

        return (new UniversalExport($query, $headings, function ($row) {
            return [
                $row->id,
                $row->personalData->name ?? '-',
                $row->full_name,
                $row->age ?? '-',
                $row->kinship->name ?? '-',
                $row->current_job ?? '-',
                $row->ideologicalAffiliation->name ?? '-',
                $row->politicalAffiliation->name ?? '-',
                $row->notes ?? '-',
            ];
        }))->download('relatives_'.date('Y-m-d').'.xlsx');
    }

    /**
     * Get all relatives (standard, enemy, traveling) for a specific person via AJAX.
     */
    public function getPersonRelatives($personalDataId)
    {
        $relatives = Relative::with(['kinship', 'ideologicalAffiliation', 'politicalAffiliation'])
            ->where('personal_data_id', $personalDataId)
            ->get();

        $enemyRelatives = EnemyAreaRelative::with(['kinship', 'ideologicalAffiliation', 'politicalAffiliation', 'financialStatus'])
            ->where('personal_data_id', $personalDataId)
            ->get();

        $travelingRelatives = TravelingRelative::with(['kinship', 'country', 'ideologicalAffiliation', 'politicalAffiliation'])
            ->where('personal_data_id', $personalDataId)
            ->get();

        return response()->json([
            'relatives' => $relatives,
            'enemy_relatives' => $enemyRelatives,
            'traveling_relatives' => $travelingRelatives,
        ]);
    }
}
