@extends('layouts.app')

@section('title', 'إضافة جهة جديدة')
@section('page_title', 'الهيكل التنظيمي')

@section('content')
<div class="container-fluid mx-auto px-4 py-8" dir="rtl">
    {{-- رأس الصفحة --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 max-w-4xl mx-auto">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-blue-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                <i class="fas fa-plus text-2xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">إضافة جهة جديدة</h2>
                <p class="text-slate-500 text-sm">إدراج جهة جديدة في الهيكل التنظيمي وتحديد موقعها والجهة الأم.</p>
            </div>
        </div>
        <a href="{{ route('system.entities.index') }}"
            class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2 text-sm">
            <i class="fas fa-arrow-right"></i> عودة للقائمة
        </a>
    </div>

    @if ($errors->any())
        <div class="max-w-4xl mx-auto mb-8 p-6 bg-rose-50 border border-rose-200 rounded-3xl text-rose-700">
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

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex items-center gap-3">
                <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                <h5 class="text-lg font-bold text-slate-800 header-font">بيانات الجهة</h5>
            </div>
            <div class="p-8">
                <form action="{{ route('system.entities.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">اسم الجهة *</label>
                            <input type="text" name="name" required
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700 @error('name') ring-2 ring-rose-100 @enderror"
                                placeholder="أدخل اسم الجهة أو الإدارة..." value="{{ old('name') }}">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الجهة الأعلى (الأب)</label>
                            <select name="parent_id"
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700">
                                <option value="">-- جهة رئيسية (بدون أب) --</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}" @selected(old('parent_id', request('parent_id')) == $parent->id)>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">المحافظة</label>
                            <select name="governorate_id"
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700">
                                <option value="">-- اختر المحافظة --</option>
                                @foreach($governorates as $gov)
                                    <option value="{{ $gov->id }}" @selected(old('governorate_id') == $gov->id)>
                                        {{ $gov->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">المديرية</label>
                            <select name="directorate_id"
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700">
                                <option value="">-- اختر المديرية --</option>
                                @foreach($directorates as $dir)
                                    <option value="{{ $dir->id }}" @selected(old('directorate_id') == $dir->id)>
                                        {{ $dir->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2 flex items-center justify-end gap-4 pt-4 border-t border-slate-50">
                            <a href="{{ route('system.entities.index') }}" class="px-8 py-3 text-slate-400 font-bold hover:text-slate-600 transition-colors">إلغاء</a>
                            <button type="submit"
                                class="px-10 py-4 bg-blue-600 text-white rounded-[2rem] font-bold hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 flex items-center gap-2">
                                <i class="fas fa-check-circle"></i> حفظ الجهة
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
