@extends('layouts.app')

@section('title', 'إضافة مستخدم جديد')
@section('page_title', 'إدارة النظام')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" dir="rtl">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <div class="p-2.5 bg-brand-500/10 text-brand-600 dark:text-brand-400 rounded-xl">
                    <i class="fas fa-user-plus text-xl"></i>
                </div>
                إضافة مستخدم جديد
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                إنشاء حساب مستخدم جديد وتعيين الصلاحيات والجهة التابع لها.
            </p>
        </div>
        <a href="{{ route('system.users.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm font-semibold rounded-xl transition-all shadow-sm">
            <i class="fas fa-arrow-right"></i>
            عودة للقائمة
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-xl text-rose-600 dark:text-rose-400">
            <div class="flex items-start gap-3">
                <i class="fas fa-exclamation-triangle text-lg mt-0.5"></i>
                <div>
                    <span class="font-bold block mb-1">يرجى تصحيح الأخطاء التالية:</span>
                    <ul class="list-disc list-inside text-sm space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('system.users.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- 1. Basic Information --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <span class="w-2 h-5 bg-brand-600 rounded-full"></span>
                المعلومات الأساسية
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">الاسم الكامل <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('name') border-rose-500 @enderror"
                        placeholder="أدخل الاسم الثلاثي أو الرباعي..." value="{{ old('name') }}">
                    @error('name')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">اسم المستخدم (للدخول) <span class="text-rose-500">*</span></label>
                    <input type="text" name="username" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-mono font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('username') border-rose-500 @enderror"
                        placeholder="username" value="{{ old('username') }}">
                    @error('username')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">رقم الهاتف</label>
                    <input type="text" name="phone"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all"
                        placeholder="09xxxxxxxx" value="{{ old('phone') }}">
                </div>
            </div>
        </div>

        {{-- 2. Password and Security --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <span class="w-2 h-5 bg-amber-500 rounded-full"></span>
                كلمة المرور والأمان
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">كلمة المرور <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('password') border-rose-500 @enderror"
                        placeholder="••••••••">
                    @error('password')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">تأكيد كلمة المرور <span class="text-rose-500">*</span></label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all"
                        placeholder="••••••••">
                </div>
            </div>
        </div>

        {{-- 3. Entity, Roles and Status --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <span class="w-2 h-5 bg-purple-600 rounded-full"></span>
                الجهة التابع لها والأدوار
            </h2>
            <div class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">الجهة التابع لها</label>
                        <select name="internal_entity_id"
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer">
                            <option value="">-- عام / غير محدد --</option>
                            @foreach($entities as $entity)
                                <option value="{{ $entity->id }}" @selected(old('internal_entity_id') == $entity->id)>
                                    {{ $entity->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">حالة الحساب</label>
                        <select name="is_active"
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer">
                            <option value="1" @selected(old('is_active', '1') == '1')>نشط (مفعل)</option>
                            <option value="0" @selected(old('is_active') == '0')>غير نشط (معطل)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-3">الأدوار الممنوحة <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($roles as $role)
                            <label class="flex items-center gap-3 p-3.5 bg-slate-50 dark:bg-slate-950/50 border border-slate-200 dark:border-slate-800 rounded-xl cursor-pointer hover:border-brand-500/40 transition-all">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                    @checked(in_array($role->name, old('roles', [])))
                                    class="w-4 h-4 text-brand-600 rounded border-slate-300 dark:border-slate-700 focus:ring-brand-500">
                                <span class="font-semibold text-slate-800 dark:text-slate-200 dark:text-slate-200 text-xs">{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
            <a href="{{ route('system.users.index') }}"
                class="px-5 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:text-slate-200 dark:hover:text-slate-200 transition-colors">
                إلغاء
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-sm font-semibold shadow-sm hover:shadow transition-all">
                <i class="fas fa-check"></i>
                إنشاء الحساب
            </button>
        </div>
    </form>
</div>
@endsection
