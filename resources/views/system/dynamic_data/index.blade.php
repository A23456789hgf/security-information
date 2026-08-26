@extends('layouts.app')

@section('title', 'بيانات الصفحة: ' . $page->name)
@section('page_title', 'الصفحات الديناميكية')

@section('content')
<div class="container-fluid mx-auto px-4 py-8" dir="rtl">
    {{-- رأس الصفحة مع الإحصائيات --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-blue-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                <i class="fas {{ $page->icon ?? 'fa-file-alt' }} text-2xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">{{ $page->name }}</h2>
                <p class="text-slate-500 text-sm flex items-center gap-2">
                    <i class="fas fa-database text-slate-400"></i> إجمالي السجلات:
                    <span class="text-slate-700 font-bold">{{ $records->total() }}</span>
                </p>
            </div>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('system.dynamic-data.import', $page->slug) }}"
                class="px-5 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100 flex items-center gap-2 text-sm">
                <i class="fas fa-file-import"></i> استيراد
            </a>
            <a href="{{ route('system.dynamic-data.export', $page->slug) }}"
                class="px-5 py-3 bg-emerald-600 text-white rounded-2xl font-bold hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-100 flex items-center gap-2 text-sm">
                <i class="fas fa-file-excel"></i> تصدير (Excel)
            </a>
            <a href="{{ route('system.dynamic-data.create', $page->slug) }}"
                class="px-6 py-3 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 flex items-center gap-2 text-sm">
                <i class="fas fa-plus-circle"></i> إضافة سجل جديد
            </a>
        </div>
    </div>

    {{-- رسائل النظام --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl mb-8 flex items-center justify-between shadow-sm" role="alert">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-xl"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
            <button type="button" class="text-emerald-400 hover:text-emerald-600 transition-colors" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- جدول البيانات --}}
    <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        @foreach($page->fields as $field)
                            <th class="{{ $loop->first ? 'px-8' : 'px-6' }} py-6 text-slate-400 font-bold uppercase tracking-widest text-[10px]">{{ $field->label }}</th>
                        @endforeach
                        <th class="px-6 py-6 text-slate-400 font-bold uppercase tracking-widest text-[10px]">بواسطة</th>
                        <th class="px-6 py-6 text-slate-400 font-bold uppercase tracking-widest text-[10px] text-center">تاريخ الإضافة</th>
                        <th class="px-8 py-6 text-slate-400 font-bold uppercase tracking-widest text-[10px] text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($records as $record)
                        @php $values = $record->values->pluck('value', 'field_id'); @endphp
                        <tr class="group hover:bg-slate-50/30 transition-colors">
                            @foreach($page->fields as $field)
                                <td class="{{ $loop->first ? 'px-8 font-bold text-slate-800' : 'px-6 text-slate-700' }} py-5">
                                    @if($field->type === 'file')
                                        @if($values[$field->id] ?? null)
                                            <a href="{{ asset('storage/' . $values[$field->id]) }}" target="_blank" class="inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-800 font-bold text-xs bg-blue-50 px-3 py-1 rounded-xl border border-blue-100">
                                                <i class="fas fa-file-download"></i> تحميل
                                            </a>
                                        @else
                                            <span class="text-slate-300">-</span>
                                        @endif
                                    @else
                                        {{ $values[$field->id] ?? '-' }}
                                    @endif
                                </td>
                            @endforeach
                            <td class="px-6 py-5 text-xs text-slate-500 font-medium">
                                {{ $record->creator->name ?? 'النظام' }}
                            </td>
                            <td class="px-6 py-5 text-center font-mono text-xs text-slate-500">
                                <span class="px-3 py-1 bg-slate-100 rounded-lg text-[10px] font-bold">
                                    {{ $record->created_at->format('Y-m-d') }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex justify-end items-center gap-2">
                                    <a href="{{ route('system.dynamic-data.edit', [$page->slug, $record]) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white shadow-sm transition-all" title="تعديل"><i class="fas fa-pen text-xs"></i></a>
                                    <form action="{{ route('system.dynamic-data.destroy', [$page->slug, $record]) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white shadow-sm transition-all" title="حذف"><i class="fas fa-trash text-xs"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($page->fields) + 3 }}" class="py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-search text-slate-200 text-3xl"></i>
                                    </div>
                                    <p class="text-slate-400 font-bold">لا توجد بيانات مسجلة في هذه الصفحة</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- الترقيم --}}
    <div class="mt-10 flex justify-center">
        {{ $records->withQueryString()->links() }}
    </div>
</div>
@endsection
