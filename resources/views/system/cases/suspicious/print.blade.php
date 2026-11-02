@extends('layouts.print')

@section('title', isset($isList) ? 'تقرير سجلات المشتبه بهم' : 'ملف الاشتباه - ' . ($suspicious->personalData->name ?? 'سجل'))

@section('content')
<div class="print-container">
    <div class="watermark-layer">وثيقة رسمية معتمدة</div>

    <x-print-header 
        :title="isset($isList) ? 'تقرير قائمة المشتبه بهم والمتحرى عنهم' : 'بطاقة ملف الأشخاص المشتبه بهم'"
        :subCode="isset($isList) ? 'كشف المتابعة والتحري الأمني • معتمد رسمياً' : 'سجل رصد ومتابعة أمنية • معتمد رسمياً'"
        :refNumber="isset($isList) ? 'SUSP-ALL-' . date('Ymd') : 'SUSP-' . str_pad($suspicious->id ?? 1, 6, '0', STR_PAD_LEFT)" 
    />

    @if(isset($isList))
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-user-secret"></i>
                    <span>قائمة المشتبه بهم والمتحرى عنهم (إجمالي: {{ count($suspects) }} سجل)</span>
                </div>
                <span class="section-header-badge">كشف رسمي</span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">#</th>
                        <th>اسم المشتبه به</th>
                        <th>الاسم الجهادي</th>
                        <th>نوع الاشتباه / القضية</th>
                        <th>تاريخ قيد الاشتباه</th>
                        <th>المحافظة والمديرية</th>
                        <th>مستوى المتابعة / الملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suspects as $index => $item)
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
                            <td><span class="status-badge status-badge-warning">{{ $item->caseType->name ?? $item->suspicion_reason ?? '-' }}</span></td>
                            <td>{{ $item->suspicion_date ? date('Y/m/d', strtotime($item->suspicion_date)) : '-' }}</td>
                            <td>{{ $item->governorate->name ?? '-' }} / {{ $item->directorate->name ?? '-' }}</td>
                            <td><span class="status-badge status-badge-danger">{{ $item->notes ?? 'تحت المتابعة والرصد' }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 25px; color: #64748b;">لا توجد سجلات اشتباه للطباعة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-id-badge"></i>
                    <span>بيانات الشخص المشتبه به</span>
                </div>
                <span class="section-header-badge">بيانات الهوية</span>
            </div>
            <table class="data-table">
                <tbody>
                    <tr>
                        <th>اسم المشتبه به الكامل</th>
                        <td style="font-size: 13px; font-weight: 800; color: #0f172a;">{{ $suspicious->personalData->name ?? 'غير محدد' }}</td>
                        <th>الاسم الجهادي</th>
                        <td>
                            @if($suspicious->personalData?->jihadi_name)
                                <span class="jihadi-name-pill">{{ $suspicious->personalData->jihadi_name }}</span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>الرقم العسكري</th>
                        <td style="font-family: monospace; font-weight: 800; color: #b91c1c;">{{ $suspicious->personalData->military_number ?? '-' }}</td>
                        <th>المحافظة والمديرية</th>
                        <td>{{ $suspicious->governorate->name ?? '-' }} / {{ $suspicious->directorate->name ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-search-plus"></i>
                    <span>تفاصيل ودواعي الاشتباه والتحري</span>
                </div>
            </div>
            <table class="data-table">
                <tbody>
                    <tr>
                        <th>تاريخ تسجيل الاشتباه</th>
                        <td>{{ $suspicious->suspicion_date ? date('Y/m/d', strtotime($suspicious->suspicion_date)) : '-' }}</td>
                        <th>نوع القضية / التصنيف</th>
                        <td><span class="status-badge status-badge-warning">{{ $suspicious->caseType->name ?? 'غير محدد' }}</span></td>
                    </tr>
                    <tr>
                        <th>سبب ودواعي الاشتباه</th>
                        <td colspan="3">{{ $suspicious->suspicion_reason ?? 'غير مدون' }}</td>
                    </tr>
                    <tr>
                        <th>ملاحظات وتحريات المتابعة</th>
                        <td colspan="3">{{ $suspicious->notes ?? 'قيد الرصد والتحري المستمر' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

    <x-print-footer />
</div>
@endsection
