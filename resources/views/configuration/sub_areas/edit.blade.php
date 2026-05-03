@extends('layouts.app')

@section('title', 'تعديل العزلة / المنطقة')
@section('page_title', 'الترميزات الجغرافية')

@section('content')
    <div class="container-fluid mx-auto px-4 py-8" dir="rtl">
        {{-- رأس الصفحة --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-amber-500 rounded-3xl shadow-xl shadow-amber-100 flex items-center justify-center text-white">
                    <i class="fas fa-edit text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">تعديل عزلة / منطقة</h2>
                    <p class="text-slate-500 text-sm">تعديل بيانات العزلة/المنطقة: <span class="text-amber-600 font-bold">{{ $subArea->name }}</span></p>
                </div>
            </div>
            <a href="{{ route('configuration.sub-areas.index') }}"
                class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                <i class="fas fa-arrow-right"></i> عودة للقائمة
            </a>
        </div>

        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                    <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                        <span class="w-2 h-6 bg-amber-500 rounded-full"></span>
                        تحديث البيانات
                    </h5>
                </div>
                <div class="p-8">
                    <form action="{{ route('configuration.sub-areas.update', $subArea->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6">
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">المحافظة *</label>
                            <select name="governorate_id" id="governorate_select" required 
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 appearance-none cursor-pointer">
                                <option value="">اختر المحافظة...</option>
                                @foreach($governorates as $gov)
                                    <option value="{{ $gov->id }}" {{ (old('governorate_id', $subArea->governorate_id) == $gov->id) ? 'selected' : '' }}>
                                        {{ $gov->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('governorate_id')
                                <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">المديرية *</label>
                            <select name="directorate_id" id="directorate_select" required 
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 appearance-none cursor-pointer">
                                @foreach($directorates as $dir)
                                    <option value="{{ $dir->id }}" {{ (old('directorate_id', $subArea->directorate_id) == $dir->id) ? 'selected' : '' }}>
                                        {{ $dir->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('directorate_id')
                                <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-8">
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">اسم العزلة / المنطقة *</label>
                            <input type="text" name="name" required 
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 @error('name') ring-2 ring-rose-100 @enderror" 
                                placeholder="مثال: عزلة الأعبوس..." value="{{ old('name', $subArea->name) }}">
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

    <script>
        document.getElementById('governorate_select').addEventListener('change', function () {
            const governorateId = this.value;
            const directorateSelect = document.getElementById('directorate_select');
            directorateSelect.innerHTML = '<option>جاري التحميل...</option>';

            fetch(`/get-directorates/${governorateId}`)
                .then(response => response.json())
                .then(data => {
                    let options = '<option value="">اختر المديرية...</option>';
                    for (let id in data) {
                        options += `<option value="${id}">${data[id]}</option>`;
                    }
                    directorateSelect.innerHTML = options;
                });
        });
    </script>
@endsection
