@extends('layouts.print')

@section('title', 'كشف السجل العام للبيانات الشخصية')

@section('content')
<div class="print-container">
    <div class="watermark-layer">وثيقة رسمية معتمدة</div>

    <x-print-header 
        title="كشف السجل العام للبيانات الشخصية"
        subCode="قيد وسجل المنتسبين والأفراد • معتمد رسمياً"
        :refNumber="'PRSN-LIST-' . date('Ymd')"
    />

    <div class="section-card">
        <div class="section-header">
            <div class="section-header-title">
                <i class="fas fa-users"></i>
                <span>قائمة المنتسبين المسجلين (إجمالي: {{ count($personalDataList) }} سجل)</span>
            </div>
            <span class="section-header-badge">السجل الموحد</span>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">#</th>
                    <th>الاسم الرباعي واللقب</th>
                    <th>الاسم الجهادي</th>
                    <th>الرتبة العسكرية</th>
                    <th>الرقم العسكري</th>
                    <th>رقم الهوية الوطنية</th>
                    <th>المحافظة والمديرية</th>
                    <th>فصيلة الدم</th>
                    <th>رقم الهاتف</th>
                </tr>
            </thead>
            <tbody>
                @forelse($personalDataList as $index => $item)
                    <tr>
                        <td style="text-align: center; font-weight: 700;">{{ $index + 1 }}</td>
                        <td style="font-weight: 800; color: #0f172a;">{{ $item->name }}</td>
                        <td>
                            @if($item->jihadi_name)
                                <span class="jihadi-name-pill">{{ $item->jihadi_name }}</span>
                            @else
                                <span style="color: #94a3b8;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($item->rank)
                                <span class="profile-rank-tag">{{ $item->rank->name }}</span>
                            @else
                                <span style="color: #94a3b8;">-</span>
                            @endif
                        </td>
                        <td style="font-family: monospace; font-weight: 700; color: #b91c1c;">
                            {{ $item->military_number ?? '-' }}
                        </td>
                        <td style="font-family: monospace; font-weight: 600;">
                            {{ $item->national_id_number ?? '-' }}
                        </td>
                        <td>{{ $item->governorate->name ?? '-' }} / {{ $item->directorate->name ?? '-' }}</td>
                        <td>
                            @if($item->bloodType)
                                <span class="blood-badge">{{ $item->bloodType->type }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td dir="ltr" style="text-align: right; font-family: monospace;">
                            {{ $item->phone_1 ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 25px; color: #64748b;">
                            لا توجد سجلات بيانات شخصية مطابقة لشروط العرض
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-print-footer />
</div>
@endsection
