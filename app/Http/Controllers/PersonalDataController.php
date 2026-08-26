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
        $entityIdsByEnt = \App\Models\InternalEntity::getAllChildrenIds(auth()->user()->administrative_scope_id);
        $query = PersonalData::query();

        if (!empty($entityIdsByEnt)) {
            $query->whereIn('internal_entity_id', $entityIdsByEnt);
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
        $personalDataList = $query->paginate($perPage);

        return view('system.personal_data.index', compact('personalDataList'));
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
        $countries = Country::pluck('name', 'id');
        $governorates = Governorate::pluck('name', 'id');
        $financialStatuses = FinancialStatus::pluck('name', 'id');

        // Structured geo data for client-side filtering (no AJAX needed)
        $allDirectorates = Directorate::orderBy('name')->get(['id', 'name', 'governorate_id']);
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
            'name' => 'nullable|string|max:255',
            'jihadi_name' => 'nullable|string|max:255',
            'phone_1' => 'nullable|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'phone_3' => 'nullable|string|max:20',
            'national_id_number' => 'nullable|string|max:255',
            'military_number' => 'nullable|string|max:255',
            'date_of_birth_gregorian' => 'nullable|date',
            'date_of_birth_hijri' => 'nullable|string|max:255',
            'blood_type_id' => 'nullable|exists:blood_types,id',
            'marital_status_id' => 'nullable|exists:marital_statuses,id',
            'qualification_id' => 'nullable|exists:qualifications,id',
            'number_classification_id' => 'nullable|exists:number_classifications,id',
            'job_status_id' => 'nullable|exists:job_statuses,id',
            'rank_id' => 'nullable|exists:ranks,id',
            'military_district_id' => 'nullable|exists:military_districts,id',
            'division_id' => 'nullable|exists:divisions,id',
            'place_of_birth_country_id' => 'nullable|exists:countries,id',
            'place_of_birth_governorate_id' => 'nullable|exists:governorates,id',
            'place_of_birth_directorate_id' => 'nullable|exists:directorates,id',
            'place_of_birth_sub_area_id' => 'nullable|exists:sub_areas,id',
            'place_of_birth_village_id' => 'nullable|exists:villages,id',
            'country_id' => 'nullable|exists:countries,id',
            'governorate_id' => 'nullable|exists:governorates,id',
            'directorate_id' => 'nullable|exists:directorates,id',
            'sub_area_id' => 'nullable|exists:sub_areas,id',
            'village_id' => 'nullable|exists:villages,id',
            'unit' => 'nullable|string|max:255',
            'job' => 'nullable|string|max:255',
            'workplace' => 'nullable|string|max:255',
            'job_specialty' => 'nullable|string|max:255',
            'jihadist_number' => 'nullable|string|max:255',
            'financial_status_id' => 'nullable|exists:financial_statuses,id',
            'personal_photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except(['personal_photos', 'main_profile_photo']);

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
        $personalData->load(['relatives.kinship', 'relatives.ideologicalAffiliation', 'relatives.politicalAffiliation', 'wives', 'courses.courseType', 'arrests']);
        return view('system.personal_data.show', compact('personalData'));
    }

    public function print(PersonalData $personalData)
    {
        return view('system.personal_data.print', compact('personalData'));
    }

    public function printFull(PersonalData $personalData)
    {
        $personalData->load([
            'wives',
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
            'arrests.arrestingAuthority',
            'arrests.caseType',
            'secondaryData.healthStatus',
            'secondaryData.weapons',
            'secondaryData.affiliations.ideologicalAffiliation',
            'secondaryData.affiliations.politicalAffiliation',
            'secondaryData.previousJihadistActions',
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
        $countries = Country::pluck('name', 'id');
        $governorates = Governorate::pluck('name', 'id');
        $financialStatuses = FinancialStatus::pluck('name', 'id');

        // Structured geo data for client-side filtering (no AJAX needed)
        $allDirectorates = Directorate::orderBy('name')->get(['id', 'name', 'governorate_id']);
        $allSubAreas = SubArea::orderBy('name')->get(['id', 'name', 'directorate_id']);
        $allVillages = Village::orderBy('name')->get(['id', 'name', 'sub_area_id']);

        return view('system.personal_data.edit', compact(
            'personalData', 'bloodTypes', 'maritalStatuses', 'qualifications', 'numberClassifications',
            'jobStatuses', 'ranks', 'militaryDistricts', 'divisions',
            'governorates', 'financialStatuses', 'allDirectorates', 'allSubAreas', 'allVillages'
        ));
    }

    public function update(Request $request, PersonalData $personalData)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'jihadi_name' => 'nullable|string|max:255',
            'phone_1' => 'nullable|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'phone_3' => 'nullable|string|max:20',
            'national_id_number' => 'nullable|string|max:255',
            'military_number' => 'nullable|string|max:255',
            'date_of_birth_gregorian' => 'nullable|date',
            'date_of_birth_hijri' => 'nullable|string|max:255',
            'blood_type_id' => 'nullable|exists:blood_types,id',
            'marital_status_id' => 'nullable|exists:marital_statuses,id',
            'qualification_id' => 'nullable|exists:qualifications,id',
            'number_classification_id' => 'nullable|exists:number_classifications,id',
            'job_status_id' => 'nullable|exists:job_statuses,id',
            'rank_id' => 'nullable|exists:ranks,id',
            'military_district_id' => 'nullable|exists:military_districts,id',
            'division_id' => 'nullable|exists:divisions,id',
            'place_of_birth_country_id' => 'nullable|exists:countries,id',
            'place_of_birth_governorate_id' => 'nullable|exists:governorates,id',
            'place_of_birth_directorate_id' => 'nullable|exists:directorates,id',
            'place_of_birth_sub_area_id' => 'nullable|exists:sub_areas,id',
            'place_of_birth_village_id' => 'nullable|exists:villages,id',
            'country_id' => 'nullable|exists:countries,id',
            'governorate_id' => 'nullable|exists:governorates,id',
            'directorate_id' => 'nullable|exists:directorates,id',
            'sub_area_id' => 'nullable|exists:sub_areas,id',
            'village_id' => 'nullable|exists:villages,id',
            'unit' => 'nullable|string|max:255',
            'job' => 'nullable|string|max:255',
            'workplace' => 'nullable|string|max:255',
            'job_specialty' => 'nullable|string|max:255',
            'jihadist_number' => 'nullable|string|max:255',
            'financial_status_id' => 'nullable|exists:financial_statuses,id',
            'personal_photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except(['personal_photos', 'main_profile_photo']);

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
            'address' => ($person->governorate->name ?? '').' - '.($person->directorate->name ?? '').' - '.($person->village->name ?? ''),
            // For auto-filling forms
            'governorate_id' => $person->governorate_id,
            'directorate_id' => $person->directorate_id,
            'sub_area_id' => $person->sub_area_id,
            'village_id' => $person->village_id,
        ]);
    }
}
