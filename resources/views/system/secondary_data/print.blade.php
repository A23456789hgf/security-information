@extends('layouts.print')

@section('title', isset($isList) ? 'تقرير قائمة البيانات الثانوية' : 'تقرير البيانات الثانوية - ' . ($secondaryData->personalData->name ?? 'سجل'))

@section('content')
    <x-print-header 
        :title="isset($isList) ? 'تقرير قائمة البيانات الثانوية والسلوك التعبوي' : 'بطاقة التقرير الثانوي والسلوك التعبوي'"
        :refNumber="isset($isList) ? 'SEC-ALL-' . date('Ymd') : 'SEC-' . ($secondaryData->id ?? '00')" 
    />

    <div class="no-print mb-6 flex justify-between items-center bg-slate-100 p-4 rounded-xl">
        <span class="text-sm font-bold text-slate-700">معاينة التقرير الجاهز للطباعة</span>
        <button onclick="window.print()" class="px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl shadow hover:bg-indigo-700 transition flex items-center gap-2">
            <i class="fas fa-print"></i> تنفيذ الطباعة الآن
        </button>
    </div>

    @if(isset($isList))
        {{-- عرض الجدول الإجمالي للطباعة --}}
        <table class="w-full text-right border-collapse">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>الاسم الكامل</th>
                    <th>الاسم الجهادي</th>
                    <th>الرقم العسكري</th>
                    <th>الحالة الصحية</th>
                    <th>الأسلحة الشخصية</th>
                    <th>الانتماءات العقائدية والسياسية</th>
                </tr>
            </thead>
            <tbody>
                @forelse($secondaryDataList as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $item->personalData->name ?? 'غير محدد' }}</strong></td>
                        <td>{{ $item->personalData->jihadi_name ?? '-' }}</td>
                        <td>{{ $item->personalData->military_number ?? '-' }}</td>
                        <td>{{ $item->healthStatus->name ?? 'سليم' }}</td>
                        <td>
                            @if($item->weapons && $item->weapons->count() > 0)
                                {{ $item->weapons->pluck('weapon_type')->implode('، ') }}
                            @else
                                لا يوجد
                            @endif
                        </td>
                        <td>
                            @forelse($item->affiliations as $aff)
                                <span class="block text-xs">
                                    {{ $aff->ideologicalAffiliation->name ?? '' }} / {{ $aff->politicalAffiliation->name ?? '' }}
                                </span>
                            @empty
                                -
                            @endforelse
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">لا توجد بيانات متاحة للطباعة</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @else
        {{-- عرض التقرير التفصيلي الفردي للطباعة --}}
        <div class="report-section">
            <div class="section-title">أولاً: البيانات الأساسية للفرد</div>
            <table>
                <tr>
                    <th>الاسم الرباعي واللقب</th>
                    <td><strong>{{ $secondaryData->personalData->name ?? 'غير محدد' }}</strong></td>
                    <th>الاسم الجهادي</th>
                    <td>{{ $secondaryData->personalData->jihadi_name ?? 'غير محدد' }}</td>
                </tr>
                <tr>
                    <th>الرقم العسكري</th>
                    <td>{{ $secondaryData->personalData->military_number ?? 'غير مسجل' }}</td>
                    <th>الحالة الصحية</th>
                    <td>{{ $secondaryData->healthStatus->name ?? 'سليم' }}</td>
                </tr>
                <tr>
                    <th>المحافظة والمديرية</th>
                    <td>{{ $secondaryData->personalData->governorate->name ?? '-' }} / {{ $secondaryData->personalData->directorate->name ?? '-' }}</td>
                    <th>القرية / المحلة</th>
                    <td>{{ $secondaryData->personalData->village->name ?? '-' }}</td>
                </tr>
            </table>
        </div>

        {{-- تفاصيل الأسلحة والعتاد --}}
        <div class="report-section">
            <div class="section-title">ثانياً: سجل العتاد والأسلحة العسكرية الشخصية</div>
            <table>
                <thead>
                    <tr>
                        <th>نوع السلاح</th>
                        <th>الرقم التسلسلي</th>
                        <th>المصدر / الجهة</th>
                        <th>العيار / التفاصيل</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($secondaryData->weapons as $weapon)
                        <tr>
                            <td>{{ $weapon->weapon_type ?? '-' }}</td>
                            <td>{{ $weapon->serial_number ?? '-' }}</td>
                            <td>{{ $weapon->source ?? '-' }}</td>
                            <td>{{ $weapon->caliber ?? '-' }}</td>
                            <td>{{ $weapon->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">لا توجد أسلحة مسجلة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- الانتماءات السابقة --}}
        <div class="report-section">
            <div class="section-title">ثالثاً: الانتماءات العقائدية والسياسية السابقة</div>
            <table>
                <thead>
                    <tr>
                        <th>الانتماء العقائدي</th>
                        <th>الانتماء السياسي</th>
                        <th>عام الانتماء</th>
                        <th>مكان العمل / الصفة</th>
                        <th>سبب المغادرة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($secondaryData->affiliations as $aff)
                        <tr>
                            <td>{{ $aff->ideologicalAffiliation->name ?? '-' }}</td>
                            <td>{{ $aff->politicalAffiliation->name ?? '-' }}</td>
                            <td>{{ $aff->affiliation_date ? (is_a($aff->affiliation_date, 'DateTimeInterface') ? $aff->affiliation_date->format('Y') : date('Y', strtotime($aff->affiliation_date))) : '-' }}</td>
                            <td>{{ $aff->workplace ?? '-' }} ({{ $aff->job_type ?? '-' }})</td>
                            <td>{{ $aff->leaving_reason ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">لا توجد انتماءات مسجلة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- الأعمال والنشاطات الجهادية السابقة --}}
        <div class="report-section">
            <div class="section-title">رابعاً: النشاطات والأعمال الجهادية السابقة</div>
            <table>
                <thead>
                    <tr>
                        <th>نوع النشاط / العمل</th>
                        <th>الموقع / المنطقة</th>
                        <th>المسؤول المباشر</th>
                        <th>العام / المدة</th>
                        <th>سبب الانتهاء / المغادرة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($secondaryData->previousJihadistActions as $act)
                        <tr>
                            <td>{{ $act->activity ?? '-' }}</td>
                            <td>{{ $act->location ?? '-' }}</td>
                            <td>{{ $act->direct_supervisor ?? '-' }}</td>
                            <td>{{ $act->activity_date ? (is_a($act->activity_date, 'DateTimeInterface') ? $act->activity_date->format('Y') : date('Y', strtotime($act->activity_date))) : '-' }} ({{ $act->duration ?? '-' }})</td>
                            <td>{{ $act->leaving_reason ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">لا توجد نشاطات مسجلة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
@endsection
