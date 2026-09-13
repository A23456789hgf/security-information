@extends('layouts.print')

@section('title', isset($isList) ? 'تقرير سجلات المشتبه بهم' : 'ملف الاشتباه - ' . ($suspicious->personalData->name ?? 'سجل'))

@section('content')
    <x-print-header 
        :title="isset($isList) ? 'تقرير قائمة المشتبه بهم والمتحرى عنهم' : 'بطاقة ملف الأشخاص المشتبه بهم'"
        :refNumber="isset($isList) ? 'SUSP-ALL-' . date('Ymd') : 'SUSP-' . ($suspicious->id ?? '00')" 
    />

    <div class="no-print mb-6 flex justify-between items-center bg-slate-100 p-4 rounded-xl">
        <span class="text-sm font-bold text-slate-700">معاينة التقرير للطباعة</span>
        <button onclick="window.print()" class="px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl shadow hover:bg-indigo-700 transition flex items-center gap-2">
            <i class="fas fa-print"></i> تنفيذ الطباعة الآن
        </button>
    </div>

    @if(isset($isList))
        <table class="w-full text-right border-collapse">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>اسم المشتبه به</th>
                    <th>الاسم الجهادي</th>
                    <th>نوع الاشتباه / القضية</th>
                    <th>تاريخ قيد الاشتباه</th>
                    <th>المحافظة والمديرية</th>
                    <th>مستوى الخطورة / الملاحظات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suspects as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $item->personalData->name ?? 'غير محدد' }}</strong></td>
                        <td>{{ $item->personalData->jihadi_name ?? '-' }}</td>
                        <td>{{ $item->caseType->name ?? $item->suspicion_reason ?? '-' }}</td>
                        <td>{{ $item->suspicion_date ? date('Y/m/d', strtotime($item->suspicion_date)) : '-' }}</td>
                        <td>{{ $item->governorate->name ?? '-' }} / {{ $item->directorate->name ?? '-' }}</td>
                        <td>{{ $item->notes ?? 'تحت المتابعة والرصد' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">لا توجد سجلات اشتباه للطباعة</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @else
        <div class="report-section">
            <div class="section-title">أولاً: بيانات الشخص المشتبه به</div>
            <table>
                <tr>
                    <th>اسم المشتبه به الكامل</th>
                    <td><strong>{{ $suspicious->personalData->name ?? 'غير محدد' }}</strong></td>
                    <th>الاسم الجهادي</th>
                    <td>{{ $suspicious->personalData->jihadi_name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>الرقم العسكري</th>
                    <td>{{ $suspicious->personalData->military_number ?? '-' }}</td>
                    <th>المحافظة والمديرية</th>
                    <td>{{ $suspicious->governorate->name ?? '-' }} / {{ $suspicious->directorate->name ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="report-section">
            <div class="section-title">ثانياً: تفاصيل ودواعي الاشتباه</div>
            <table>
                <tr>
                    <th>تاريخ تسجيل الاشتباه</th>
                    <td>{{ $suspicious->suspicion_date ? date('Y/m/d', strtotime($suspicious->suspicion_date)) : '-' }}</td>
                    <th>نوع القضية / التصنيف</th>
                    <td><strong>{{ $suspicious->caseType->name ?? 'غير محدد' }}</strong></td>
                </tr>
                <tr>
                    <th>سبب دواعي الاشتباه</th>
                    <td colspan="3">{{ $suspicious->suspicion_reason ?? 'غير مدون' }}</td>
                </tr>
                <tr>
                    <th>ملاحظات وتحريات المتابعة</th>
                    <td colspan="3">{{ $suspicious->notes ?? 'قيد الرصد والتحري' }}</td>
                </tr>
            </table>
        </div>
    @endif
@endsection
