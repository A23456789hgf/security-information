@extends('layouts.app')

@section('title', 'إدارة الأدوار والصلاحيات')
@section('page_title', 'إدارة النظام')

@section('content')
<div class="container-fluid mx-auto px-4 py-8" dir="rtl">
    {{-- رأس الصفحة مع الإحصائيات --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-blue-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                <i class="fas fa-shield-alt text-2xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">الأدوار والصلاحيات</h2>
                <p class="text-slate-500 text-sm flex items-center gap-2">
                    <i class="fas fa-database text-slate-400"></i> إجمالي الأدوار:
                    <span class="text-slate-700 font-bold">{{ $roles->count() }}</span>
                </p>
            </div>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('system.roles.create') }}"
                class="px-6 py-3 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 flex items-center gap-2 text-sm">
                <i class="fas fa-plus-circle"></i> إضافة دور جديد
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
                        <th class="px-8 py-6 text-slate-400 font-bold uppercase tracking-widest text-[10px]" style="width: 250px;">اسم الدور</th>
                        <th class="px-6 py-6 text-slate-400 font-bold uppercase tracking-widest text-[10px]">الصلاحيات الممنوحة</th>
                        <th class="px-8 py-6 text-slate-400 font-bold uppercase tracking-widest text-[10px] text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($roles as $role)
                        <tr class="group hover:bg-slate-50/30 transition-colors">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-bold text-sm border border-blue-100">
                                        <i class="fas fa-user-tag"></i>
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-800 block">{{ $role->name }}</span>
                                        @if($role->name === 'super-admin')
                                            <span class="inline-flex items-center px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-100 rounded-md text-[10px] font-bold">
                                                دور نظامي رئيسي
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-wrap gap-1.5 max-w-2xl">
                                    @foreach($role->permissions->take(12) as $permission)
                                        <span class="px-2.5 py-0.5 bg-slate-50 text-slate-600 border border-slate-200 rounded-lg text-[10px] font-bold">
                                            {{ $permission->name }}
                                        </span>
                                    @endforeach
                                    @if($role->permissions->count() > 12)
                                        <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded-lg text-[10px] font-bold">
                                            + {{ $role->permissions->count() - 12 }} أخرى
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex justify-end items-center gap-2">
                                    <a href="{{ route('system.roles.edit', $role) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white shadow-sm transition-all" title="تعديل"><i class="fas fa-pen text-xs"></i></a>
                                    @if($role->name !== 'super-admin')
                                        <form action="{{ route('system.roles.destroy', $role) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الدور؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white shadow-sm transition-all" title="حذف"><i class="fas fa-trash text-xs"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-search text-slate-200 text-3xl"></i>
                                    </div>
                                    <p class="text-slate-400 font-bold">لا توجد أدوار مسجلة</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
