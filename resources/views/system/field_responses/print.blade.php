@extends('layouts.print')

@section('title', isset($isList) && $isList ? 'تقرير كشف الردود الميدانية' : 'تقرير رد ميداني - ' . ($response->personalData->name ?? 'سجل رقم ' . $response->id))

@section('content')
<div class="print-container" dir="rtl">
    <div class="watermark-layer">وثيقة رسمية معتمدة</div>

    <x-print-header 
        :title="isset($isList) && $isList ? 'تقرير كشف سجلات الرد الميداني' : 'تقرير محضر رد ميداني رسمي'"
        :subCode="isset($isList) && $isList ? 'كشف شامل ببيانات وقائع الردود الميدانية • معتمد رسمياً' : 'محضر إفادة ورد ميداني رسمي • معتمد رسمياً'"
        :refNumber="isset($isList) && $isList ? 'FLD-ALL-' . date('Ymd') : 'FLD-' . str_pad($response->id ?? 1, 6, '0', STR_PAD_LEFT)" 
    />

    @if(isset($isList) && $isList)
        {{-- BATCH LIST PRINT MODE --}}
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-satellite-dish me-1"></i>
                    <span>قائمة الردود الميدانية المسجلة (إجمالي: {{ count($responses) }} سجل)</span>
                </div>
                <span class="section-header-badge">كشف رسمي مجمع</span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 35px; text-align: center;">#</th>
                        <th>الشخص المعني</th>
                        <th>الرقم الوطني / العسكري</th>
                        <th>الرد الميداني</th>
                        <th style="text-align: center;">تاريخ الرد</th>
                        <th>المصدر / عمل المصدر</th>
                        <th>الجهة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($responses as $index => $item)
                        <tr>
                            <td style="text-align: center; font-weight: 700;">{{ $index + 1 }}</td>
                            <td style="font-weight: 800; color: #0f2942;">
                                {{ $item->personalData->name ?? '-' }}
                            </td>
                            <td style="font-family: monospace; font-weight: 700;">
                                {{ $item->personalData->national_id_number ?: ($item->personalData->military_number ?: '-') }}
                            </td>
                            <td style="font-weight: 600; color: #1e293b;">
                                {{ Str::limit($item->field_response, 100) }}
                            </td>
                            <td style="text-align: center; font-family: monospace;">
                                {{ $item->response_date ? $item->response_date->format('Y-m-d') : '-' }}
                            </td>
                            <td>
                                <div><strong>{{ $item->source ?: '-' }}</strong></div>
                                <div style="font-size: 11px; color: #64748b;">{{ $item->source_job ?: '-' }}</div>
                            </td>
                            <td>
                                <span style="font-weight: 700; color: #0284c7;">{{ $item->entity->name ?? '-' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 25px; color: #64748b;">لا توجد ردود ميدانية مسجلة للطباعة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        {{-- SINGLE RECORD PRINT MODE --}}
        
        {{-- Section 1: Person & Entity Info --}}
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-user-shield me-1"></i>
                    <span>بيانات الشخص المعني بالرد الميداني</span>
                </div>
                <span class="section-header-badge">بيانات رسمية</span>
            </div>

            <table class="details-table">
                <tr>
                    <td class="label">اسم الشخص الكامل:</td>
                    <td class="value" style="font-weight: 800; font-size: 15px; color: #0f2942;">
                        {{ $response->personalData->name ?? 'غير متاح' }}
                    </td>
                    <td class="label">الرقم الوطني:</td>
                    <td class="value" style="font-family: monospace; font-weight: 700;">
                        {{ $response->personalData->national_id_number ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="label">الرقم العسكري:</td>
                    <td class="value" style="font-family: monospace;">
                        {{ $response->personalData->military_number ?? '-' }}
                    </td>
                    <td class="label">الجهة التابع لها:</td>
                    <td class="value" style="font-weight: 700; color: #0284c7;">
                        {{ $response->entity->name ?? ($response->personalData->entity->name ?? '-') }}
                    </td>
                </tr>
            </table>
        </div>

        {{-- Section 2: Field Response Body --}}
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-satellite-dish me-1"></i>
                    <span>مضمون الرد الميداني والمعلومات الواردة</span>
                </div>
                <span class="section-header-badge">نص الإفادة</span>
            </div>

            <div style="padding: 15px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; line-height: 1.8; font-size: 14px; font-weight: 600; color: #0f172a; white-space: pre-line;">
                {{ $response->field_response }}
            </div>
        </div>

        {{-- Section 3: Metadata & Source Info --}}
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-info-circle me-1"></i>
                    <span>تأصيل المصادر والملاحظات</span>
                </div>
                <span class="section-header-badge">توثيق الرسميات</span>
            </div>

            <table class="details-table">
                <tr>
                    <td class="label">تاريخ الرد الميداني:</td>
                    <td class="value" style="font-family: monospace; font-weight: 700;">
                        {{ $response->response_date ? $response->response_date->format('Y-m-d') : 'غير محدد' }}
                    </td>
                    <td class="label">المصدر الميداني:</td>
                    <td class="value" style="font-weight: 700;">
                        {{ $response->source ?: 'غير مدخل' }}
                    </td>
                </tr>
                <tr>
                    <td class="label">عمل / صفة المصدر:</td>
                    <td class="value">
                        {{ $response->source_job ?: 'غير مدخل' }}
                    </td>
                    <td class="label">المُدخل بواسطة:</td>
                    <td class="value">
                        {{ $response->user->name ?? 'النظام' }}
                    </td>
                </tr>
                @if($response->notes)
                    <tr>
                        <td class="label">الملاحظات الإضافية:</td>
                        <td class="value" colspan="3">
                            {{ $response->notes }}
                        </td>
                    </tr>
                @endif
            </table>
        </div>
    @endif

    <x-print-footer />
</div>
@endsection
