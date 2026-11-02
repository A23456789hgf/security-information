@extends('layouts.app')

@section('title', 'تعديل القسم الإداري')
@section('page_title', 'إدارة النظام')

@section('content')
<div class="space-y-6" dir="rtl">
    {{-- رأس الصفحة --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 max-w-3xl mx-auto">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-amber-500 rounded-3xl shadow-xl shadow-amber-100 flex items-center justify-center text-white">
                <i class="fas fa-edit text-2xl"></i>
            </div>
            <div>
                <h2 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">تعديل القسم الإداري</h2>
                <p class="text-slate-500 text-sm">تعديل بيانات وإعدادات قسم: <span class="font-bold text-amber-600">{{ $section->name }}</span></p>
            </div>
        </div>
        <a href="{{ route('system.sections.index') }}"
            class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2 text-sm">
            <i class="fas fa-arrow-right"></i> عودة للقائمة
        </a>
    </div>

    @if ($errors->any())
        <div class="max-w-3xl mx-auto mb-8 p-6 bg-rose-50 border border-rose-200 rounded-3xl text-rose-700">
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

    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden transition-colors">
            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex items-center gap-3">
                <span class="w-2 h-6 bg-amber-500 rounded-full"></span>
                <h5 class="text-lg font-bold text-slate-800 dark:text-slate-200 header-font">تعديل بيانات القسم</h5>
            </div>
            <div class="p-8">
                <form action="{{ route('system.sections.update', $section) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-6">
                        <div>
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">اسم القسم *</label>
                            <input type="text" name="name" required
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 @error('name') ring-2 ring-rose-100 @enderror"
                                value="{{ old('name', $section->name) }}">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الأيقونة (FontAwesome)</label>
                                <input type="text" name="icon"
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700"
                                    value="{{ old('icon', $section->icon) }}">
                                <span class="text-[10px] text-slate-400 px-2 mt-1 block">مثال: fa-users, fa-building, fa-shield-alt</span>
                            </div>

                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">ترتيب العرض</label>
                                <input type="number" name="order_column"
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 text-center"
                                    value="{{ old('order_column', $section->order_column) }}">
                            </div>
                        </div>

                        <div>
                            <label class="flex items-center gap-3 p-4 bg-slate-50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-100 transition-all">
                                <input type="checkbox" name="is_active" value="1" id="isActive" @checked(old('is_active', $section->is_active))
                                    class="w-5 h-5 text-brand-600 rounded-2xl focus:ring-0">
                                <span class="font-bold text-slate-700 text-xs">تفعيل وعرض القسم في القائمة الرئيسية</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-50">
                            <a href="{{ route('system.sections.index') }}" class="px-8 py-3 text-slate-400 font-bold hover:text-slate-600 transition-colors">إلغاء</a>
                            <button type="submit"
                                class="px-10 py-4 bg-amber-500 text-white rounded-[2rem] font-bold hover:bg-amber-600 transition-all shadow-xl shadow-amber-100 flex items-center gap-2">
                                <i class="fas fa-save"></i> حفظ التعديلات
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


