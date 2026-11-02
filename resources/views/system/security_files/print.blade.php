@extends('layouts.print')

@section('title', isset($isList) ? 'تقرير كشف الملفات الأمنية' : 'وثيقة الملف الأمني - ' . ($securityFile->personalData->name ?? 'سجل'))

@section('content')
<div class="print-container">
    <div class="watermark-layer">وثيقة رسمية معتمدة</div>

    <x-print-header 
        :title="isset($isList) ? 'تقرير قائمة وسجلات الملفات الأمنية' : 'وثيقة واستمارة الملف الأمني الرسمي'"
        :subCode="isset($isList) ? 'أرشيف الملفات والقيود الأمنية • معتمد رسمياً' : 'ملف أمني سري ومحفوظ • معتمد رسمياً'"
        :refNumber="isset($isList) ? 'SECFILE-ALL-' . date('Ymd') : 'SECFILE-' . ($securityFile->file_number ?? str_pad($securityFile->id ?? 1, 6, '0', STR_PAD_LEFT))" 
    />

    @if(isset($isList))
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-folder-open"></i>
                    <span>قائمة الملفات الأمنية المسجلة (إجمالي: {{ count($securityFiles) }} ملف)</span>
                </div>
                <span class="section-header-badge">أرشيف سري</span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">#</th>
                        <th>رقم الملف</th>
                        <th>اسم الفرد المرتبط</th>
                        <th>الجهة المعدة للملف</th>
                        <th>تاريخ الملف</th>
                        <th>معد الملف</th>
                        <th>معد التلخيص</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($securityFiles as $index => $item)
                        <tr>
                            <td style="text-align: center; font-weight: 700;">{{ $index + 1 }}</td>
                            <td style="font-family: monospace; font-weight: 800; color: #b91c1c;">{{ $item->file_number ?? '-' }}</td>
                            <td style="font-weight: 800; color: #0f172a;">{{ $item->personalData->name ?? 'غير محدد' }}</td>
                            <td>{{ $item->preparingEntity->name ?? '-' }}</td>
                            <td>{{ $item->file_date_gregorian ? date('Y/m/d', strtotime($item->file_date_gregorian)) : '-' }}</td>
                            <td>{{ $item->file_preparer ?? '-' }}</td>
                            <td>{{ $item->summary_preparer ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 25px; color: #64748b;">لا توجد ملفات أمنية للطباعة</td>
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
                    <span>البيانات الأساسية والتعريفية بالفرد</span>
                </div>
                <span class="section-header-badge">بيانات الهوية</span>
            </div>
            <table class="data-table">
                <tbody>
                    <tr>
                        <th>اسم الفرد الكامل</th>
                        <td style="font-size: 13px; font-weight: 800; color: #0f172a;">{{ $securityFile->personalData->name ?? 'غير محدد' }}</td>
                        <th>الاسم الجهادي</th>
                        <td>
                            @if($securityFile->personalData?->jihadi_name)
                                <span class="jihadi-name-pill">{{ $securityFile->personalData->jihadi_name }}</span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>الرقم العسكري</th>
                        <td style="font-family: monospace; font-weight: 800; color: #b91c1c;">{{ $securityFile->personalData->military_number ?? '-' }}</td>
                        <th>الرقم الوطني / الهوية</th>
                        <td style="font-family: monospace;">{{ $securityFile->personalData->national_id_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>المحافظة والمديرية</th>
                        <td>{{ $securityFile->personalData->governorate->name ?? '-' }} / {{ $securityFile->personalData->directorate->name ?? '-' }}</td>
                        <th>القرية / المحلة</th>
                        <td>{{ $securityFile->personalData->village->name ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-archive"></i>
                    <span>بيانات توثيق الملف الأمني والجهة المعدة</span>
                </div>
                <span class="section-header-badge">بيانات التوثيق</span>
            </div>
            <table class="data-table">
                <tbody>
                    <tr>
                        <th>رقم الملف الأمني الرسمي</th>
                        <td style="font-family: monospace; font-weight: 800; color: #b91c1c;">{{ $securityFile->file_number ?? '-' }}</td>
                        <th>الجهة المعدة للملف</th>
                        <td style="font-weight: 800; color: #0f172a;">{{ $securityFile->preparingEntity->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>تاريخ الملف (ميلادي)</th>
                        <td>{{ $securityFile->file_date_gregorian ? date('Y/m/d', strtotime($securityFile->file_date_gregorian)) : '-' }}</td>
                        <th>تاريخ الملف (هجري)</th>
                        <td>{{ $securityFile->file_date_hijri ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>اسم معد الملف</th>
                        <td>{{ $securityFile->file_preparer ?? '-' }}</td>
                        <th>اسم معد التلخيص</th>
                        <td>{{ $securityFile->summary_preparer ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>تاريخ التلخيص (ميلادي)</th>
                        <td>{{ $securityFile->summary_date_gregorian ? date('Y/m/d', strtotime($securityFile->summary_date_gregorian)) : '-' }}</td>
                        <th>تاريخ التلخيص (هجري)</th>
                        <td>{{ $securityFile->summary_date_hijri ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($securityFile->summary)
            <div class="section-card">
                <div class="section-header">
                    <div class="section-header-title">
                        <i class="fas fa-file-alt"></i>
                        <span>ملخص ومحتوى الملف الأمني</span>
                    </div>
                </div>
                <div class="notes-content">
                    {{ $securityFile->summary }}
                </div>
            </div>
        @endif
    @endif

    <x-print-footer />
</div>
@endsection
