@extends('layouts.app')

@section('title', 'إدارة الأدوار والصلاحيات')
@section('page_title', 'إدارة النظام')

@section('content')
<div class="space-y-6" dir="rtl">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <div class="p-2.5 bg-brand-500/10 text-brand-600 dark:text-brand-400 rounded-xl">
                    <i class="fas fa-shield-alt text-xl"></i>
                </div>
                الأدوار والصلاحيات
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                إدارة أدوار النظام وتخصيص مصفوفات الصلاحيات ونطاقات الوصول (إجمالي الأدوار: <span class="font-bold text-slate-700 dark:text-slate-200">{{ $roles->count() }}</span>)
            </p>
        </div>
        <div>
            <a href="{{ route('system.roles.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all">
                <i class="fas fa-plus"></i>
                إضافة دور جديد
            </a>
        </div>
    </div>

    {{-- System Alerts --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-emerald-600 dark:text-emerald-400 flex items-center justify-between" role="alert">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            <button type="button" class="text-emerald-500 hover:text-emerald-700" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- Data table --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-bold text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 w-64">اسم الدور</th>
                        <th class="px-6 py-4">الصلاحيات الممنوحة</th>
                        <th class="px-6 py-4 text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                    @forelse($roles as $role)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/25 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-brand-500/10 border border-brand-500/20 text-brand-600 dark:text-brand-400 flex items-center justify-center font-bold text-sm">
                                        <i class="fas fa-user-tag"></i>
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-900 dark:text-white block">{{ $role->name }}</span>
                                        @if($role->name === 'super-admin')
                                            <span class="inline-flex items-center px-2 py-0.5 bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 rounded-md text-[10px] font-bold">
                                                دور نظامي رئيسي
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1.5 max-w-2xl">
                                    @foreach($role->permissions->take(12) as $permission)
                                        <span class="inline-flex items-center px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 rounded-2xl text-xs font-semibold">
                                            {{ $permission->name }}
                                        </span>
                                    @endforeach
                                    @if($role->permissions->count() > 12)
                                        <span class="inline-flex items-center px-2.5 py-0.5 bg-brand-500/10 text-brand-600 dark:text-brand-400 border border-brand-500/20 rounded-2xl text-xs font-bold">
                                            + {{ $role->permissions->count() - 12 }} أخرى
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('system.roles.edit', $role) }}"
                                        class="p-2 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/50 transition-colors"
                                        title="تعديل">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    @if($role->name !== 'super-admin')
                                        <form action="{{ route('system.roles.destroy', $role) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الدور؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/50 transition-colors"
                                                title="حذف">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-3">
                                        <i class="fas fa-shield-slash text-slate-300 dark:text-slate-600 text-2xl"></i>
                                    </div>
                                    <p class="text-slate-500 dark:text-slate-400 font-medium">لا توجد أدوار مسجلة</p>
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

