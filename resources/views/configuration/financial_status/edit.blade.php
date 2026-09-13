@extends('layouts.app')

@section('title', 'تعديل الحالة المالية')
@section('page_title', 'الترميزات الأساسية')

@section('content')
    <div class="space-y-6" dir="rtl">
        {{-- رأس الصفحة --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-amber-500 rounded-3xl shadow-xl shadow-amber-100 flex items-center justify-center text-white">
                    <i class="fas fa-edit text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">تعديل حالة مالية</h2>
                    <p class="text-slate-500 text-sm">تعديل بيانات الحالة: <span class="text-amber-600 font-bold">{{ $financialStatus->name }}</span></p>
                </div>
            </div>
            <a href="{{ route('configuration.financial-statuses.index') }}"
                class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                <i class="fas fa-arrow-right"></i> عودة للقائمة
            </a>
        </div>

        <div class="max-w-2xl mx-auto">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden transition-colors">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                    <h5 class="text-lg font-bold text-slate-800 dark:text-slate-200 header-font flex items-center gap-3">
                        <span class="w-2 h-6 bg-amber-500 rounded-full"></span>
                        تحديث البيانات
                    </h5>
                </div>
                <div class="p-8">
                    <form action="{{ route('configuration.financial-statuses.update', $financialStatus->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-8">
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">اسم الحالة المالية *</label>
                            <input type="text" name="name" required 
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 @error('name') ring-2 ring-rose-100 @enderror" 
                                placeholder="مثال: مدفوع..." value="{{ old('name', $financialStatus->name) }}">
                            @error('name')
                                <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <button type="button" onclick="history.back()" class="px-8 py-3 text-slate-400 font-bold hover:text-slate-600 transition-colors">إلغاء</button>
                            <button type="submit" class="px-10 py-4 bg-amber-500 text-white rounded-[2rem] font-bold hover:bg-amber-600 transition-all shadow-xl shadow-amber-100 flex items-center gap-2">
                                <i class="fas fa-check-circle"></i> تحديث البيانات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

