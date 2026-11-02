@extends('layouts.print')

@section('title', isset($isList) ? 'تقرير كشف الدورات التدريبية' : 'وثيقة دورة تدريبية - ' . ($course->personalData->name ?? 'سجل'))

@section('content')
<div class="print-container">
    <div class="watermark-layer">وثيقة رسمية معتمدة</div>

    <x-print-header 
        :title="isset($isList) ? 'تقرير كشف الدورات التدريبية' : 'وثيقة وسجل دورة تدريبية لـ فرد'"
        :subCode="isset($isList) ? 'كشف برامج ودورات التأهيل • معتمد رسمياً' : 'سجل تدريب وتأهيل عسكري • معتمد رسمياً'"
        :refNumber="isset($isList) ? 'CRS-ALL-' . date('Ymd') : 'CRS-' . str_pad($course->id ?? 1, 6, '0', STR_PAD_LEFT)" 
    />

    @if(isset($isList))
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-graduation-cap"></i>
                    <span>قائمة سجلات الدورات التدريبية (إجمالي: {{ count($courses) }} سجل)</span>
                </div>
                <span class="section-header-badge">كشف رسمي</span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">#</th>
                        <th>اسم الفرد الملتحق</th>
                        <th>نوع الدورة التدريبية</th>
                        <th>الجهة الموفدة</th>
                        <th>تاريخ عقد الدورة</th>
                        <th>مدة الدورة</th>
                        <th>مسؤول / مشرف الدورة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $index => $item)
                        <tr>
                            <td style="text-align: center; font-weight: 700;">{{ $index + 1 }}</td>
                            <td style="font-weight: 800; color: #0f172a;">{{ $item->personalData->name ?? 'غير محدد' }}</td>
                            <td><span class="status-badge status-badge-info">{{ $item->courseType->name ?? '-' }}</span></td>
                            <td>{{ $item->sendingEntity->name ?? '-' }}</td>
                            <td>{{ $item->course_date_gregorian ? date('Y/m/d', strtotime($item->course_date_gregorian)) : '-' }}</td>
                            <td style="font-weight: 700;">{{ $item->course_duration ?? '-' }}</td>
                            <td>{{ $item->course_administrator ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 25px; color: #64748b;">لا توجد سجلات دورات للطباعة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-user-check"></i>
                    <span>بيانات الفرد الملتحق بالدورة</span>
                </div>
                <span class="section-header-badge">السجل التدريبي</span>
            </div>
            <table class="data-table">
                <tbody>
                    <tr>
                        <th>اسم الفرد الكامل</th>
                        <td style="font-size: 13px; font-weight: 800; color: #0f172a;">{{ $course->personalData->name ?? 'غير محدد' }}</td>
                        <th>الاسم الجهادي</th>
                        <td>
                            @if($course->personalData?->jihadi_name)
                                <span class="jihadi-name-pill">{{ $course->personalData->jihadi_name }}</span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>الرقم العسكري</th>
                        <td style="font-family: monospace; font-weight: 800; color: #b91c1c;">{{ $course->personalData->military_number ?? '-' }}</td>
                        <th>الرقم الوطني / الهوية</th>
                        <td style="font-family: monospace;">{{ $course->personalData->national_id_number ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-certificate"></i>
                    <span>تفاصيل وبيانات الدورة التدريبية</span>
                </div>
            </div>
            <table class="data-table">
                <tbody>
                    <tr>
                        <th>نوع الدورة ومجالها</th>
                        <td style="font-weight: 800; color: #0f172a;">{{ $course->courseType->name ?? 'غير محدد' }}</td>
                        <th>الجهة الموفدة / التابعة لها</th>
                        <td>{{ $course->sendingEntity->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>تاريخ الدورة (ميلادي)</th>
                        <td>{{ $course->course_date_gregorian ? date('Y/m/d', strtotime($course->course_date_gregorian)) : '-' }}</td>
                        <th>تاريخ الدورة (هجري)</th>
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
                </tbody>
            </table>
        </div>
    @endif

    <x-print-footer />
</div>
@endsection
