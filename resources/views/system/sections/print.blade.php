@extends('layouts.print')

@section('title', 'تقرير كشف الأقسام الإدارية والصفحات')

@section('content')
    <x-print-header 
        title="تقرير قائمة الأقسام الإدارية والصفحات المرتبطة"
        :refNumber="'SEC-ALL-' . date('Ymd')" 
    />

    <div class="no-print mb-6 flex justify-between items-center bg-slate-100 p-4 rounded-xl">
        <span class="text-sm font-bold text-slate-700">معاينة قائمة الأقسام للطباعة</span>
        <button onclick="window.print()" class="px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl shadow hover:bg-indigo-700 transition flex items-center gap-2">
            <i class="fas fa-print"></i> تنفيذ الطباعة الآن
        </button>
    </div>

    <table class="w-full text-right border-collapse">
        <thead>
            <tr>
                <th style="width: 60px;">الترتيب</th>
                <th>اسم القسم الإداري</th>
                <th>المعرف البرمجي (Slug)</th>
                <th>عدد الصفحات التابعة</th>
                <th>حالة القسم</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sections as $section)
                <tr>
                    <td class="text-center font-bold">{{ $section->order_column }}</td>
                    <td><strong>{{ $section->name }}</strong></td>
                    <td class="font-mono text-xs">{{ $section->slug }}</td>
                    <td class="text-center">{{ $section->pages_count }} صفحة</td>
                    <td class="text-center">
                        {{ $section->is_active ? 'نشط' : 'متوقف' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4">لا توجد أقسام إدارية مسجلة</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
