@extends('layouts.print')

@section('title', isset($isList) ? 'تقرير بيانات الأقارب' : 'تقرير قسيمة القريب - ' . ($relative->full_name ?? 'سجل'))

@section('content')
<div class="print-container">
    <div class="watermark-layer">وثيقة رسمية معتمدة</div>

    <x-print-header 
        :title="isset($isList) ? 'تقرير كشف الأهل والأقارب' : 'بطاقة بيانات القريب الرسمية'"
        :subCode="isset($isList) ? 'كشف شبكة الأقارب والارتباطات • معتمد رسمياً' : 'سجل قيد ارتباط أسري • معتمد رسمياً'"
        :refNumber="isset($isList) ? 'REL-ALL-' . date('Ymd') : 'REL-' . str_pad($relative->id ?? 1, 6, '0', STR_PAD_LEFT)" 
    />

    @if(isset($isList))
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-users"></i>
                    <span>قائمة سجلات الأقارب المسجلة (إجمالي: {{ count($records) }} سجل)</span>
                </div>
                <span class="section-header-badge">كشف رسمي</span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">#</th>
                        <th>اسم الفرد المرتبط</th>
                        <th>اسم القريب</th>
                        <th>صلة القرابة</th>
                        <th>مكان الإقامة / الدولة</th>
                        <th>المهنة / العمل</th>
                        <th>الانتماء</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $index => $item)
                        <tr>
                            <td style="text-align: center; font-weight: 700;">{{ $index + 1 }}</td>
                            <td style="font-weight: 800; color: #b91c1c;">{{ $item->personalData->name ?? 'غير محدد' }}</td>
                            <td style="font-weight: 700; color: #0f172a;">{{ $item->full_name ?? $item->name ?? '-' }}</td>
                            <td><span class="status-badge status-badge-info">{{ $item->kinship->name ?? '-' }}</span></td>
                            <td>{{ $item->residence_place ?? $item->country->name ?? '-' }}</td>
                            <td>{{ $item->job ?? '-' }}</td>
                            <td>
                                {{ $item->ideologicalAffiliation->name ?? '' }} 
                                @if(isset($item->politicalAffiliation->name))
                                    / {{ $item->politicalAffiliation->name }}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 25px; color: #64748b;">لا توجد بيانات أقارب للطباعة</td>
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
                    <span>بيانات القريب الأساسية والارتباط</span>
                </div>
                <span class="section-header-badge">سجل فردي</span>
            </div>
            <table class="data-table">
                <tbody>
                    <tr>
                        <th>الاسم الكامل للقريب</th>
                        <td style="font-size: 13px; font-weight: 800; color: #0f172a;">{{ $relative->full_name }}</td>
                        <th>صلة القرابة</th>
                        <td><span class="status-badge status-badge-info">{{ $relative->kinship->name ?? 'غير محدد' }}</span></td>
                    </tr>
                    <tr>
                        <th>الفرد الأساسي المرتبط به</th>
                        <td style="font-weight: 800; color: #b91c1c;">{{ $relative->personalData->name ?? 'غير محدد' }}</td>
                        <th>مكان الإقامة</th>
                        <td>{{ $relative->residence_place ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>المهنة / العمل الحالي</th>
                        <td>{{ $relative->job ?? '-' }}</td>
                        <th>رقم الهاتف</th>
                        <td dir="ltr" style="text-align: right; font-family: monospace;">{{ $relative->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>الانتماء العقائدي</th>
                        <td>{{ $relative->ideologicalAffiliation->name ?? '-' }}</td>
                        <th>الانتماء السياسي</th>
                        <td>{{ $relative->politicalAffiliation->name ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

    <x-print-footer />
</div>
@endsection
