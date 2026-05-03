@extends('layouts.app')

@section('title', 'بيانات الصفحة: ' . $page->name)

@section('content')
<div class="container-fluid py-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-blue-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                <i class="fas fa-file-alt text-2xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">{{ $page->name }}</h2>
                <p class="text-slate-500 text-sm flex items-center gap-2">
                    إدارة بيانات الصفحة المخصصة
                    <span class="inline-flex items-center justify-center bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs font-bold">
                        {{ $records->total() }} سجل
                    </span>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('system.dynamic-data.import', $page->slug) }}"
                class="flex items-center gap-2 bg-emerald-100 text-emerald-700 hover:bg-emerald-600 hover:text-white px-5 py-3 rounded-2xl font-bold transition-all shadow-sm">
                <i class="fas fa-file-import"></i>
                <span>استيراد</span>
            </a>
            <a href="{{ route('system.dynamic-data.export', $page->slug) }}"
                class="flex items-center gap-2 bg-indigo-100 text-indigo-700 hover:bg-indigo-600 hover:text-white px-5 py-3 rounded-2xl font-bold transition-all shadow-sm">
                <i class="fas fa-file-export"></i>
                <span>تصدير</span>
            </a>
            <a href="{{ route('system.dynamic-data.create', $page->slug) }}"
                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold transition-all shadow-lg shadow-blue-100">
                <i class="fas fa-plus"></i>
                <span>إضافة سجل جديد</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Main Content Card -->
    <div class="bg-white rounded-3xl soft-shadow border border-slate-100" style="overflow: visible !important;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="bg-slate-50/80 border-b border-slate-100">
                    <tr>
                        @foreach($page->fields as $field)
                            <th class="{{ $loop->first ? 'ps-6' : '' }} py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]">{{ $field->label }}</th>
                        @endforeach
                        <th class="py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]">بواسطة</th>
                        <th class="py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]">تاريخ الإضافة</th>
                        <th class="text-center pe-6 py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]" style="width: 150px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($records as $record)
                    @php $values = $record->values->pluck('value', 'field_id'); @endphp
                    <tr class="group hover:bg-slate-50/50 transition-colors">
                        @foreach($page->fields as $field)
                            <td class="{{ $loop->first ? 'ps-6 fw-bold text-slate-700' : 'text-slate-600' }} py-4">
                                @if($field->type === 'file')
                                    @if($values[$field->id] ?? null)
                                        <a href="{{ asset('storage/' . $values[$field->id]) }}" target="_blank" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 transition-colors">
                                            <i class="fas fa-file-download"></i>
                                            <span>تحميل</span>
                                        </a>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                @else
                                    {{ $values[$field->id] ?? '-' }}
                                @endif
                            </td>
                        @endforeach
                        <td class="py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-slate-50 rounded-full flex items-center justify-center text-slate-400 border border-slate-100">
                                    <i class="fas fa-user text-xs"></i>
                                </div>
                                <span class="text-slate-600 font-medium">{{ $record->user->name ?? 'مجهول' }}</span>
                            </div>
                        </td>
                        <td class="py-4 text-slate-500 font-mono text-xs">{{ $record->created_at->format('Y-m-d') }}</td>
                        <td class="text-center pe-6 py-4">
                            <div class="flex justify-end items-center gap-2">
                                <a href="{{ route('system.dynamic-data.edit', [$page->slug, $record]) }}" class="w-9 h-9 flex items-center justify-center rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-600 hover:text-white shadow-sm transition-all" title="تعديل">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                        <path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                        <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                    </svg>
                                </a>
                                <form action="{{ route('system.dynamic-data.destroy', [$page->slug, $record]) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-lg bg-rose-100 text-rose-700 hover:bg-rose-600 hover:text-white shadow-sm transition-all" title="حذف">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                            <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75V4H5a2 2 0 00-2 2v.25C3 6.664 3.336 7 3.75 7h12.5a.75.75 0 000-1.5h-.25V4A2.75 2.75 0 0013.25 1h-4.5zM6 5.5v-1.75C6 3.224 6.448 2.75 7 2.75h6c.552 0 1 .474 1 1V5.5H6z" clip-rule="evenodd" />
                                            <path d="M4 8.25V15.5A2.75 2.75 0 006.75 18.25h6.5A2.75 2.75 0 0016 15.5V8.25H4zM7.5 10a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 017.5 10zm5.25.75a.75.75 0 00-1.5 0v4.5a.75.75 0 001.5 0v-4.5z" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8 flex justify-center">
        {{ $records->links() }}
    </div>
</div>
@endsection
