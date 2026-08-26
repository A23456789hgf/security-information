@extends('layouts.app')

@section('title', 'تعديل بيانات المستخدم')
@section('page_title', 'إدارة النظام')

@section('content')
<div class="container-fluid mx-auto px-4 py-8" dir="rtl">
    {{-- رأس الصفحة --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 max-w-5xl mx-auto">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-amber-500 rounded-3xl shadow-xl shadow-amber-100 flex items-center justify-center text-white">
                <i class="fas fa-user-edit text-2xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">تعديل بيانات المستخدم</h2>
                <p class="text-slate-500 text-sm">تحديث بيانات حساب: <span class="font-bold text-amber-600">{{ $user->name }}</span></p>
            </div>
        </div>
        <a href="{{ route('system.users.index') }}"
            class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2 text-sm">
            <i class="fas fa-arrow-right"></i> عودة للقائمة
        </a>
    </div>

    @if ($errors->any())
        <div class="max-w-5xl mx-auto mb-8 p-6 bg-rose-50 border border-rose-200 rounded-3xl text-rose-700">
            <div class="flex items-center gap-3 mb-2 font-bold text-base">
                <i class="fas fa-exclamation-circle text-rose-500 text-lg"></i>
                يرجى تصحيح الأخطاء التالية:
            </div>
            <ul class="list-disc list-inside text-sm space-y-1 pr-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('system.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="max-w-5xl mx-auto space-y-8">
            {{-- 1. المعلومات الأساسية --}}
            <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex items-center gap-3">
                    <span class="w-2 h-6 bg-amber-500 rounded-full"></span>
                    <h5 class="text-lg font-bold text-slate-800 header-font">المعلومات الأساسية</h5>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الاسم الكامل *</label>
                            <input type="text" name="name" required
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 @error('name') ring-2 ring-rose-100 @enderror"
                                value="{{ old('name', $user->name) }}">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">اسم المستخدم *</label>
                            <input type="text" name="username" required
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 @error('username') ring-2 ring-rose-100 @enderror"
                                value="{{ old('username', $user->username) }}">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">رقم الهاتف</label>
                            <input type="text" name="phone"
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700"
                                value="{{ old('phone', $user->phone) }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. تغيير كلمة المرور (اختياري) --}}
            <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-6 bg-slate-400 rounded-full"></span>
                        <h5 class="text-lg font-bold text-slate-800 header-font">تغيير كلمة المرور</h5>
                    </div>
                    <span class="text-xs font-bold text-slate-400 bg-slate-100 px-3 py-1 rounded-full">اختياري - اتركه فارغاً إذا لم ترغب بالتغيير</span>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">كلمة المرور الجديدة</label>
                            <input type="password" name="password"
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700"
                                placeholder="••••••••">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" name="password_confirmation"
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700"
                                placeholder="••••••••">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. الجهة والأدوار والحالة --}}
            <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex items-center gap-3">
                    <span class="w-2 h-6 bg-purple-600 rounded-full"></span>
                    <h5 class="text-lg font-bold text-slate-800 header-font">الجهة التابع لها والأدوار</h5>
                </div>
                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الجهة التابع لها</label>
                            <select name="internal_entity_id"
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-purple-100 transition-all font-bold text-slate-700">
                                <option value="">-- عام / غير محدد --</option>
                                @foreach($entities as $entity)
                                    <option value="{{ $entity->id }}" @selected(old('internal_entity_id', $user->internal_entity_id) == $entity->id)>
                                        {{ $entity->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">حالة الحساب</label>
                            <select name="is_active"
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-purple-100 transition-all font-bold text-slate-700">
                                <option value="1" @selected(old('is_active', (string)$user->is_active) == '1')>نشط (مفعل)</option>
                                <option value="0" @selected(old('is_active', (string)$user->is_active) == '0')>غير نشط (معطل)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400 mb-3 block px-2">الأدوار والصلاحيات *</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($roles as $role)
                                <label class="flex items-center gap-3 p-4 bg-slate-50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-100 transition-all">
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                        @checked(in_array($role->name, old('roles', $user->roles->pluck('name')->toArray())))
                                        class="w-5 h-5 text-blue-600 rounded-lg focus:ring-0">
                                    <span class="font-bold text-slate-700 text-xs">{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- أزرار الحفظ --}}
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('system.users.index') }}" class="px-8 py-3 text-slate-400 font-bold hover:text-slate-600 transition-colors">إلغاء</a>
                <button type="submit"
                    class="px-10 py-4 bg-amber-500 text-white rounded-[2rem] font-bold hover:bg-amber-600 transition-all shadow-xl shadow-amber-100 flex items-center gap-2">
                    <i class="fas fa-save"></i> حفظ التعديلات
                </button>
            </div>
        </div>
    </form>
</div>
@endsection