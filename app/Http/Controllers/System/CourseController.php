<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\FillingAgency;
use App\Models\PersonalData;
use App\Models\Governorate;
use App\Models\Directorate;
use App\Models\SubArea;
use App\Models\Village;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with('personalData', 'courseType', 'sendingEntity');

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->whereHas('personalData', function($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%$search%");
                })
                ->orWhereHas('courseType', function($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%$search%");
                })
                ->orWhere('course_administrator', 'like', "%$search%");
            });
        }

        if ($sort = $request->input('sort')) {
            $direction = $request->input('direction', 'asc');
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $perPage = $request->input('per_page', 50);
        $courses = $query->paginate($perPage);

        return view('system.courses.index', compact('courses'));
    }

    public function create(Request $request)
    {
        $personalDataList = PersonalData::orderBy('name')->pluck('name', 'id');
        $allPeople = PersonalData::select('id', 'name')->get();
        $courseTypes = CourseType::orderBy('name')->pluck('name', 'id');
        $fillingAgencies = FillingAgency::orderBy('name')->pluck('name', 'id');
        $governorates = Governorate::pluck('name', 'id');
        $directorates = Directorate::pluck('name', 'id');
        $subAreas = SubArea::pluck('name', 'id');
        $villages = Village::pluck('name', 'id');
        
        $selectedPersonalDataId = $request->query('personal_data_id');

        return view('system.courses.create', compact(
            'personalDataList',
            'allPeople',
            'courseTypes',
            'fillingAgencies',
            'governorates',
            'directorates',
            'subAreas',
            'villages',
            'selectedPersonalDataId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'personal_data_id' => 'required|exists:personal_data,id',
            'course_type_id' => 'required|exists:course_types,id',
            'course_date_gregorian' => 'required|date',
            'course_date_hijri' => 'nullable|string|max:255',
            'course_duration' => 'nullable|string|max:255',
            'sending_entity_id' => 'nullable|exists:filling_agencies,id',
            'course_administrator' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Course::create($request->all());

        return redirect()->route('system.courses.index')->with('success', 'تمت إضافة الدورة بنجاح');
    }

    public function edit(Course $course)
    {
        $personalDataList = PersonalData::orderBy('name')->pluck('name', 'id');
        $allPeople = PersonalData::select('id', 'name')->get();
        $courseTypes = CourseType::orderBy('name')->pluck('name', 'id');
        $fillingAgencies = FillingAgency::orderBy('name')->pluck('name', 'id');
        $governorates = Governorate::pluck('name', 'id');
        $directorates = Directorate::pluck('name', 'id');
        $subAreas = SubArea::pluck('name', 'id');
        $villages = Village::pluck('name', 'id');

        return view('system.courses.edit', compact(
            'course',
            'personalDataList',
            'allPeople',
            'courseTypes',
            'fillingAgencies',
            'governorates',
            'directorates',
            'subAreas',
            'villages'
        ));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'personal_data_id' => 'required|exists:personal_data,id',
            'course_type_id' => 'required|exists:course_types,id',
            'course_date_gregorian' => 'required|date',
            'course_date_hijri' => 'nullable|string|max:255',
            'course_duration' => 'nullable|string|max:255',
            'sending_entity_id' => 'nullable|exists:filling_agencies,id',
            'course_administrator' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $course->update($request->all());

        return redirect()->route('system.courses.index')->with('success', 'تم تحديث الدورة بنجاح');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('system.courses.index')->with('success', 'تم حذف الدورة بنجاح');
    }

    /**
     * Get person details for auto-filling (AJAX)
     */
    public function getPersonDetails($id)
    {
        $person = PersonalData::findOrFail($id);

        return response()->json([
            'name' => $person->name,
            'national_id_number' => $person->national_id_number,
            'date_of_birth' => $person->date_of_birth_gregorian ? $person->date_of_birth_gregorian->format('Y-m-d') : null,
            'governorate_id' => $person->governorate_id,
            'directorate_id' => $person->directorate_id,
            'sub_area_id' => $person->sub_area_id,
            'village_id' => $person->village_id,
        ]);
    }
}
