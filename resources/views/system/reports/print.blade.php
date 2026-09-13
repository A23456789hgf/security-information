@extends('layouts.print')

@section('title', isset($isList) ? 'تقرير كشف البلاغات الأمنية' : 'وثيقة بلاغ أمني - ' . ($report->report_number ?? 'سجل'))

@section('content')
    <x-print-header 
        :title="isset($isList) ? 'تقرير قائمة البلاغات والسجلات الأمنية' : 'وثيقة بلاغ أمني رسمي'"
        :refNumber="isset($isList) ? 'REP-ALL-' . date('Ymd') : 'REP-' . ($report->report_number ?? $report->id ?? '00')" 
    />

    <div class="no-print mb-6 flex justify-between items-center bg-slate-100 p-4 rounded-xl">
        <span class="text-sm font-bold text-slate-700">معاينة البلاغ للطباعة</span>
        <button onclick="window.print()" class="px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl shadow hover:bg-indigo-700 transition flex items-center gap-2">
            <i class="fas fa-print"></i> تنفيذ الطباعة الآن
        </button>
    </div>

    @if(isset($isList))
        <table class="w-full text-right border-collapse">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>رقم البلاغ</th>
                    <th>موضوع / اسم البلاغ</th>
                    <th>اسم المقدم / المبلغ</th>
                    <th>تاريخ البلاغ</th>
                    <th>جهة الضبط / الإيداع</th>
                    <th>تاريخ الإيداع / الإفراج</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $item->report_number ?? '-' }}</strong></td>
                        <td>{{ $item->report_name ?? $item->full_name ?? '-' }}</td>
                        <td>{{ $item->informant_name ?? $item->full_name ?? '-' }}</td>
                        <td>{{ $item->report_date ? date('Y/m/d', strtotime($item->report_date)) : '-' }}</td>
                        <td>{{ $item->arresting_authority ?? '-' }}</td>
                        <td>{{ $item->deposit_date ? date('Y/m/d', strtotime($item->deposit_date)) : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">لا توجد بلاغات أمنية للطباعة</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @else
        <div class="report-section">
            <div class="section-title">أولاً: بيانات البلاغ الأساسية</div>
            <table>
                <tr>
                    <th>رقم البلاغ الرسمي</th>
                    <td><strong>{{ $report->report_number ?? '-' }}</strong></td>
                    <th>تاريخ البلاغ</th>
                    <td>{{ $report->report_date ? date('Y/m/d', strtotime($report->report_date)) : '-' }}</td>
                </tr>
                <tr>
                    <th>اسم الشخص / موضوع البلاغ</th>
                    <td><strong>{{ $report->full_name ?? $report->report_name ?? '-' }}</strong></td>
                    <th>الجهة المبلغة / مقدم البلاغ</th>
                    <td>{{ $report->informant_name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>جهة الضبط المسؤولة</th>
                    <td>{{ $report->arresting_authority ?? '-' }}</td>
                    <th>مكان الإيداع / الاحتجاز</th>
                    <td>{{ $report->detention_place ?? '-' }}</td>
                </tr>
                <tr>
                    <th>تاريخ الإيداع</th>
                    <td>{{ $report->deposit_date ? date('Y/m/d', strtotime($report->deposit_date)) : '-' }}</td>
                    <th>تاريخ الإفراج</th>
                    <td>{{ $report->date_of_release ? date('Y/m/d', strtotime($report->date_of_release)) : 'لا يزال مقيداً' }}</td>
                </tr>
            </table>
        </div>

        <div class="report-section">
            <div class="section-title">ثانياً: مضمون ونص البلاغ والملاحظات</div>
            <table>
                <tr>
                    <th>مضمون البلاغ تفصيلياً</th>
                    <td colspan="3">{{ $report->report_content ?? $report->notes ?? 'لا يوجد نص مدون' }}</td>
                </tr>
                <tr>
                    <th>الإجراءات المتخذة</th>
                    <td colspan="3">{{ $report->action_taken ?? 'قيد المتابعة والتحقيق' }}</td>
                </tr>
            </table>
        </div>
    @endif
@endsection
