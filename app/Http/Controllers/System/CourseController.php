<?php

namespace App\Http\Controllers\System;

use App\Exports\UniversalExport;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\Directorate;
use App\Models\FillingAgency;
use App\Models\Governorate;
use App\Models\PersonalData;
use App\Models\SubArea;
use App\Models\Village;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with('personalData', 'courseType', 'sendingEntity');

        if ($request->filled('personal_data_id')) {
            $query->where('personal_data_id', $request->personal_data_id);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('personalData', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%$search%")
                        ->orWhere('jihadi_name', 'like', "%$search%")
                        ->orWhere('military_number', 'like', "%$search%");
                })
                    ->orWhereHas('courseType', function ($subQuery) use ($search) {
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
        $courses = $query->paginate($perPage)->withQueryString();
        $personalDataList = PersonalData::select('id', 'name', 'jihadi_name', 'military_number')->orderBy('name')->get();

        return view('system.courses.index', compact('courses', 'personalDataList'));
    }

    public function export(Request $request)
    {
        $query = Course::with(['personalData', 'courseType', 'sendingEntity']);

        if ($search = $request->input('search')) {
            $query->whereHas('personalData', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        $headings = [
            'ID',
            'اسم المتدرب',
            'نوع الدورة',
            'تاريخ الدورة',
            'المدة',
            'جهة الإرسال',
            'مشرف الدورة',
        ];

        return (new UniversalExport($query, $headings, function ($row) {
            return [
                $row->id,
                $row->personalData->name ?? '-',
                $row->courseType->name ?? '-',
                $row->course_date_gregorian,
                $row->course_duration,
                $row->sendingEntity->name ?? '-',
                $row->course_administrator,
            ];
        }))->download('courses_'.date('Y-m-d').'.xlsx');
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
            'courses' => 'required|array|min:1',
            'courses.*.course_type_id' => 'required|exists:course_types,id',
            'courses.*.course_date_gregorian' => 'required|date',
            'courses.*.course_date_hijri' => 'nullable|string|max:255',
            'courses.*.course_duration' => 'nullable|string|max:255',
            'courses.*.sending_entity_id' => 'nullable|exists:filling_agencies,id',
            'courses.*.course_administrator' => 'nullable|string|max:255',
            'courses.*.notes' => 'nullable|string',
        ]);

        foreach ($request->courses as $courseData) {
            $courseData['personal_data_id'] = $request->personal_data_id;
            Course::create($courseData);
        }

        return redirect()->route('system.courses.index')->with('success', 'تمت إضافة الدورات بنجاح');
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
            
            // Validation for multiple new courses
            'new_courses' => 'nullable|array',
            'new_courses.*.course_type_id' => 'required_with:new_courses|exists:course_types,id',
            'new_courses.*.course_date_gregorian' => 'required_with:new_courses|date',
            'new_courses.*.course_date_hijri' => 'nullable|string|max:255',
            'new_courses.*.course_duration' => 'nullable|string|max:255',
            'new_courses.*.sending_entity_id' => 'nullable|exists:filling_agencies,id',
            'new_courses.*.course_administrator' => 'nullable|string|max:255',
            'new_courses.*.notes' => 'nullable|string',
        ]);

        $course->update($request->except('new_courses'));

        if ($request->has('new_courses') && is_array($request->new_courses)) {
            foreach ($request->new_courses as $newCourseData) {
                if (!empty($newCourseData['course_type_id'])) {
                    $newCourseData['personal_data_id'] = $request->personal_data_id;
                    Course::create($newCourseData);
                }
            }
        }

        return redirect()->route('system.courses.index')->with('success', 'تم تحديث الدورة وإضافة الدورات الجديدة بنجاح');
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
            'photo_url' => $person->photo_url,
            'date_of_birth' => $person->date_of_birth_gregorian ? $person->date_of_birth_gregorian->format('Y-m-d') : null,
            'governorate_id' => $person->governorate_id,
            'directorate_id' => $person->directorate_id,
            'sub_area_id' => $person->sub_area_id,
            'village_id' => $person->village_id,
        ]);
    }

    /**
     * Print single course record.
     */
    public function print(Course $course)
    {
        $course->load(['personalData', 'courseType', 'sendingEntity']);
        return view('system.courses.print', compact('course'));
    }

    /**
     * Print all courses listing.
     */
    public function printAll(Request $request)
    {
        $courses = Course::with(['personalData', 'courseType', 'sendingEntity'])->get();
        return view('system.courses.print', [
            'courses' => $courses,
            'isList' => true,
        ]);
    }
}
