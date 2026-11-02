@extends('layouts.app')

@section('title', 'إدارة المستخدمين')
@section('page_title', 'إدارة النظام')

@section('content')
<div class="space-y-6" dir="rtl">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <div class="p-2.5 bg-brand-500/10 text-brand-600 dark:text-brand-400 rounded-xl">
                    <i class="fas fa-user-shield text-xl"></i>
                </div>
                إدارة المستخدمين
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                إدارة حسابات مستخدمي النظام والصلاحيات والجهات (إجمالي الحسابات: <span class="font-bold text-slate-700 dark:text-slate-200">{{ $users->total() }}</span>)
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('system.users.print-all', request()->all()) }}" target="_blank"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all">
                <i class="fas fa-print"></i>
                طباعة الكشف
            </a>
            <a href="{{ route('system.users.export', request()->all()) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all">
                <i class="fas fa-file-excel"></i>
                تصدير (Excel)
            </a>
            <a href="{{ route('data.import.index', ['type' => 'users']) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all">
                <i class="fas fa-file-import"></i>
                استيراد
            </a>
            <a href="{{ route('system.users.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all">
                <i class="fas fa-plus"></i>
                إضافة مستخدم جديد
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
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">المستخدم</th>
                        <th class="px-6 py-4">اسم الدخول</th>
                        <th class="px-6 py-4">النطاق الإداري</th>
                        <th class="px-6 py-4">النطاق الجغرافي</th>
                        <th class="px-6 py-4">الدور والصلاحيات</th>
                        <th class="px-6 py-4 text-center">الحالة</th>
                        <th class="px-6 py-4 text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/25 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs text-slate-400">#{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-brand-500/10 border border-brand-500/20 text-brand-600 dark:text-brand-400 flex items-center justify-center font-bold text-xs uppercase">
                                        {{ mb_substr($user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('system.users.show', $user) }}" class="font-bold text-slate-900 dark:text-white hover:text-brand-600 dark:hover:text-brand-400 transition-colors block">
                                            {{ $user->name }}
                                        </a>
                                        <span class="text-xs text-slate-400">{{ $user->phone ?? 'لا يوجد هاتف' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-mono font-semibold text-xs text-slate-700 dark:text-slate-300">
                                {{ $user->username }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-semibold bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20">
                                    <i class="fas fa-sitemap ml-1.5"></i>
                                    {{ $user->internal_entity->name ?? 'عام (كافة الجهات)' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    @forelse($user->geographicScopes as $geo)
                                        @if($geo->scope_type === 'all')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[11px] font-medium bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 w-fit">
                                                كل المحافظات والمديريات
                                            </span>
                                        @elseif($geo->scope_type === 'none')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[11px] font-medium bg-slate-500/10 text-slate-600 dark:text-slate-400 border border-slate-500/20 w-fit">
                                                بدون محافظة
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-medium bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/20 w-fit">
                                                <i class="fas fa-map-marker-alt text-[9px]"></i>
                                                {{ $geo->governorate->name ?? 'محافظة' }}: {{ $geo->directorate->name ?? 'كل المديريات' }}
                                            </span>
                                        @endif
                                    @empty
                                        <span class="text-xs text-slate-400 font-medium">غير محدد (كل النطاقات)</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($user->roles as $role)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-2xl text-xs font-semibold bg-brand-500/10 text-brand-600 dark:text-brand-400 border border-brand-500/20">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($user->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        نشط
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        غير نشط
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('system.users.print', $user) }}" target="_blank"
                                        class="p-2 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/50 transition-colors"
                                        title="طباعة الملف">
                                        <i class="fas fa-print text-xs"></i>
                                    </a>
                                    <a href="{{ route('system.users.show', $user) }}"
                                        class="p-2 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-brand-50 dark:hover:bg-brand-950/50 transition-colors"
                                        title="عرض الملف">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('system.users.edit', $user) }}"
                                        class="p-2 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/50 transition-colors"
                                        title="تعديل">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('system.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
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
                            <td colspan="6" class="py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-3">
                                        <i class="fas fa-user-slash text-slate-300 dark:text-slate-600 text-2xl"></i>
                                    </div>
                                    <p class="text-slate-500 dark:text-slate-400 font-medium">لا يوجد مستخدمون حالياً</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="flex justify-center">
        {{ $users->withQueryString()->links() }}
    </div>
</div>
@endsection

