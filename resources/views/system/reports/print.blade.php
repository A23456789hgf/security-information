@extends('layouts.print')

@section('title', isset($isList) ? 'تقرير كشف البلاغات الأمنية والقضايا' : 'وثيقة بلاغ أمني - ' . ($report->report_number ?? 'سجل رقم ' . $report->id))

@section('content')
<div class="print-container">
    <div class="watermark-layer">وثيقة رسمية معتمدة</div>

    <x-print-header 
        :title="isset($isList) ? 'تقرير كشف البلاغات الأمنية والقضايا المقيدة' : 'وثيقة محضر بلاغ وقضية أمنية رسمية'"
        :subCode="isset($isList) ? 'كشف شامل ببيانات وقائع القضايا والبلاغات • معتمد رسمياً' : 'محضر إخطار وقضية رسمية • معتمد رسمياً'"
        :refNumber="isset($isList) ? 'REP-ALL-' . date('Ymd') : 'REP-' . ($report->report_number ?? str_pad($report->id ?? 1, 6, '0', STR_PAD_LEFT))" 
    />

    @if(isset($isList))
        {{-- LIST PRINT MODE --}}
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-file-invoice me-1"></i>
                    <span>قائمة البلاغات والقضايا الأمنية المقيدة (إجمالي: {{ count($reports) }} قضية)</span>
                </div>
                <span class="section-header-badge">كشف رسمي مجمع</span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 35px; text-align: center;">#</th>
                        <th>موضوع / اسم البلاغ</th>
                        <th>اسم الشخص المعني</th>
                        <th>الرقم العسكري / الهوية</th>
                        <th>الجهة المختصة</th>
                        <th>الوحدة / الرتبة</th>
                        <th>تاريخ البلاغ</th>
                        <th>حالة القبض / الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $index => $item)
                        <tr>
                            <td style="text-align: center; font-weight: 700;">{{ $index + 1 }}</td>
                            <td style="font-weight: 800; color: #0f2942;">{{ $item->report_name ?? '-' }}</td>
                            <td style="font-weight: 700; color: #b91c1c;">{{ $item->full_name ?? '-' }}</td>
                            <td style="font-family: monospace; font-weight: 700;">
                                {{ $item->military_number ?: ($item->id_number ?: '-') }}
                            </td>
                            <td>
                                <span style="font-weight: 700; color: #0284c7;">{{ $item->entity->name ?? '-' }}</span>
                            </td>
                            <td>{{ $item->rank ? $item->rank . ' - ' : '' }}{{ $item->unit ?? '-' }}</td>
                            <td style="font-family: monospace;">{{ $item->report_date ? date('Y/m/d', strtotime($item->report_date)) : '-' }}</td>
                            <td>
                                @if($item->arrest_status)
                                    <span class="status-badge status-badge-warning">{{ $item->arrest_status }}</span>
                                @elseif($item->date_of_release)
                                    <span class="status-badge status-badge-success">مخلّى سبيله</span>
                                @else
                                    <span class="status-badge status-badge-info">قيد المتابعة</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 25px; color: #64748b;">لا توجد بلاغات أمنية مسجلة للطباعة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        {{-- SINGLE REPORT PRINT MODE --}}
        
        {{-- Section 1: Basic Info --}}
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-id-card me-1"></i>
                    <span>بيانات البلاغ والشخص المعني</span>
                </div>
                <span class="section-header-badge">بيانات مخصصة رسمية</span>
            </div>
            <table class="data-table">
                <tbody>
                    <tr>
                        <th style="width: 18%;">موضوع / عنوان البلاغ</th>
                        <td style="font-size: 13px; font-weight: 800; color: #0f2942;" colspan="3">
                            {{ $report->report_name ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 18%;">الشخص المعني بالبلاغ</th>
                        <td style="font-size: 12.5px; font-weight: 800; color: #b91c1c;">
                            {{ $report->full_name ?? '-' }}
                        </td>
                        <th style="width: 18%;">الجهة الداخلية المختصة</th>
                        <td style="font-weight: 800; color: #0284c7;">
                            {{ $report->entity->name ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>نوع تصنيف البلاغ</th>
                        <td>{{ $report->report_type ?? '-' }}</td>
                        <th>تاريخ القيد والتقديم</th>
                        <td style="font-family: monospace;">{{ $report->report_date ? date('Y/m/d', strtotime($report->report_date)) : '-' }}</td>
                    </tr>
                    <tr>
                        <th>الرقم العسكري</th>
                        <td style="font-family: monospace; font-weight: 800; color: #0f2942;">{{ $report->military_number ?? '-' }}</td>
                        <th>رقم الهوية الوطنية</th>
                        <td style="font-family: monospace; font-weight: 800;">{{ $report->id_number ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Section 2: Unit & Rank --}}
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-sitemap me-1"></i>
                    <span>بيانات الوحدة والرتبة والتعيين</span>
                </div>
                <span class="section-header-badge">التفاصيل العسكرية/الوظيفية</span>
            </div>
            <table class="data-table">
                <tbody>
                    <tr>
                        <th style="width: 18%;">الوحدة العسكرية/الوظيفية</th>
                        <td>{{ $report->unit ?? '-' }}</td>
                        <th style="width: 18%;">الوحدة الفرعية</th>
                        <td>{{ $report->sub_unit ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>الوحدة التفصيلية</th>
                        <td>{{ $report->detailed_unit ?? '-' }}</td>
                        <th>الرتبة العسكرية</th>
                        <td style="font-weight: 700; color: #b91c1c;">{{ $report->rank ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>التعيين / المسمى الوظيفي</th>
                        <td>{{ $report->designation ?? $report->job_title ?? '-' }}</td>
                        <th>المنطقة / الفرع</th>
                        <td>{{ $report->area ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Section 3: Procedures & Legal Actions --}}
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-gavel me-1"></i>
                    <span>الإجراءات والتهمة والحالة القانونية</span>
                </div>
                <span class="section-header-badge">المتابعة والضبط</span>
            </div>
            <table class="data-table">
                <tbody>
                    <tr>
                        <th style="width: 18%;">التهمة المنسوبة</th>
                        <td colspan="3" style="font-weight: 700; color: #0f2942;">{{ $report->charge ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>حالة القبض / التوقيف</th>
                        <td>
                            @if($report->arrest_status)
                                <span class="status-badge status-badge-warning">{{ $report->arrest_status }}</span>
                            @else
                                <span class="status-badge status-badge-info">قيد المتابعة</span>
                            @endif
                        </td>
                        <th style="width: 18%;">تاريخ القبض / الضبط</th>
                        <td style="font-family: monospace;">{{ $report->arrest_date ? date('Y/m/d', strtotime($report->arrest_date)) : '-' }}</td>
                    </tr>
                    <tr>
                        <th>الإجراءات المتخذة</th>
                        <td colspan="3" style="white-space: pre-wrap;">{{ $report->procedures ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Section 4: Notes & Additional Details --}}
        @if(!empty($report->notes) || !empty($report->additional_notes))
            <div class="notes-box">
                <div class="section-header">
                    <div class="section-header-title">
                        <i class="fas fa-comment-alt me-1"></i>
                        <span>الملاحظات والتفاصيل الاستخباراتية الإضافية</span>
                    </div>
                </div>
                <div class="notes-content">
                    @if(!empty($report->notes))
                        <p style="margin-bottom: 6px;"><strong>ملاحظات البلاغ:</strong> {{ $report->notes }}</p>
                    @endif
                    @if(!empty($report->additional_notes))
                        <p style="margin-bottom: 0;"><strong>ملاحظات إضافية:</strong> {{ $report->additional_notes }}</p>
                    @endif
                </div>
            </div>
        @endif

    @endif

    <x-print-footer />
</div>
@endsection
