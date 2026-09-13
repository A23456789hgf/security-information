@extends('layouts.app')

@section('title', 'الملف الشخصي')
@section('page_title', 'الملف الشخصي')

@section('content')
<div class="space-y-6" dir="rtl">

    {{-- Page Header --}}
    <div class="flex items-center gap-4 mb-10">
        <div class="w-16 h-16 bg-brand-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
            <i class="fas fa-user-circle text-2xl"></i>
        </div>
        <div>
            <h2 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">الملف الشخصي</h2>
            <p class="text-slate-500 text-sm">إدارة بيانات حسابك الشخصي وتحديث كلمة المرور.</p>
        </div>
    </div>

    {{-- Success/Error Alerts --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-3xl px-6 py-4 mb-8 flex items-center gap-3 font-bold text-sm">
            <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 max-w-7xl mx-auto">

        {{-- ===== LEFT: Profile bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mb-4 ===== --}}
        <div class="lg:col-span-4">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden sticky top-8">

                {{-- Avatar Banner --}}
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 px-8 pt-10 pb-16 text-center">
                    <div class="w-24 h-24 mx-auto rounded-full bg-white/20 border-4 border-white/30 flex items-center justify-center text-white text-4xl font-extrabold shadow-2xl mb-4">
                        {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                    </div>
                    <h3 class="text-white text-xl font-extrabold header-font">{{ $user->name }}</h3>
                    <span class="inline-block mt-2 px-4 py-1 bg-white/20 text-white text-xs font-bold rounded-full">
                        {{ $user->roles->first()->name ?? 'مستخدم النظام' }}
                    </span>
                </div>

                {{-- Info Stats --}}
                <div class="px-8 py-6 -mt-8">
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                        <div class="flex items-center justify-between px-6 py-4">
                            <span class="text-xs font-bold text-slate-400 flex items-center gap-2">
                                <i class="fas fa-id-badge text-brand-400"></i> معرف الدخول
                            </span>
                            <span class="font-bold text-slate-700 text-sm">{{ $user->username }}</span>
                        </div>
                        <div class="flex items-center justify-between px-6 py-4">
                            <span class="text-xs font-bold text-slate-400 flex items-center gap-2">
                                <i class="fas fa-calendar-check text-emerald-400"></i> تاريخ الانضمام
                            </span>
                            <span class="font-bold text-slate-700 text-sm">{{ $user->created_at->format('Y-m-d') }}</span>
                        </div>
                        @if($user->phone)
                        <div class="flex items-center justify-between px-6 py-4">
                            <span class="text-xs font-bold text-slate-400 flex items-center gap-2">
                                <i class="fas fa-phone text-amber-400"></i> الهاتف
                            </span>
                            <span class="font-bold text-slate-700 text-sm">{{ $user->phone }}</span>
                        </div>
                        @endif
                        @if($user->internal_entity)
                        <div class="flex items-center justify-between px-6 py-4">
                            <span class="text-xs font-bold text-slate-400 flex items-center gap-2">
                                <i class="fas fa-building text-purple-400"></i> الجهة
                            </span>
                            <span class="font-bold text-slate-700 text-sm">{{ $user->internal_entity->name }}</span>
                        </div>
                        @endif
                        <div class="flex items-center justify-between px-6 py-4">
                            <span class="text-xs font-bold text-slate-400 flex items-center gap-2">
                                <i class="fas fa-circle text-emerald-400"></i> حالة الحساب
                            </span>
                            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">نشط</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== RIGHT: Forms ===== --}}
        <div class="lg:col-span-8 space-y-8">

            {{-- ---- Form 1: Update Basic Info ---- --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden transition-colors">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex items-center gap-4">
                    <div class="w-10 h-10 bg-brand-100 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-user-pen text-brand-600"></i>
                    </div>
                    <div>
                        <h5 class="text-lg font-bold text-slate-800 dark:text-slate-200 header-font">تعديل المعلومات الأساسية</h5>
                        <p class="text-xs text-slate-400 font-bold">تحديث اسمك ورقم هاتفك وحالتك الوظيفية.</p>
                    </div>
                </div>

                <form action="{{ route('system.profile.update') }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    @if($errors->hasAny(['name','phone','work_status']))
                        <div class="bg-rose-50 border border-rose-100 rounded-2xl px-5 py-4 mb-6 text-rose-700 text-sm font-bold">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            @foreach(['name','phone','work_status'] as $field)
                                @error($field) <div>{{ $message }}</div> @enderror
                            @endforeach
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Username (read-only) --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">معرف الدخول (ثابت)</label>
                            <div class="relative">
                                <i class="fas fa-lock absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                                <input type="text" value="{{ $user->username }}" disabled
                                    class="w-full pr-10 pl-4 py-4 bg-slate-100 border-0 rounded-2xl font-bold text-slate-400 cursor-not-allowed">
                            </div>
                        </div>

                        {{-- Full Name --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الاسم الكامل *</label>
                            <div class="relative">
                                <i class="fas fa-user absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full pr-10 pl-4 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700 @error('name') ring-2 ring-rose-200 @enderror">
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">رقم الهاتف</label>
                            <div class="relative">
                                <i class="fas fa-phone absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                    class="w-full pr-10 pl-4 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700"
                                    placeholder="7xx xxx xxx">
                            </div>
                        </div>

                        {{-- Work Status --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الحالة الوظيفية</label>
                            <div class="relative">
                                <i class="fas fa-briefcase absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                                <input type="text" name="work_status" value="{{ old('work_status', $user->work_status) }}"
                                    class="w-full pr-10 pl-4 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700"
                                    placeholder="مثال: مدير، محلل...">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-8">
                        <button type="submit"
                            class="px-10 py-4 bg-brand-600 text-white rounded-[2rem] font-bold hover:bg-brand-700 transition-all shadow-xl shadow-blue-100 flex items-center gap-2">
                            <i class="fas fa-save"></i> حفظ التعديلات
                        </button>
                    </div>
                </form>
            </div>

            {{-- ---- Form 2: Change Password ---- --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden transition-colors">
                <div class="px-8 py-6 border-b border-slate-50 bg-amber-50/50 flex items-center gap-4">
                    <div class="w-10 h-10 bg-amber-100 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-key text-amber-600"></i>
                    </div>
                    <div>
                        <h5 class="text-lg font-bold text-slate-800 dark:text-slate-200 header-font">تغيير كلمة المرور</h5>
                        <p class="text-xs text-slate-400 font-bold">استخدم كلمة مرور قوية لا تقل عن 8 رموز.</p>
                    </div>
                </div>

                <form action="{{ route('system.profile.password') }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    @if($errors->hasAny(['current_password','password']))
                        <div class="bg-rose-50 border border-rose-100 rounded-2xl px-5 py-4 mb-6 text-rose-700 text-sm font-bold">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            @foreach(['current_password','password'] as $field)
                                @error($field) <div>{{ $message }}</div> @enderror
                            @endforeach
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Current Password (full width) --}}
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-amber-600 mb-2 block px-2">كلمة المرور الحالية *</label>
                            <div class="relative">
                                <i class="fas fa-lock absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                                <input type="password" name="current_password" required
                                    id="current_password_field"
                                    class="w-full pr-10 pl-12 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 @error('current_password') ring-2 ring-rose-200 @enderror"
                                    placeholder="أدخل كلمة المرور الحالية">
                                <button type="button" onclick="togglePassword('current_password_field', 'eye_current')"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500 transition-colors">
                                    <i id="eye_current" class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        {{-- New Password --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">كلمة المرور الجديدة *</label>
                            <div class="relative">
                                <i class="fas fa-shield-halved absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                                <input type="password" name="password" required
                                    id="new_password_field"
                                    class="w-full pr-10 pl-12 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 @error('password') ring-2 ring-rose-200 @enderror"
                                    placeholder="8 رموز كحد أدنى">
                                <button type="button" onclick="togglePassword('new_password_field', 'eye_new')"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500 transition-colors">
                                    <i id="eye_new" class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">تأكيد كلمة المرور *</label>
                            <div class="relative">
                                <i class="fas fa-check-double absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                                <input type="password" name="password_confirmation" required
                                    id="confirm_password_field"
                                    class="w-full pr-10 pl-12 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700"
                                    placeholder="أعد إدخال كلمة المرور الجديدة">
                                <button type="button" onclick="togglePassword('confirm_password_field', 'eye_confirm')"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500 transition-colors">
                                    <i id="eye_confirm" class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Password strength tip --}}
                    <div class="mt-4 p-4 bg-amber-50 border border-amber-100 rounded-2xl flex items-start gap-3 text-xs text-amber-700">
                        <i class="fas fa-lightbulb mt-0.5 flex-shrink-0"></i>
                        <span>استخدم مزيجاً من الحروف الكبيرة والصغيرة والأرقام والرموز الخاصة لكلمة مرور أقوى.</span>
                    </div>

                    <div class="flex justify-end mt-8">
                        <button type="submit"
                            class="px-10 py-4 bg-amber-500 text-white rounded-[2rem] font-bold hover:bg-amber-600 transition-all shadow-xl shadow-amber-100 flex items-center gap-2">
                            <i class="fas fa-sync-alt"></i> تحديث كلمة المرور
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePassword(fieldId, eyeId) {
    const field = document.getElementById(fieldId);
    const eye   = document.getElementById(eyeId);
    if (field.type === 'password') {
        field.type = 'text';
        eye.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        field.type = 'password';
        eye.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endpush
@endsection


