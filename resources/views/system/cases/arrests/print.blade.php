@extends('layouts.print')

@section('title', isset($isList) ? 'تقرير سجلات الضبط والتوقيف' : 'محضر ضبط وقضية - ' . ($arrest->personalData->name ?? 'سجل'))

@section('content')
<div class="print-container">
    <div class="watermark-layer">وثيقة رسمية معتمدة</div>

    <x-print-header 
        :title="isset($isList) ? 'تقرير سجلات الضبط والتوقيف' : 'محضر رسمي لواقعة ضبط وتوقيف'"
        :subCode="isset($isList) ? 'كشف قضايا الضبط والأحراز • معتمد رسمياً' : 'محضر تحقيق وضبط أمني • معتمد رسمياً'"
        :refNumber="isset($isList) ? 'ARR-ALL-' . date('Ymd') : 'ARR-' . str_pad($arrest->id ?? 1, 6, '0', STR_PAD_LEFT)" 
    />

    @if(isset($isList))
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-gavel"></i>
                    <span>قائمة سجلات الموقوفين والقضايا (إجمالي: {{ count($arrests) }} سجل)</span>
                </div>
                <span class="section-header-badge">كشف رسمي</span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">#</th>
                        <th>اسم الفرد الموقوف</th>
                        <th>نوع القضية / التهمة</th>
                        <th>جهة الضبط</th>
                        <th>تاريخ الضبط والتوقيف</th>
                        <th>مكان الضبط</th>
                        <th>حالة التوقيف / الإفراج</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($arrests as $index => $item)
                        <tr>
                            <td style="text-align: center; font-weight: 700;">{{ $index + 1 }}</td>
                            <td style="font-weight: 800; color: #0f172a;">{{ $item->personalData->name ?? 'غير محدد' }}</td>
                            <td><span class="status-badge status-badge-warning">{{ $item->caseType->name ?? $item->charge ?? '-' }}</span></td>
                            <td>{{ $item->arrestingAuthority->name ?? '-' }}</td>
                            <td>{{ $item->arrest_date ? date('Y/m/d', strtotime($item->arrest_date)) : '-' }}</td>
                            <td>{{ $item->arrest_location ?? '-' }}</td>
                            <td>
                                @if($item->release_date)
                                    <span class="status-badge status-badge-success">أفرج عنه ({{ date('Y/m/d', strtotime($item->release_date)) }})</span>
                                @else
                                    <span class="status-badge status-badge-danger">قيد التوقيف</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 25px; color: #64748b;">لا توجد سجلات ضبط وتوقيف للطباعة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-user-lock"></i>
                    <span>بيانات الشخص الموقوف</span>
                </div>
                <span class="section-header-badge">بيانات الهوية</span>
            </div>
            <table class="data-table">
                <tbody>
                    <tr>
                        <th>اسم الموقوف الكامل</th>
                        <td style="font-size: 13px; font-weight: 800; color: #0f172a;">{{ $arrest->personalData->name ?? 'غير محدد' }}</td>
                        <th>الاسم الجهادي</th>
                        <td>
                            @if($arrest->personalData?->jihadi_name)
                                <span class="jihadi-name-pill">{{ $arrest->personalData->jihadi_name }}</span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>الرقم العسكري</th>
                        <td style="font-family: monospace; font-weight: 800; color: #b91c1c;">{{ $arrest->personalData->military_number ?? '-' }}</td>
                        <th>المحافظة والمديرية</th>
                        <td>{{ $arrest->governorate->name ?? '-' }} / {{ $arrest->directorate->name ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-file-invoice"></i>
                    <span>تفاصيل محضر الضبط والقضية</span>
                </div>
            </div>
            <table class="data-table">
                <tbody>
                    <tr>
                        <th>جهة الضبط المسؤولة</th>
                        <td style="font-weight: 800; color: #0f172a;">{{ $arrest->arrestingAuthority->name ?? 'غير محدد' }}</td>
                        <th>نوع القضية والتصنيف</th>
                        <td><span class="status-badge status-badge-warning">{{ $arrest->caseType->name ?? 'غير محدد' }}</span></td>
                    </tr>
                    <tr>
                        <th>تاريخ الضبط</th>
                        <td>{{ $arrest->arrest_date ? date('Y/m/d', strtotime($arrest->arrest_date)) : '-' }}</td>
                        <th>مكان موقع الضبط</th>
                        <td>{{ $arrest->arrest_location ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>مكان الاحتجاز / السجن</th>
                        <td>{{ $arrest->detention_place ?? '-' }}</td>
                        <th>تاريخ وحالة الإفراج</th>
                        <td>
                            @if($arrest->release_date)
                                <span class="status-badge status-badge-success">تم الإفراج بتاريخ: {{ date('Y/m/d', strtotime($arrest->release_date)) }}</span>
                            @else
                                <span class="status-badge status-badge-danger">لا يزال قيد التوقيف</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>التهمة والملابسات</th>
                        <td colspan="3">{{ $arrest->charge ?? 'لا يوجد بيان تفصيلي' }}</td>
                    </tr>
                    <tr>
                        <th>ملاحظات الإجراءات القضائية</th>
                        <td colspan="3">{{ $arrest->notes ?? 'لا توجد ملاحظات إضافية' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

    <x-print-footer />
</div>
@endsection
