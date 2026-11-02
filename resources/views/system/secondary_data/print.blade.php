@extends('layouts.print')

@section('title', isset($isList) ? 'تقرير قائمة البيانات الثانوية' : 'تقرير البيانات الثانوية - ' . ($secondaryData->personalData->name ?? 'سجل'))

@section('content')
<div class="print-container">
    <div class="watermark-layer">وثيقة رسمية معتمدة</div>

    <x-print-header 
        :title="isset($isList) ? 'تقرير قائمة البيانات الثانوية والتعبوية' : 'بطاقة التقرير الثانوي والسلوك التعبوي'"
        :subCode="isset($isList) ? 'كشف البيانات التعبوية والتسليح • معتمد رسمياً' : 'سجل قيد ثانوي تفصيلي • معتمد رسمياً'"
        :refNumber="isset($isList) ? 'SEC-ALL-' . date('Ymd') : 'SEC-' . str_pad($secondaryData->id ?? 1, 6, '0', STR_PAD_LEFT)" 
    />

    @if(isset($isList))
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-shield-alt"></i>
                    <span>قائمة السجلات الثانوية (إجمالي: {{ count($secondaryDataList) }} سجل)</span>
                </div>
                <span class="section-header-badge">كشف رسمي</span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">#</th>
                        <th>الاسم الرباعي واللقب</th>
                        <th>الاسم الجهادي</th>
                        <th>الرقم العسكري</th>
                        <th>الحالة الصحية</th>
                        <th>الأسلحة الشخصية</th>
                        <th>الانتماءات السابقة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($secondaryDataList as $index => $item)
                        <tr>
                            <td style="text-align: center; font-weight: 700;">{{ $index + 1 }}</td>
                            <td style="font-weight: 800; color: #0f172a;">{{ $item->personalData->name ?? 'غير محدد' }}</td>
                            <td>
                                @if($item->personalData?->jihadi_name)
                                    <span class="jihadi-name-pill">{{ $item->personalData->jihadi_name }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td style="font-family: monospace; font-weight: 700; color: #b91c1c;">
                                {{ $item->personalData->military_number ?? '-' }}
                            </td>
                            <td>{{ $item->healthStatus->name ?? 'سليم' }}</td>
                            <td>
                                @if($item->weapons && $item->weapons->count() > 0)
                                    {{ $item->weapons->pluck('weapon_type')->implode('، ') }}
                                @else
                                    <span style="color: #94a3b8;">لا يوجد</span>
                                @endif
                            </td>
                            <td>
                                @forelse($item->affiliations as $aff)
                                    <div>{{ $aff->ideologicalAffiliation->name ?? '' }} / {{ $aff->politicalAffiliation->name ?? '' }}</div>
                                @empty
                                    -
                                @endforelse
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 25px; color: #64748b;">لا توجد بيانات متاحة للطباعة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-id-card"></i>
                    <span>البيانات الأساسية للفرد</span>
                </div>
                <span class="section-header-badge">السجل الميداني</span>
            </div>
            <table class="data-table">
                <tbody>
                    <tr>
                        <th>الاسم الرباعي واللقب</th>
                        <td style="font-size: 13px; font-weight: 800; color: #0f172a;">{{ $secondaryData->personalData->name ?? 'غير محدد' }}</td>
                        <th>الاسم الجهادي</th>
                        <td>
                            @if($secondaryData->personalData?->jihadi_name)
                                <span class="jihadi-name-pill">{{ $secondaryData->personalData->jihadi_name }}</span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>الرقم العسكري</th>
                        <td style="font-family: monospace; font-weight: 800; color: #b91c1c;">{{ $secondaryData->personalData->military_number ?? 'غير مسجل' }}</td>
                        <th>الحالة الصحية</th>
                        <td>{{ $secondaryData->healthStatus->name ?? 'سليم' }}</td>
                    </tr>
                    <tr>
                        <th>المحافظة والمديرية</th>
                        <td>{{ $secondaryData->personalData->governorate->name ?? '-' }} / {{ $secondaryData->personalData->directorate->name ?? '-' }}</td>
                        <th>القرية / المحلة</th>
                        <td>{{ $secondaryData->personalData->village->name ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- تفاصيل الأسلحة والعتاد --}}
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-crosshairs"></i>
                    <span>سجل العتاد والأسلحة العسكرية الشخصية</span>
                </div>
            </div>
            <table class="data-table">
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
                            <td style="font-weight: 700;">{{ $weapon->weapon_type ?? '-' }}</td>
                            <td style="font-family: monospace;">{{ $weapon->serial_number ?? '-' }}</td>
                            <td>{{ $weapon->source ?? '-' }}</td>
                            <td>{{ $weapon->caliber ?? '-' }}</td>
                            <td>{{ $weapon->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 15px; color: #94a3b8;">لا توجد أسلحة مسجلة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- الانتماءات السابقة --}}
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-flag"></i>
                    <span>الانتماءات العقائدية والسياسية السابقة</span>
                </div>
            </div>
            <table class="data-table">
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
                            <td style="font-weight: 700;">{{ $aff->ideologicalAffiliation->name ?? '-' }}</td>
                            <td>{{ $aff->politicalAffiliation->name ?? '-' }}</td>
                            <td>{{ $aff->affiliation_date ? (is_a($aff->affiliation_date, 'DateTimeInterface') ? $aff->affiliation_date->format('Y') : date('Y', strtotime($aff->affiliation_date))) : '-' }}</td>
                            <td>{{ $aff->workplace ?? '-' }} ({{ $aff->job_type ?? '-' }})</td>
                            <td>{{ $aff->leaving_reason ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 15px; color: #94a3b8;">لا توجد انتماءات سابقة مقيدة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- الأعمال والنشاطات الجهادية السابقة --}}
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-tasks"></i>
                    <span>النشاطات والأعمال السابقة</span>
                </div>
            </div>
            <table class="data-table">
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
                            <td style="font-weight: 700;">{{ $act->activity ?? '-' }}</td>
                            <td>{{ $act->location ?? '-' }}</td>
                            <td>{{ $act->direct_supervisor ?? '-' }}</td>
                            <td>{{ $act->activity_date ? (is_a($act->activity_date, 'DateTimeInterface') ? $act->activity_date->format('Y') : date('Y', strtotime($act->activity_date))) : '-' }} ({{ $act->duration ?? '-' }})</td>
                            <td>{{ $act->leaving_reason ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 15px; color: #94a3b8;">لا توجد نشاطات سابقة مقيدة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    <x-print-footer />
</div>
@endsection
