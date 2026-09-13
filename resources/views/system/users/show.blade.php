@extends('layouts.app')

@section('title', 'تفاصيل المستخدم')
@section('page_title', 'إدارة النظام')

@section('content')
<div class="space-y-6" dir="rtl">
    {{-- Header Actions --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <div class="p-2.5 bg-brand-500/10 text-brand-600 dark:text-brand-400 rounded-xl">
                    <i class="fas fa-user text-xl"></i>
                </div>
                ملف المستخدم: {{ $user->name }}
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                عرض تفاصيل الحساب والصلاحيات والجهة التابع لها
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('system.users.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm font-semibold rounded-xl transition-all shadow-sm">
                <i class="fas fa-arrow-right"></i>
                العودة للقائمة
            </a>
            <a href="{{ route('system.users.edit', $user) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all">
                <i class="fas fa-edit"></i>
                تعديل البيانات
            </a>
        </div>
    </div>

    {{-- Detail Grid: Right Profile summary + Left detailed cards --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile Summary bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mb-4 --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm text-center">
                <div class="w-20 h-20 mx-auto rounded-2xl bg-brand-500/10 border border-brand-500/20 text-brand-600 dark:text-brand-400 flex items-center justify-center font-bold text-2xl uppercase mb-4">
                    {{ mb_substr($user->name, 0, 2) }}
                </div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ $user->name }}</h2>
                <p class="text-xs font-mono font-semibold text-slate-500 dark:text-slate-400 mb-3">{{ '@' . $user->username }}</p>

                <div class="flex flex-wrap justify-center gap-1.5 mb-6">
                    @foreach($user->roles as $role)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-brand-500/10 text-brand-600 dark:text-brand-400 border border-brand-500/20">
                            {{ $role->name }}
                        </span>
                    @endforeach
                </div>

                <div class="border-t border-slate-100 dark:border-slate-800 pt-4 space-y-3 text-right text-xs">
                    <div>
                        <span class="text-slate-400 block mb-0.5"><i class="fas fa-envelope ml-1"></i> البريد الإلكتروني</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-200 dark:text-slate-200">{{ $user->email ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5"><i class="fas fa-phone ml-1"></i> رقم الهاتف</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-200 dark:text-slate-200">{{ $user->phone ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5"><i class="fas fa-calendar-alt ml-1"></i> تاريخ الإنشاء</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-200 dark:text-slate-200">{{ $user->created_at->format('Y-m-d') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Details & Permissions bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mb-4 --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <span class="w-2 h-5 bg-brand-600 rounded-full"></span>
                    معلومات الارتباط والحالة
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="bg-slate-50 dark:bg-slate-950/50 p-4 rounded-xl border border-slate-200 dark:border-slate-800">
                        <span class="text-slate-400 block mb-1">الجهة التابع لها</span>
                        <div class="flex items-center gap-2 font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200 text-sm">
                            <i class="fas fa-building text-brand-600 dark:text-brand-400"></i>
                            <span>{{ $user->internal_entity->name ?? 'مستخدم عام / غير محدد' }}</span>
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-950/50 p-4 rounded-xl border border-slate-200 dark:border-slate-800">
                        <span class="text-slate-400 block mb-1">حالة الحساب</span>
                        @if($user->is_active)
                            <div class="flex items-center gap-2 font-bold text-emerald-600 dark:text-emerald-400 text-sm">
                                <i class="fas fa-check-circle"></i>
                                <span>نشط (مفعل)</span>
                            </div>
                        @else
                            <div class="flex items-center gap-2 font-bold text-rose-600 dark:text-rose-400 text-sm">
                                <i class="fas fa-times-circle"></i>
                                <span>غير نشط (معطل)</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <span class="w-2 h-5 bg-purple-600 rounded-full"></span>
                    الصلاحيات الممنوحة
                </h3>
                <div class="flex flex-wrap gap-2">
                    @forelse($user->getAllPermissions() as $permission)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-2xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                            <i class="fas fa-shield-alt text-brand-600 dark:text-brand-400 text-[10px] ml-1.5"></i>
                            {{ $permission->name }}
                        </span>
                    @empty
                        <p class="text-slate-400 text-xs italic">لا توجد صلاحيات فردية مخصصة لهذا المستخدم</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

