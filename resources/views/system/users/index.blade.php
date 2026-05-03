@extends('layouts.app')

@section('title', 'إدارة المستخدمين')
@section('page_title', 'إدارة النظام')

@section('content')
<div class="container-fluid py-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-blue-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                <i class="fas fa-user-shield text-2xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">إدارة المستخدمين</h2>
                <p class="text-slate-500 text-sm flex items-center gap-2">
                    إدارة حسابات المستخدمين وصلاحيات الوصول
                    <span class="inline-flex items-center justify-center bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs font-bold">
                        {{ $users->total() }} حساب
                    </span>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('system.users.create') }}"
                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold transition-all shadow-lg shadow-blue-100">
                <i class="fas fa-plus"></i>
                <span>إضافة مستخدم جديد</span>
            </a>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-3xl soft-shadow border border-slate-100" style="overflow: visible !important;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="bg-slate-50/80 border-b border-slate-100">
                    <tr>
                        <th class="ps-6 py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]">المسؤول</th>
                        <th class="py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]">اسم المستخدم</th>
                        <th class="py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]">الدور / الصلاحية</th>
                        <th class="py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]">الحالة</th>
                        <th class="text-center pe-6 py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]" style="width: 150px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $user)
                    <tr class="group hover:bg-slate-50/50 transition-colors">
                        <td class="ps-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-bold text-xs uppercase border border-blue-100">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-slate-700 mb-0">{{ $user->name }}</div>
                                    <div class="text-[10px] text-slate-400 mb-0">{{ $user->internal_entity->name ?? 'مستخدم عام' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 font-mono text-slate-600">{{ $user->username }}</td>
                        <td class="py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($user->roles as $role)
                                    <span class="inline-flex items-center px-2 py-0.5 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-bold border border-blue-100">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="py-4">
                            @if($user->is_active)
                                <span class="inline-flex items-center px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-bold border border-emerald-100">
                                    <i class="fas fa-check-circle me-1 opacity-50"></i> نشط
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 bg-rose-50 text-rose-600 rounded-full text-[10px] font-bold border border-rose-100">
                                    <i class="fas fa-times-circle me-1 opacity-50"></i> غير نشط
                                </span>
                            @endif
                        </td>
                        <td class="text-center pe-6 py-4">
                            <div class="flex justify-end items-center gap-2">
                                <a href="{{ route('system.users.edit', $user) }}" class="w-9 h-9 flex items-center justify-center rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-600 hover:text-white shadow-sm transition-all" title="تعديل">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                        <path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                        <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                    </svg>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('system.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-lg bg-rose-100 text-rose-700 hover:bg-rose-600 hover:text-white shadow-sm transition-all" title="حذف">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                                <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75V4H5a2 2 0 00-2 2v.25C3 6.664 3.336 7 3.75 7h12.5a.75.75 0 000-1.5h-.25V4A2.75 2.75 0 0013.25 1h-4.5zM6 5.5v-1.75C6 3.224 6.448 2.75 7 2.75h6c.552 0 1 .474 1 1V5.5H6z" clip-rule="evenodd" />
                                                <path d="M4 8.25V15.5A2.75 2.75 0 006.75 18.25h6.5A2.75 2.75 0 0016 15.5V8.25H4zM7.5 10a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 017.5 10zm5.25.75a.75.75 0 00-1.5 0v4.5a.75.75 0 001.5 0v-4.5z" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8 flex justify-center">
        {{ $users->links() }}
    </div>
</div>
@endsection