<?php

namespace App\Http\Controllers;

use App\Exports\UniversalExport;
use App\Models\BloodType;
use App\Models\Country;
use App\Models\Directorate;
use App\Models\Division;
use App\Models\FinancialStatus;
use App\Models\Governorate;
use App\Models\JobStatus;
use App\Models\MaritalStatus;
use App\Models\MilitaryDistrict;
use App\Models\NumberClassification;
use App\Models\PersonalData;
use App\Models\Qualification;
use App\Models\Rank;
use App\Models\SubArea;
use App\Models\Village;
use Illuminate\Http\Request;

class PersonalDataController extends Controller
{
    public function index(Request $request)
    {
        $query = PersonalData::query();
        \App\Services\UserScopeService::applyScope($query);

        if ($request->filled('personal_data_id')) {
            $query->where('id', $request->personal_data_id);
        }

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('jihadi_name', 'like', "%$search%")
                ->orWhere('national_id_number', 'like', "%$search%")
                ->orWhere('military_number', 'like', "%$search%")
                ->orWhere('phone_1', 'like', "%$search%")
                ->orWhere('phone_2', 'like', "%$search%")
                ->orWhere('phone_3', 'like', "%$search%");
        }

        $perPage = $request->input('per_page', 50);
        $personalDataList = $query->paginate($perPage)->withQueryString();
        $allPeople = PersonalData::select('id', 'name', 'jihadi_name', 'military_number')->orderBy('name')->get();

