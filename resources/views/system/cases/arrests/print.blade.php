@extends('layouts.print')

@section('title', isset($isList) ? 'تقرير سجلات الضبط والتوقيف' : 'محضر ضبط وقضية - ' . ($arrest->personalData->name ?? 'سجل'))

@section('content')
    <x-print-header 
        :title="isset($isList) ? 'تقرير سجلات الضبط والتوقيف القائمة' : 'محضر ومحضر ضبط وقضية موقوف'"
        :refNumber="isset($isList) ? 'ARR-ALL-' . date('Ymd') : 'ARR-' . ($arrest->id ?? '00')" 
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
                    <th>اسم الفرد الموقوف</th>
                    <th>نوع القضية / التهمة</th>
                    <th>جهة الضبط</th>
                    <th>تاريخ الضبط والتوقيف</th>
                    <th>مكان الضبط</th>
                    <th>تاريخ الإفراج / الحالة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($arrests as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $item->personalData->name ?? 'غير محدد' }}</strong></td>
                        <td>{{ $item->caseType->name ?? $item->charge ?? '-' }}</td>
                        <td>{{ $item->arrestingAuthority->name ?? '-' }}</td>
                        <td>{{ $item->arrest_date ? date('Y/m/d', strtotime($item->arrest_date)) : '-' }}</td>
                        <td>{{ $item->arrest_location ?? '-' }}</td>
                        <td>{{ $item->release_date ? date('Y/m/d', strtotime($item->release_date)) : 'قيد التوقيف' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">لا توجد سجلات ضبط للطباعة</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @else
        <div class="report-section">
            <div class="section-title">أولاً: بيانات الشخص الموقوف</div>
            <table>
                <tr>
                    <th>اسم الموقوف الكامل</th>
                    <td><strong>{{ $arrest->personalData->name ?? 'غير محدد' }}</strong></td>
                    <th>الاسم الجهادي</th>
                    <td>{{ $arrest->personalData->jihadi_name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>الرقم العسكري</th>
                    <td>{{ $arrest->personalData->military_number ?? '-' }}</td>
                    <th>المحافظة والمديرية</th>
                    <td>{{ $arrest->governorate->name ?? '-' }} / {{ $arrest->directorate->name ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="report-section">
            <div class="section-title">ثانياً: تفاصيل محضر الضبط والقضية</div>
            <table>
                <tr>
                    <th>جهة الضبط المسؤولة</th>
                    <td><strong>{{ $arrest->arrestingAuthority->name ?? 'غير محدد' }}</strong></td>
                    <th>نوع القضية والتصنيف</th>
                    <td><strong>{{ $arrest->caseType->name ?? 'غير محدد' }}</strong></td>
                </tr>
                <tr>
                    <th>تاريخ الضبط</th>
                    <td>{{ $arrest->arrest_date ? date('Y/m/d', strtotime($arrest->arrest_date)) : '-' }}</td>
                    <th>مكان موقع الضبط</th>
                    <td>{{ $arrest->arrest_location ?? '-' }}</td>
                </tr>
                <tr>
                    <th>موقوف في سجن / مكان الاحتجاز</th>
                    <td>{{ $arrest->detention_place ?? '-' }}</td>
                    <th>تاريخ الإفراج (إن وجد)</th>
                    <td>{{ $arrest->release_date ? date('Y/m/d', strtotime($arrest->release_date)) : 'لا يزال قيد التوقيف' }}</td>
                </tr>
                <tr>
                    <th>التهمة والملابسات</th>
                    <td colspan="3">{{ $arrest->charge ?? 'لا يوجد بيان تفصيلي' }}</td>
                </tr>
                <tr>
                    <th>ملاحظات الإجراءات القضائية</th>
                    <td colspan="3">{{ $arrest->notes ?? 'لا توجد ملاحظات إضافية' }}</td>
                </tr>
            </table>
        </div>
    @endif
@endsection
