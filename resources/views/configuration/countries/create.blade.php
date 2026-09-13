@extends('layouts.app')

@section('title', 'إضافة دولة جديدة')
@section('page_title', 'الترميزات الجغرافية')

@section('content')
    <div class="space-y-6" dir="rtl">
        {{-- رأس الصفحة --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-brand-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                    <i class="fas fa-plus text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">إضافة دولة جديدة</h2>
                    <p class="text-slate-500 text-sm">إضافة تصنيف جديد للدول في النظام الجغرافي.</p>
                </div>
            </div>
            <a href="{{ route('configuration.countries.index') }}"
                class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                <i class="fas fa-arrow-right"></i> عودة للقائمة
            </a>
        </div>

        <div class="max-w-2xl mx-auto">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden transition-colors">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                    <h5 class="text-lg font-bold text-slate-800 dark:text-slate-200 header-font flex items-center gap-3">
                        <span class="w-2 h-6 bg-brand-600 rounded-full"></span>
                        بيانات الدولة
                    </h5>
                </div>
                <div class="p-8">
                    <form action="{{ route('configuration.countries.store') }}" method="POST">
                        @csrf
                        
                        <x-form-input name="name" label="اسم الدولة *" placeholder="مثال: اليمن، السعودية، مصر..." :value="old('name')" required />

                        <div class="flex items-center justify-end gap-4">
                                                    <x-button type="button" onclick="history.back()">إلغاء</x-button>
                        <x-button type="submit"><i class="fas fa-check-circle"></i> حفظ البيانات</x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


