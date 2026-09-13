@extends('layouts.print')

@section('title', isset($isList) ? 'تقرير كشف الملفات الأمنية' : 'وثيقة الملف الأمني - ' . ($securityFile->personalData->name ?? 'سجل'))

@section('content')
    <x-print-header 
        :title="isset($isList) ? 'تقرير قائمة وسجلات الملفات الأمنية' : 'وثيقة واستمارة الملف الأمني الرسمي'"
        :refNumber="isset($isList) ? 'SECFILE-ALL-' . date('Ymd') : 'SECFILE-' . ($securityFile->file_number ?? $securityFile->id ?? '00')" 
    />

    <div class="no-print mb-6 flex justify-between items-center bg-slate-100 p-4 rounded-xl">
        <span class="text-sm font-bold text-slate-700">معاينة الملف للطباعة</span>
        <button onclick="window.print()" class="px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl shadow hover:bg-indigo-700 transition flex items-center gap-2">
            <i class="fas fa-print"></i> تنفيذ الطباعة الآن
        </button>
    </div>

    @if(isset($isList))
        <table class="w-full text-right border-collapse">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>رقم الملف</th>
                    <th>اسم الفرد المرتبط</th>
                    <th>الجهة المعدة للملف</th>
                    <th>تاريخ الملف (ميلادي/هجري)</th>
                    <th>معد الملف</th>
                    <th>معد التلخيص</th>
                </tr>
            </thead>
            <tbody>
                @forelse($securityFiles as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $item->file_number ?? '-' }}</strong></td>
                        <td>{{ $item->personalData->name ?? 'غير محدد' }}</td>
                        <td>{{ $item->preparingEntity->name ?? '-' }}</td>
                        <td>{{ $item->file_date_gregorian ? date('Y/m/d', strtotime($item->file_date_gregorian)) : '-' }} ({{ $item->file_date_hijri ?? '-' }})</td>
                        <td>{{ $item->file_preparer ?? '-' }}</td>
                        <td>{{ $item->summary_preparer ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">لا توجد ملفات أمنية للطباعة</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @else
        <div class="report-section">
            <div class="section-title">أولاً: البيانات الأساسية والتعريفية بالفرد</div>
            <table>
                <tr>
                    <th>اسم الفرد الكامل</th>
                    <td><strong>{{ $securityFile->personalData->name ?? 'غير محدد' }}</strong></td>
                    <th>الاسم الجهادي</th>
                    <td>{{ $securityFile->personalData->jihadi_name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>الرقم العسكري</th>
                    <td>{{ $securityFile->personalData->military_number ?? '-' }}</td>
                    <th>الرقم القومي / الهوية</th>
                    <td>{{ $securityFile->personalData->national_id_number ?? '-' }}</td>
                </tr>
                <tr>
                    <th>المحافظة والمديرية</th>
                    <td>{{ $securityFile->personalData->governorate->name ?? '-' }} / {{ $securityFile->personalData->directorate->name ?? '-' }}</td>
                    <th>القرية / المحلة</th>
                    <td>{{ $securityFile->personalData->village->name ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="report-section">
            <div class="section-title">ثانياً: بيانات توثيق الملف الأمني والجهة المعدة</div>
            <table>
                <tr>
                    <th>رقم الملف الأمني الرسمى</th>
                    <td><strong>{{ $securityFile->file_number ?? '-' }}</strong></td>
                    <th>الجهة المعدة للملف</th>
                    <td><strong>{{ $securityFile->preparingEntity->name ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <th>تاريخ الملف الميلادي</th>
                    <td>{{ $securityFile->file_date_gregorian ? date('Y/m/d', strtotime($securityFile->file_date_gregorian)) : '-' }}</td>
                    <th>تاريخ الملف الهجري</th>
                    <td>{{ $securityFile->file_date_hijri ?? '-' }}</td>
                </tr>
                <tr>
                    <th>اسم المعد للملف</th>
                    <td>{{ $securityFile->file_preparer ?? '-' }}</td>
                    <th>اسم معد التلخيص</th>
                    <td>{{ $securityFile->summary_preparer ?? '-' }}</td>
                </tr>
                <tr>
                    <th>تاريخ التلخيص الميلادي</th>
                    <td>{{ $securityFile->summary_date_gregorian ? date('Y/m/d', strtotime($securityFile->summary_date_gregorian)) : '-' }}</td>
                    <th>تاريخ التلخيص الهجري</th>
                    <td>{{ $securityFile->summary_date_hijri ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="report-section">
            <div class="section-title">ثالثاً: أبرز البيانات الملخصة من الملف</div>
            <table>
                <tr>
                    <td colspan="4" style="line-height: 1.8; text-align: justify;">
                        {{ $securityFile->highlight_summary ?? 'لا يوجد نص ملخص مدون' }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="report-section">
            <div class="section-title">رابعاً: بيانات وملاحظات الوضع الجهادي</div>
            <table>
                <tr>
                    <td colspan="4" style="line-height: 1.8; text-align: justify;">
                        {{ $securityFile->jihadist_status ?? 'لا توجد بيانات مدونة' }}
                    </td>
                </tr>
            </table>
        </div>
    @endif
@endsection
