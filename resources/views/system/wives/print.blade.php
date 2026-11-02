@extends('layouts.print')

@section('title', isset($isList) ? 'تقرير بيانات الزوجات' : 'بطاقة الزوجة - ' . ($wife->name ?? 'سجل'))

@section('content')
<div class="print-container">
    <div class="watermark-layer">وثيقة رسمية معتمدة</div>

    <x-print-header 
        :title="isset($isList) ? 'تقرير بيانات زوجات الأفراد' : 'بطاقة التقرير الرسمي لبيانات الزوجة'"
        :subCode="isset($isList) ? 'كشف البيانات العائلية • معتمد رسمياً' : 'سجل قيد الزوجة • معتمد رسمياً'"
        :refNumber="isset($isList) ? 'WIFE-ALL-' . date('Ymd') : 'WIFE-' . str_pad($wife->id ?? 1, 6, '0', STR_PAD_LEFT)" 
    />

    @if(isset($isList))
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-heart"></i>
                    <span>قائمة سجلات الزوجات المقيدة بالنظام (إجمالي: {{ count($wives) }} سجل)</span>
                </div>
                <span class="section-header-badge">كشف رسمي</span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">#</th>
                        <th>اسم الزوج (الفرد)</th>
                        <th>اسم الزوجة الكامل</th>
                        <th>الحالة الزوجية</th>
                        <th>رقم الهوية الوطنية</th>
                        <th>رقم الهاتف</th>
                        <th>المحافظة والمديرية</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wives as $index => $item)
                        <tr>
                            <td style="text-align: center; font-weight: 700;">{{ $index + 1 }}</td>
                            <td><strong>{{ $item->personalData->name ?? 'غير محدد' }}</strong></td>
                            <td style="font-weight: 700; color: #0f172a;">{{ $item->name }}</td>
                            <td><span class="status-badge status-badge-info">{{ $item->status ?? 'مستمرة' }}</span></td>
                            <td style="font-family: monospace;">{{ $item->wife_id_number ?? '-' }}</td>
                            <td dir="ltr" style="text-align: right; font-family: monospace;">{{ $item->wife_phone ?? '-' }}</td>
                            <td>{{ $item->governorate->name ?? '-' }} / {{ $item->directorate->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 25px; color: #64748b;">لا توجد بيانات زوجات للطباعة</td>
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
                    <span>البيانات الأساسية للزوجة والربط الأسري</span>
                </div>
                <span class="section-header-badge">سجل فردي</span>
            </div>
            <table class="data-table">
                <tbody>
                    <tr>
                        <th>اسم الزوجة الكامل</th>
                        <td style="font-size: 13px; font-weight: 800; color: #0f172a;">{{ $wife->name }}</td>
                        <th>الحالة الزوجية</th>
                        <td><span class="status-badge status-badge-info">{{ $wife->status ?? 'مستمرة' }}</span></td>
                    </tr>
                    <tr>
                        <th>اسم الزوج المرتبط بها</th>
                        <td style="font-weight: 800; color: #b91c1c;">{{ $wife->personalData->name ?? 'غير محدد' }}</td>
                        <th>الرقم العسكري للزوج</th>
                        <td style="font-family: monospace; font-weight: 800; color: #b91c1c;">{{ $wife->personalData->military_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>رقم الهوية الوطنية للزوجة</th>
                        <td style="font-family: monospace; font-weight: 700;">{{ $wife->wife_id_number ?? 'غير مسجل' }}</td>
                        <th>تاريخ الميلاد</th>
                        <td>{{ $wife->wife_birth_date ? date('Y/m/d', strtotime($wife->wife_birth_date)) : '-' }}</td>
                    </tr>
                    <tr>
                        <th>رقم الهاتف</th>
                        <td dir="ltr" style="text-align: right; font-family: monospace;">{{ $wife->wife_phone ?? '-' }}</td>
                        <th>العنوان / مكان الإقامة</th>
                        <td>{{ $wife->wife_address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>المحافظة والمديرية</th>
                        <td>{{ $wife->governorate->name ?? '-' }} / {{ $wife->directorate->name ?? '-' }}</td>
                        <th>العزلة والقرية</th>
                        <td>{{ $wife->subArea->name ?? '-' }} / {{ $wife->village->name ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

    <x-print-footer />
</div>
@endsection
