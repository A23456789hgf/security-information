@extends('layouts.print')

@section('title', isset($isList) ? 'تقرير كشف الدورات التدريبية' : 'وثيقة دورة تدريبية - ' . ($course->personalData->name ?? 'سجل'))

@section('content')
    <x-print-header 
        :title="isset($isList) ? 'تقرير قائمة سجلات الدورات التدريبية' : 'وثيقة وسجل دورة تدريبية لـ فرد'"
        :refNumber="isset($isList) ? 'CRS-ALL-' . date('Ymd') : 'CRS-' . ($course->id ?? '00')" 
    />

    <div class="no-print mb-6 flex justify-between items-center bg-slate-100 p-4 rounded-xl">
        <span class="text-sm font-bold text-slate-700">معاينة تقرير الدورات للطباعة</span>
        <button onclick="window.print()" class="px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl shadow hover:bg-indigo-700 transition flex items-center gap-2">
            <i class="fas fa-print"></i> تنفيذ الطباعة الآن
        </button>
    </div>

    @if(isset($isList))
        <table class="w-full text-right border-collapse">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>اسم الفرد الملتحق</th>
                    <th>نوع الدورة التدريبية</th>
                    <th>الجهة الموفدة</th>
                    <th>تاريخ عقد الدورة</th>
                    <th>مدة الدورة</th>
                    <th>مسؤول / قائد الدورة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $item->personalData->name ?? 'غير محدد' }}</strong></td>
                        <td>{{ $item->courseType->name ?? '-' }}</td>
                        <td>{{ $item->sendingEntity->name ?? '-' }}</td>
                        <td>{{ $item->course_date_gregorian ? date('Y/m/d', strtotime($item->course_date_gregorian)) : '-' }}</td>
                        <td>{{ $item->course_duration ?? '-' }}</td>
                        <td>{{ $item->course_administrator ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">لا توجد سجلات دورات للطباعة</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @else
        <div class="report-section">
            <div class="section-title">أولاً: بيانات الفرد الملتحق بالدورة</div>
            <table>
                <tr>
                    <th>اسم الفرد الكامل</th>
                    <td><strong>{{ $course->personalData->name ?? 'غير محدد' }}</strong></td>
                    <th>الاسم الجهادي</th>
                    <td>{{ $course->personalData->jihadi_name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>الرقم العسكري</th>
                    <td>{{ $course->personalData->military_number ?? '-' }}</td>
                    <th>الرقم القومي / الهوية</th>
                    <td>{{ $course->personalData->national_id_number ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="report-section">
            <div class="section-title">ثانياً: تفاصيل الدورة التدريبية</div>
            <table>
                <tr>
                    <th>نوع الدورة ومجالها</th>
                    <td><strong>{{ $course->courseType->name ?? 'غير محدد' }}</strong></td>
                    <th>الجهة الموفدة / التابعة لها</th>
                    <td>{{ $course->sendingEntity->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>تاريخ الدورة الميلادي</th>
                    <td>{{ $course->course_date_gregorian ? date('Y/m/d', strtotime($course->course_date_gregorian)) : '-' }}</td>
                    <th>تاريخ الدورة الهجري</th>
                    <td>{{ $course->course_date_hijri ?? '-' }}</td>
                </tr>
                <tr>
                    <th>مدة الدورة التدريبية</th>
                    <td>{{ $course->course_duration ?? '-' }}</td>
                    <th>مسؤول / مشرف الدورة</th>
                    <td>{{ $course->course_administrator ?? '-' }}</td>
                </tr>
                <tr>
                    <th>ملاحظات وتقييم الدورة</th>
                    <td colspan="3">{{ $course->notes ?? 'لا توجد ملاحظات مدونة' }}</td>
                </tr>
            </table>
        </div>
    @endif
@endsection