        return view('system.personal_data.index', compact('personalDataList', 'allPeople'));
    }

    public function export(Request $request)
    {
        $entityIdsByEnt = \App\Models\InternalEntity::getAllChildrenIds(auth()->user()->administrative_scope_id);
        $query = PersonalData::query()->with(['rank', 'militaryDistrict']);

        if (!empty($entityIdsByEnt)) {
            $query->whereIn('internal_entity_id', $entityIdsByEnt);
        }

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('military_number', 'like', "%{$search}%")
                    ->orWhere('national_id_number', 'like', "%{$search}%")
                    ->orWhere('jihadi_name', 'like', "%{$search}%");
            });
        }

        $headings = [
            'ID',
            'الاسم الكامل',
            'الاسم الجهادي',
            'الرقم الوطني',
            'الرقم العسكري',
            'الرتبة',
            'المنطقة العسكرية',
            'الوحدة',
            'الوظيفة',
            'رقم الهاتف',
        ];

        return (new UniversalExport($query, $headings, function ($row) {
            return [
                $row->id,
                $row->name,
                $row->jihadi_name,
                $row->national_id_number,
                $row->military_number,
                $row->rank->rank ?? '-',
                $row->militaryDistrict->name ?? '-',
                $row->unit,
                $row->job,
                $row->phone_1,
            ];
        }))->download('personal_data_'.date('Y-m-d').'.xlsx');
    }

    public function create()
    {
        $bloodTypes = BloodType::pluck('type', 'id');
        $maritalStatuses = MaritalStatus::pluck('status', 'id');
        $qualifications = Qualification::pluck('name', 'id');
        $numberClassifications = NumberClassification::pluck('classification', 'id');
        $jobStatuses = JobStatus::pluck('job_status', 'id');
        $ranks = Rank::pluck('rank', 'id');
        $militaryDistricts = MilitaryDistrict::pluck('name', 'id');
        $divisions = Division::pluck('name', 'id');
        $governorates = \App\Services\UserScopeService::getScopedGovernorates()->pluck('name', 'id');
        $financialStatuses = FinancialStatus::pluck('name', 'id');
        // Structured geo data for client-side filtering (scoped to user geographical permissions)
        $allDirectorates = \App\Services\UserScopeService::getScopedDirectorates();
        $allSubAreas = SubArea::orderBy('name')->get(['id', 'name', 'directorate_id']);
        $allVillages = Village::orderBy('name')->get(['id', 'name', 'sub_area_id']);

        return view('system.personal_data.create', compact(
            'bloodTypes', 'maritalStatuses', 'qualifications', 'numberClassifications',
            'jobStatuses', 'ranks', 'militaryDistricts', 'divisions',
            'governorates', 'financialStatuses', 'allDirectorates', 'allSubAreas', 'allVillages'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:personal_data,name',
            'jihadi_name' => 'nullable|string|max:255',
            'phone_1' => 'nullable|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'phone_3' => 'nullable|string|max:20',
            'national_id_number' => 'nullable|string|max:255|unique:personal_data,national_id_number',
            'military_number' => 'nullable|string|max:255|unique:personal_data,military_number',
            'date_of_birth_gregorian' => 'nullable|date',
            'date_of_birth_hijri' => 'nullable|string|max:255',
            'blood_type_id' => 'nullable|exists:blood_types,id',
            'marital_status_id' => 'nullable|exists:marital_statuses,id',
            'qualification_id' => 'nullable|exists:qualifications,id',
            'number_classification_id' => 'nullable|exists:number_classifications,id',
            'job_status_id' => 'nullable|exists:job_statuses,id',
            'rank_id' => 'nullable|exists:ranks,id',
            'military_district_id' => 'nullable|string|max:255',
            'division_id' => 'nullable|string|max:255',
            'place_of_birth_country_id' => 'nullable|exists:countries,id',
            'place_of_birth_governorate_id' => 'nullable|exists:governorates,id',
            'place_of_birth_directorate_id' => 'nullable|string|max:255',
            'place_of_birth_sub_area_id' => 'nullable|string|max:255',
            'place_of_birth_village_id' => 'nullable|string|max:255',
            'country_id' => 'nullable|exists:countries,id',
            'governorate_id' => 'nullable|exists:governorates,id',
            'directorate_id' => 'nullable|string|max:255',
            'sub_area_id' => 'nullable|string|max:255',
            'village_id' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:255',
            'job' => 'nullable|string|max:255',
            'workplace' => 'nullable|string|max:255',
            'job_specialty' => 'nullable|string|max:255',
            'jihadist_number' => 'nullable|string|max:255|unique:personal_data,jihadist_number',
            'financial_status_id' => 'nullable|exists:financial_statuses,id',
            'personal_photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'الاسم الكامل مطلوب ولا يمكن تركه فارغاً.',
            'name.unique' => 'يوجد قيد سابق بنفس هذا الاسم الكامل في السجلات (يمنع التكرار).',
            'national_id_number.unique' => 'رقم الهوية الوطنية هذا مسجل مسبقاً بفرد آخر (يمنع التكرار).',
            'military_number.unique' => 'الرقم العسكري هذا مسجل مسبقاً بفرد آخر (يمنع التكرار).',
            'jihadist_number.unique' => 'الرقم الجهادي هذا مسجل مسبقاً بفرد آخر (يمنع التكرار).',
        ]);

        $data = $request->except(['personal_photos', 'main_profile_photo']);

        // Resolve Place of Birth Directorate
        $pobDirId = $this->resolveDirectorate(
            $data['place_of_birth_governorate_id'] ?? null,
            $data['place_of_birth_directorate_id'] ?? null
        );
        $data['place_of_birth_directorate_id'] = $pobDirId;

        // Resolve Residence Directorate
        $dirId = $this->resolveDirectorate(
            $data['governorate_id'] ?? null,
            $data['directorate_id'] ?? null
        );
        $data['directorate_id'] = $dirId;

        // Resolve Place of Birth SubArea & Village (supports both dropdown selection and manual text entry)
        [$pobSubAreaId, $pobVillageId] = $this->resolveSubAreaAndVillage(
            $data['place_of_birth_governorate_id'] ?? null,
            $pobDirId,
            $data['place_of_birth_sub_area_id'] ?? null,
            $data['place_of_birth_village_id'] ?? null
        );
        $data['place_of_birth_sub_area_id'] = $pobSubAreaId;
        $data['place_of_birth_village_id'] = $pobVillageId;

        // Resolve Residence SubArea & Village (supports both dropdown selection and manual text entry)
        [$subAreaId, $villageId] = $this->resolveSubAreaAndVillage(
            $data['governorate_id'] ?? null,
            $dirId,
            $data['sub_area_id'] ?? null,
            $data['village_id'] ?? null
        );
        $data['sub_area_id'] = $subAreaId;
        $data['village_id'] = $villageId;

        // Resolve Military District & Division (supports both dropdown selection and manual text entry)
        [$distId, $divId] = $this->resolveMilitaryDistrictAndDivision(
            $data['military_district_id'] ?? null,
            $data['division_id'] ?? null
        );
        $data['military_district_id'] = $distId;
        $data['division_id'] = $divId;

        // Automatically assign internal_entity_id from user if not present
        if (!isset($data['internal_entity_id']) && auth()->user()->internal_entity_id) {
            $data['internal_entity_id'] = auth()->user()->internal_entity_id;
        }

        // Handle Hijri date components
        if ($request->date_of_birth_hijri_day && $request->date_of_birth_hijri_month && $request->date_of_birth_hijri_year) {
            $data['date_of_birth_hijri'] = $request->date_of_birth_hijri_day.'/'.$request->date_of_birth_hijri_month.'/'.$request->date_of_birth_hijri_year;
        }

        // Handle main profile photo
        if ($request->hasFile('main_profile_photo')) {
            $data['main_profile_photo'] = $request->file('main_profile_photo')->store('profile_photos', 'public');
        }

        $personalData = PersonalData::create($data);

        if ($request->hasFile('personal_photos')) {
            foreach ($request->file('personal_photos') as $photo) {
                $path = $photo->store('personal_photos', 'public');
                $personalData->photos()->create(['photo_path' => $path]);
            }
        }

        return redirect()->route('system.personal-data.index')->with('success', 'تمت الإضافة بنجاح');
    }

    public function show(PersonalData $personalData)
    {
        $personalData->load([
            'relatives.kinship', 'relatives.ideologicalAffiliation', 'relatives.politicalAffiliation',
            'wives', 'courses.courseType', 'arrests',
            'governorate', 'directorate', 'subArea', 'village',
            'placeOfBirthGovernorate', 'placeOfBirthDirectorate', 'placeOfBirthSubArea', 'placeOfBirthVillage',
        ]);
        return view('system.personal_data.show', compact('personalData'));
    }

    public function printList(Request $request)
    {
        $entityIdsByEnt = \App\Models\InternalEntity::getAllChildrenIds(auth()->user()->administrative_scope_id);
        $query = PersonalData::query()->with(['rank', 'militaryDistrict', 'governorate', 'directorate', 'bloodType']);

        if (!empty($entityIdsByEnt)) {
            $query->whereIn('internal_entity_id', $entityIdsByEnt);
        }

        if ($request->filled('personal_data_id')) {
            $query->where('id', $request->personal_data_id);
        }

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('jihadi_name', 'like', "%$search%")
                  ->orWhere('national_id_number', 'like', "%$search%")
                  ->orWhere('military_number', 'like', "%$search%")
                  ->orWhere('phone_1', 'like', "%$search%");
            });
        }

        $personalDataList = $query->limit(500)->get();

        return view('system.personal_data.print_all', compact('personalDataList'));
    }

    public function print(PersonalData $personalData)
    {
        $personalData->load([
            'rank', 'bloodType', 'maritalStatus', 'qualification', 'numberClassification',
            'jobStatus', 'militaryDistrict', 'division',
            'governorate', 'directorate', 'subArea', 'village',
            'placeOfBirthCountry', 'placeOfBirthGovernorate', 'placeOfBirthDirectorate', 'placeOfBirthSubArea', 'placeOfBirthVillage',
            'secondaryData.healthStatus', 'secondaryData.weapons',
            'secondaryData.affiliations.ideologicalAffiliation', 'secondaryData.affiliations.politicalAffiliation',
            'secondaryData.previousJihadistActions'
        ]);

        return view('system.personal_data.print', compact('personalData'));
    }

    public function printFull(PersonalData $personalData)
    {
        $personalData->load([
            'entity',
            'financialStatus',
            'wives.governorate',
            'wives.directorate',
            'wives.subArea',
            'wives.village',
            'relatives.kinship',
            'relatives.ideologicalAffiliation',
            'relatives.politicalAffiliation',
            'enemyAreaRelatives.kinship',
            'enemyAreaRelatives.politicalAffiliation',
            'enemyAreaRelatives.ideologicalAffiliation',
            'enemyAreaRelatives.financialStatus',
            'travelingRelatives.kinship',
            'travelingRelatives.country',
            'travelingRelatives.politicalAffiliation',
            'travelingRelatives.ideologicalAffiliation',
            'courses.courseType',
            'courses.sendingEntity',
            'courses.governorate',
            'courses.directorate',
            'rank',
            'bloodType',
            'maritalStatus',
            'qualification',
            'numberClassification',
            'jobStatus',
            'militaryDistrict',
            'division',
            'governorate',
            'directorate',
            'subArea',
            'village',
            'placeOfBirthCountry',
            'placeOfBirthGovernorate',
            'placeOfBirthDirectorate',
            'placeOfBirthSubArea',
            'placeOfBirthVillage',
            'arrests.arrestingAuthority',
            'arrests.caseType',
            'secondaryData.healthStatus',
            'secondaryData.weapons',
            'secondaryData.affiliations.ideologicalAffiliation',
            'secondaryData.affiliations.politicalAffiliation',
            'secondaryData.previousJihadistActions',
            'securityFiles.preparingEntity',
            'suspects',
            'photos',
        ]);

        $securityReports = $personalData->securityReports();
        $dynamicRecords = $personalData->dynamicRecords();
        $relatedUser = $personalData->relatedUser();

        if ($relatedUser) {
            $relatedUser->load(['roles', 'internal_entity']);
        }

        return view('system.personal_data.print_comprehensive', compact(
            'personalData',
            'securityReports',
            'dynamicRecords',
            'relatedUser'
        ));
    }

    public function edit(PersonalData $personalData)
    {
        $bloodTypes = BloodType::pluck('type', 'id');
        $maritalStatuses = MaritalStatus::pluck('status', 'id');
        $qualifications = Qualification::pluck('name', 'id');
        $numberClassifications = NumberClassification::pluck('classification', 'id');
        $jobStatuses = JobStatus::pluck('job_status', 'id');
        $ranks = Rank::pluck('rank', 'id');
        $militaryDistricts = MilitaryDistrict::pluck('name', 'id');
        $divisions = Division::pluck('name', 'id');
        $governorates = \App\Services\UserScopeService::getScopedGovernorates()->pluck('name', 'id');
        $financialStatuses = FinancialStatus::pluck('name', 'id');

        // Structured geo data for client-side filtering (scoped to user geographical permissions)
        $allDirectorates = \App\Services\UserScopeService::getScopedDirectorates();
        $allSubAreas = SubArea::orderBy('name')->get(['id', 'name', 'directorate_id']);
        $allVillages = Village::orderBy('name')->get(['id', 'name', 'sub_area_id']);
        $governorates = \App\Services\UserScopeService::getScopedGovernorates()->pluck('name', 'id');

        return view('system.personal_data.edit', compact(
            'personalData', 'bloodTypes', 'maritalStatuses', 'qualifications', 'numberClassifications',
            'jobStatuses', 'ranks', 'militaryDistricts', 'divisions',
            'governorates', 'financialStatuses', 'allDirectorates', 'allSubAreas', 'allVillages'
        ));
    }

    public function update(Request $request, PersonalData $personalData)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:personal_data,name,'.$personalData->id,
            'jihadi_name' => 'nullable|string|max:255',
            'phone_1' => 'nullable|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'phone_3' => 'nullable|string|max:20',
            'national_id_number' => 'nullable|string|max:255|unique:personal_data,national_id_number,'.$personalData->id,
            'military_number' => 'nullable|string|max:255|unique:personal_data,military_number,'.$personalData->id,
            'date_of_birth_gregorian' => 'nullable|date',
            'date_of_birth_hijri' => 'nullable|string|max:255',
            'blood_type_id' => 'nullable|exists:blood_types,id',
            'marital_status_id' => 'nullable|exists:marital_statuses,id',
            'qualification_id' => 'nullable|exists:qualifications,id',
            'number_classification_id' => 'nullable|exists:number_classifications,id',
            'job_status_id' => 'nullable|exists:job_statuses,id',
            'rank_id' => 'nullable|exists:ranks,id',
            'military_district_id' => 'nullable|string|max:255',
            'division_id' => 'nullable|string|max:255',
            'place_of_birth_country_id' => 'nullable|exists:countries,id',
            'place_of_birth_governorate_id' => 'nullable|exists:governorates,id',
            'place_of_birth_directorate_id' => 'nullable|string|max:255',
            'place_of_birth_sub_area_id' => 'nullable|string|max:255',
            'place_of_birth_village_id' => 'nullable|string|max:255',
            'country_id' => 'nullable|exists:countries,id',
            'governorate_id' => 'nullable|exists:governorates,id',
            'directorate_id' => 'nullable|string|max:255',
            'sub_area_id' => 'nullable|string|max:255',
            'village_id' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:255',
            'job' => 'nullable|string|max:255',
            'workplace' => 'nullable|string|max:255',
            'job_specialty' => 'nullable|string|max:255',
            'jihadist_number' => 'nullable|string|max:255|unique:personal_data,jihadist_number,'.$personalData->id,
            'financial_status_id' => 'nullable|exists:financial_statuses,id',
            'personal_photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'الاسم الكامل مطلوب ولا يمكن تركه فارغاً.',
            'name.unique' => 'يوجد قيد سابق بنفس هذا الاسم الكامل في السجلات (يمنع التكرار).',
            'national_id_number.unique' => 'رقم الهوية الوطنية هذا مسجل مسبقاً بفرد آخر (يمنع التكرار).',
            'military_number.unique' => 'الرقم العسكري هذا مسجل مسبقاً بفرد آخر (يمنع التكرار).',
            'jihadist_number.unique' => 'الرقم الجهادي هذا مسجل مسبقاً بفرد آخر (يمنع التكرار).',
        ]);

        $data = $request->except(['personal_photos', 'main_profile_photo']);

        // Resolve Place of Birth Directorate
        $pobDirId = $this->resolveDirectorate(
            $data['place_of_birth_governorate_id'] ?? null,
            $data['place_of_birth_directorate_id'] ?? null
        );
        $data['place_of_birth_directorate_id'] = $pobDirId;

        // Resolve Residence Directorate
        $dirId = $this->resolveDirectorate(
            $data['governorate_id'] ?? null,
            $data['directorate_id'] ?? null
        );
        $data['directorate_id'] = $dirId;

        // Resolve Place of Birth SubArea & Village (supports both dropdown selection and manual text entry)
        [$pobSubAreaId, $pobVillageId] = $this->resolveSubAreaAndVillage(
            $data['place_of_birth_governorate_id'] ?? null,
            $pobDirId,
            $data['place_of_birth_sub_area_id'] ?? null,
            $data['place_of_birth_village_id'] ?? null
        );
        $data['place_of_birth_sub_area_id'] = $pobSubAreaId;
        $data['place_of_birth_village_id'] = $pobVillageId;

        // Resolve Residence SubArea & Village (supports both dropdown selection and manual text entry)
        [$subAreaId, $villageId] = $this->resolveSubAreaAndVillage(
            $data['governorate_id'] ?? null,
            $dirId,
            $data['sub_area_id'] ?? null,
            $data['village_id'] ?? null
        );
        $data['sub_area_id'] = $subAreaId;
        $data['village_id'] = $villageId;

        // Resolve Military District & Division (supports both dropdown selection and manual text entry)
        [$distId, $divId] = $this->resolveMilitaryDistrictAndDivision(
            $data['military_district_id'] ?? null,
            $data['division_id'] ?? null
        );
        $data['military_district_id'] = $distId;
        $data['division_id'] = $divId;

        // Automatically assign internal_entity_id if missing (for safety, though usually kept from creation)
        if (!isset($data['internal_entity_id']) && auth()->user()->internal_entity_id) {
            $data['internal_entity_id'] = auth()->user()->internal_entity_id;
        }

        // Handle Hijri date components (if they come from a create-style form, though edit uses a single field)
        if ($request->date_of_birth_hijri_day && $request->date_of_birth_hijri_month && $request->date_of_birth_hijri_year) {
            $data['date_of_birth_hijri'] = $request->date_of_birth_hijri_day.'/'.$request->date_of_birth_hijri_month.'/'.$request->date_of_birth_hijri_year;
        }

        // Handle main profile photo
        if ($request->hasFile('main_profile_photo')) {
            $data['main_profile_photo'] = $request->file('main_profile_photo')->store('profile_photos', 'public');
        }

        $personalData->update($data);

        if ($request->hasFile('personal_photos')) {
            foreach ($request->file('personal_photos') as $photo) {
                $path = $photo->store('personal_photos', 'public');
                $personalData->photos()->create(['photo_path' => $path]);
            }
        }

        return redirect()->route('system.personal-data.index')->with('success', 'تم التحديث بنجاح');
    }

    /**
     * Resolve Directorate ID from either integer ID or manually typed name.
     */
    protected function resolveDirectorate($govId, $dirInput): ?int
    {
        if (empty($dirInput)) {
            return null;
        }

        $dirInput = trim((string) $dirInput);
        if (is_numeric($dirInput) && Directorate::where('id', $dirInput)->exists()) {
            return (int) $dirInput;
        }

        $govId = !empty($govId) ? (int) $govId : null;
        if ($govId) {
            $dir = Directorate::firstOrCreate(
                ['governorate_id' => $govId, 'name' => $dirInput]
            );
            return $dir->id;
        }

        $dir = Directorate::where('name', $dirInput)->first();
        if ($dir) {
            return $dir->id;
        }

        $dir = Directorate::create(['name' => $dirInput]);
        return $dir->id;
    }

    /**
     * Resolve Military District and Division IDs from either integer IDs or manually typed names.
     */
    protected function resolveMilitaryDistrictAndDivision($distInput, $divInput): array
    {
        $distId = null;
        $divId = null;

        if (!empty($distInput)) {
            $distInput = trim((string) $distInput);
            if (is_numeric($distInput) && MilitaryDistrict::where('id', $distInput)->exists()) {
                $distId = (int) $distInput;
            } else {
                $dist = MilitaryDistrict::firstOrCreate(['name' => $distInput]);
                $distId = $dist->id;
            }
        }

        if (!empty($divInput)) {
            $divInput = trim((string) $divInput);
            if (is_numeric($divInput) && Division::where('id', $divInput)->exists()) {
                $divId = (int) $divInput;
            } else {
                if ($distId) {
                    $div = Division::firstOrCreate(
                        ['military_district_id' => $distId, 'name' => $divInput]
                    );
                    $divId = $div->id;
                } else {
                    $div = Division::firstOrCreate(['name' => $divInput]);
                    $divId = $div->id;
                }
            }
        }

        return [$distId, $divId];
    }

    /**
     * Resolve SubArea and Village IDs from either integer IDs or manually typed names.
     */
    protected function resolveSubAreaAndVillage($govId, $dirId, $subAreaInput, $villageInput): array
    {
        $subAreaId = null;
        $villageId = null;

        $govId = !empty($govId) ? (int) $govId : null;
        $dirId = !empty($dirId) ? (int) $dirId : null;
        $subAreaInput = $subAreaInput !== null ? trim((string) $subAreaInput) : '';
        $villageInput = $villageInput !== null ? trim((string) $villageInput) : '';

        if ($dirId && !$govId) {
            $dir = Directorate::find($dirId);
            if ($dir) {
                $govId = $dir->governorate_id;
            }
        }

        // 1. Resolve Sub Area
        if ($subAreaInput !== '') {
            if (is_numeric($subAreaInput) && SubArea::where('id', $subAreaInput)->exists()) {
                $subAreaId = (int) $subAreaInput;
                $sub = SubArea::find($subAreaId);
                if ($sub) {
                    if (!$dirId) $dirId = $sub->directorate_id;
                    if (!$govId) $govId = $sub->governorate_id;
                }
            } else {
                // Manually entered text
                if ($dirId && $govId) {
                    $sub = SubArea::firstOrCreate(
                        ['directorate_id' => $dirId, 'name' => $subAreaInput],
                        ['governorate_id' => $govId]
                    );
                    $subAreaId = $sub->id;
                } elseif ($dirId) {
                    $dir = Directorate::find($dirId);
                    $govId = $dir?->governorate_id;
                    if ($govId) {
                        $sub = SubArea::firstOrCreate(
                            ['directorate_id' => $dirId, 'name' => $subAreaInput],
                            ['governorate_id' => $govId]
                        );
                        $subAreaId = $sub->id;
                    }
                } else {
                    $sub = SubArea::where('name', $subAreaInput)->first();
                    if ($sub) {
                        $subAreaId = $sub->id;
                        $dirId = $sub->directorate_id;
                        $govId = $sub->governorate_id;
                    }
                }
            }
        }

        // 2. Resolve Village
        if ($villageInput !== '') {
            if (is_numeric($villageInput) && Village::where('id', $villageInput)->exists()) {
                $villageId = (int) $villageInput;
            } else {
                // Manually entered text
                if ($subAreaId && $dirId && $govId) {
                    $vil = Village::firstOrCreate(
                        ['sub_area_id' => $subAreaId, 'name' => $villageInput],
                        ['governorate_id' => $govId, 'directorate_id' => $dirId]
                    );
                    $villageId = $vil->id;
                } elseif ($subAreaId) {
                    $sub = SubArea::find($subAreaId);
                    $vDirId = $dirId ?: $sub?->directorate_id;
                    $vGovId = $govId ?: $sub?->governorate_id;
                    if ($vDirId && $vGovId) {
                        $vil = Village::firstOrCreate(
                            ['sub_area_id' => $subAreaId, 'name' => $villageInput],
                            ['governorate_id' => $vGovId, 'directorate_id' => $vDirId]
                        );
                        $villageId = $vil->id;
                    }
                } else {
                    $vil = Village::where('name', $villageInput)->first();
                    if ($vil) {
                        $villageId = $vil->id;
                    }
                }
            }
        }

        return [$subAreaId, $villageId];
    }

    public function destroy(PersonalData $personalData)
    {
        // Photos will be deleted automatically due to cascade on delete in migration
        if ($personalData->photos->count() > 0) {
            foreach ($personalData->photos as $photo) {
                // Delete file from storage
                \Illuminate\Support\Facades\Storage::disk('public')->delete($photo->photo_path);
            }
        }
        $personalData->delete();

        return redirect()->route('system.personal-data.index')->with('success', 'تم الحذف بنجاح');
    }

    public function deletePhoto($id)
    {
        $photo = \App\Models\PersonalDataPhoto::findOrFail($id);
        \Illuminate\Support\Facades\Storage::disk('public')->delete($photo->photo_path);
        $photo->delete();

        return back()->with('success', 'تم حذف الصورة بنجاح');
    }

    public function fastEntry()
    {
        $bloodTypes = BloodType::all();
        $maritalStatuses = MaritalStatus::all();
        $qualifications = Qualification::all();
        $numberClassifications = NumberClassification::all();
        $jobStatuses = JobStatus::all();
        $ranks = Rank::all();
        $militaryDistricts = MilitaryDistrict::all();
        $divisions = Division::all();
        $countries = Country::all();
        $governorates = Governorate::all();
        $financialStatuses = FinancialStatus::all();

        return view('system.personal_data.fast_entry', compact(
            'bloodTypes', 'maritalStatuses', 'qualifications', 'numberClassifications',
            'jobStatuses', 'ranks', 'militaryDistricts', 'divisions', 'countries', 'governorates', 'financialStatuses'
        ));
    }

    public function bulkStore(Request $request)
    {
        $rows = $request->input('rows', []);
        $results = ['success' => 0, 'failed' => 0, 'errors' => []];

        \DB::transaction(function () use ($rows, &$results) {
            foreach ($rows as $index => $rowData) {
                try {
                    if (empty($rowData['name']) || empty($rowData['national_id_number'])) {
                        throw new \Exception('الاسم ورقم الهوية مطلوبان');
                    }

                    // Automatically assign internal_entity_id if not present
                    if (!isset($rowData['internal_entity_id']) && auth()->user()->internal_entity_id) {
                        $rowData['internal_entity_id'] = auth()->user()->internal_entity_id;
                    }

                    PersonalData::create($rowData);
                    $results['success']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = 'الصف '.($index + 1).': '.$e->getMessage();
                }
            }
        });

        return response()->json([
            'success' => true,
            'results' => $results,
            'message' => 'تمت معالجة '.count($rows)." سجلات (بنجاح: {$results['success']}, فشل: {$results['failed']})",
        ]);
    }

    /**
     * Get comprehensive person details for AJAX info box
     */
    public function getDetails($id)
    {
        $person = PersonalData::with(['bloodType', 'maritalStatus', 'placeOfBirthDirectorate', 'governorate', 'directorate', 'village'])->findOrFail($id);

        return response()->json([
            'name' => $person->name,
            'jihadi_name' => $person->jihadi_name ?? '-',
            'military_number' => $person->military_number ?? '-',
            'blood_type' => $person->bloodType->type ?? '-',
            'date_of_birth' => $person->date_of_birth_gregorian ? $person->date_of_birth_gregorian->format('Y-m-d') : '-',
            'marital_status' => $person->maritalStatus->status ?? '-',
            'place_of_birth' => $person->placeOfBirthDirectorate->name ?? '-',
            'national_id_number' => $person->national_id_number ?? '-',
            'phone_1' => $person->phone_1 ?? '-',
            'phone_2' => $person->phone_2 ?? '-',
            'photo_url' => $person->photo_url,
            'address' => ($person->governorate->name ?? '').' - '.($person->directorate->name ?? '').' - '.($person->village->name ?? ''),
            // For auto-filling forms
            'governorate_id' => $person->governorate_id,
            'directorate_id' => $person->directorate_id,
            'sub_area_id' => $person->sub_area_id,
            'village_id' => $person->village_id,
        ]);
    }
}
