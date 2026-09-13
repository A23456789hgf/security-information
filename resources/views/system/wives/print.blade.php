@extends('layouts.print')

@section('title', isset($isList) ? 'تقرير بيانات الزوجات' : 'بطاقة الزوجة - ' . ($wife->name ?? 'سجل'))

@section('content')
    <x-print-header 
        :title="isset($isList) ? 'تقرير بيانات زوجات الأفراد' : 'بطاقة التقرير الرسمي لبيانات الزوجة'"
        :refNumber="isset($isList) ? 'WIFE-ALL-' . date('Ymd') : 'WIFE-' . ($wife->id ?? '00')" 
    />

    <div class="no-print mb-6 flex justify-between items-center bg-slate-100 p-4 rounded-xl">
        <span class="text-sm font-bold text-slate-700">معاينة تقرير الزوجات للطباعة</span>
        <button onclick="window.print()" class="px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl shadow hover:bg-indigo-700 transition flex items-center gap-2">
            <i class="fas fa-print"></i> تنفيذ الطباعة الآن
        </button>
    </div>

    @if(isset($isList))
        <table class="w-full text-right border-collapse">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>اسم الزوج (الفرد)</th>
                    <th>اسم الزوجة الكامل</th>
                    <th>الحالة الحالية</th>
                    <th>رقم الهوية الوطنية</th>
                    <th>رقم الهاتف</th>
                    <th>المحافظة والمديرية</th>
                </tr>
            </thead>
            <tbody>
                @forelse($wives as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $item->personalData->name ?? 'غير محدد' }}</strong></td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->status ?? 'مستمرة' }}</td>
                        <td>{{ $item->wife_id_number ?? '-' }}</td>
                        <td>{{ $item->wife_phone ?? '-' }}</td>
                        <td>{{ $item->governorate->name ?? '-' }} / {{ $item->directorate->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">لا توجد بيانات زوجات للطباعة</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @else
        <div class="report-section">
            <div class="section-title">أولاً: بيانات الزوجة الأساسية</div>
            <table>
                <tr>
                    <th>اسم الزوجة الكامل</th>
                    <td><strong>{{ $wife->name }}</strong></td>
                    <th>الحالة الإجتماعية / الزوجية</th>
                    <td>{{ $wife->status ?? 'مستمرة' }}</td>
                </tr>
                <tr>
                    <th>اسم الزوج المرتبط بها</th>
                    <td><strong>{{ $wife->personalData->name ?? 'غير محدد' }}</strong></td>
                    <th>الرقم العسكري للزوج</th>
                    <td>{{ $wife->personalData->military_number ?? '-' }}</td>
                </tr>
                <tr>
                    <th>رقم الهوية الوطنية للزوجة</th>
                    <td>{{ $wife->wife_id_number ?? 'غير مسجل' }}</td>
                    <th>تاريخ الميلاد</th>
                    <td>{{ $wife->wife_birth_date ? date('Y/m/d', strtotime($wife->wife_birth_date)) : '-' }}</td>
                </tr>
                <tr>
                    <th>رقم الهاتف</th>
                    <td>{{ $wife->wife_phone ?? '-' }}</td>
                    <th>العنوان / مكان الإقامة</th>
                    <td>{{ $wife->wife_address ?? '-' }}</td>
                </tr>
                <tr>
                    <th>المحافظة والمديرية</th>
                    <td>{{ $wife->governorate->name ?? '-' }} / {{ $wife->directorate->name ?? '-' }}</td>
                    <th>العزلة والقرية</th>
                    <td>{{ $wife->subArea->name ?? '-' }} / {{ $wife->village->name ?? '-' }}</td>
                </tr>
            </table>
        </div>
    @endif
@endsection
