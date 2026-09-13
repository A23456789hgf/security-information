@extends('layouts.print')

@section('title', isset($isList) ? 'تقرير بيانات الأقارب' : 'تقرير قسيمة القريب - ' . ($relative->full_name ?? 'سجل'))

@section('content')
    <x-print-header 
        :title="isset($isList) ? 'تقرير كشف الأهل والأقارب' : 'بطاقة بيانات القريب الرسمية'"
        :refNumber="isset($isList) ? 'REL-ALL-' . date('Ymd') : 'REL-' . ($relative->id ?? '00')" 
    />

    <div class="no-print mb-6 flex justify-between items-center bg-slate-100 p-4 rounded-xl">
        <span class="text-sm font-bold text-slate-700">معاينة تقرير الأقارب للطباعة</span>
        <button onclick="window.print()" class="px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl shadow hover:bg-indigo-700 transition flex items-center gap-2">
            <i class="fas fa-print"></i> تنفيذ الطباعة الآن
        </button>
    </div>

    @if(isset($isList))
        <table class="w-full text-right border-collapse">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>الاسم الفرد المرتبط</th>
                    <th>اسم القريب</th>
                    <th>صلة القرابة</th>
                    <th>مكان الإقامة / الدولة</th>
                    <th>المهنة / العمل</th>
                    <th>الانتماء العقائدي / السياسي</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $item->personalData->name ?? 'غير محدد' }}</strong></td>
                        <td>{{ $item->full_name ?? $item->name ?? '-' }}</td>
                        <td>{{ $item->kinship->name ?? '-' }}</td>
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
                        <td colspan="7" class="text-center py-4">لا توجد بيانات أقارب للطباعة</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @else
        <div class="report-section">
            <div class="section-title">أولاً: بيانات القريب</div>
            <table>
                <tr>
                    <th>الاسم الكامل للقريب</th>
                    <td><strong>{{ $relative->full_name }}</strong></td>
                    <th>صلة القرابة</th>
                    <td>{{ $relative->kinship->name ?? 'غير محدد' }}</td>
                </tr>
                <tr>
                    <th>الفرد الأساسي المرتبط به</th>
                    <td>{{ $relative->personalData->name ?? 'غير محدد' }}</td>
                    <th>مكان الإقامة</th>
                    <td>{{ $relative->residence_place ?? '-' }}</td>
                </tr>
                <tr>
                    <th>المهنة / العمل الحالي</th>
                    <td>{{ $relative->job ?? '-' }}</td>
                    <th>رقم الهاتف</th>
                    <td>{{ $relative->phone ?? '-' }}</td>
                </tr>
                <tr>
                    <th>الانتماء العقائدي</th>
                    <td>{{ $relative->ideologicalAffiliation->name ?? '-' }}</td>
                    <th>الانتماء السياسي</th>
                    <td>{{ $relative->politicalAffiliation->name ?? '-' }}</td>
                </tr>
            </table>
        </div>
    @endif
@endsection
