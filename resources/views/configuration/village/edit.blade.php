@extends('layouts.app')

@section('title', 'تعديل القرية / الحارة')
@section('page_title', 'الترميزات الجغرافية')

@section('content')
    <div class="space-y-6" dir="rtl">
        {{-- رأس الصفحة --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-amber-500 rounded-3xl shadow-xl shadow-amber-100 flex items-center justify-center text-white">
                    <i class="fas fa-edit text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">تعديل القرية / الحارة</h2>
                    <p class="text-slate-500 text-sm">تعديل بيانات القرية/الحارة: <span class="text-amber-600 font-bold">{{ $village->name }}</span></p>
                </div>
            </div>
            <a href="{{ route('configuration.villages.index') }}"
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
                    <form action="{{ route('configuration.villages.update', $village->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6">
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">المحافظة *</label>
                            <select name="governorate_id" id="gov" required 
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 appearance-none cursor-pointer">
                                <option value="">اختر المحافظة...</option>
                                @foreach($governorates as $gov)
                                    <option value="{{ $gov->id }}" {{ (old('governorate_id', $village->governorate_id) == $gov->id) ? 'selected' : '' }}>
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
                            <select name="directorate_id" id="dir" required 
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 appearance-none cursor-pointer">
                                <option value="">اختر المديرية...</option>
                                @foreach($directorates as $d)
                                    <option value="{{ $d->id }}" {{ (old('directorate_id', $village->directorate_id) == $d->id) ? 'selected' : '' }}>
                                        {{ $d->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('directorate_id')
                                <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">العزلة / المنطقة *</label>
                            <select name="sub_area_id" id="sub" required 
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 appearance-none cursor-pointer">
                                <option value="">اختر العزلة...</option>
                                @foreach($subAreas as $s)
                                    <option value="{{ $s->id }}" {{ (old('sub_area_id', $village->sub_area_id) == $s->id) ? 'selected' : '' }}>
                                        {{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sub_area_id')
                                <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-8">
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">اسم القرية / الحارة *</label>
                            <input type="text" name="name" required 
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 @error('name') ring-2 ring-rose-100 @enderror" 
                                placeholder="مثال: حارة النصر..." value="{{ old('name', $village->name) }}">
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
    document.addEventListener('DOMContentLoaded', function() {
        const gov = document.getElementById('gov');
        const dir = document.getElementById('dir');
        const sub = document.getElementById('sub');

        gov.addEventListener('change', () => {
            fetch(`/get-directorates/${gov.value}`)
                .then(res => res.json()).then(data => {
                    dir.innerHTML = '<option value="">اختر المديرية...</option>' + Object.entries(data).map(([id,name])=>`<option value="${id}">${name}</option>`).join('');
                    sub.innerHTML = '<option value="">اختر العزلة...</option>';
                });
        });

        dir.addEventListener('change', () => {
            fetch(`/get-sub-areas/${dir.value}`)
                .then(res => res.json()).then(data => {
                    sub.innerHTML = '<option value="">اختر العزلة...</option>' + Object.entries(data).map(([id,name])=>`<option value="${id}">${name}</option>`).join('');
                });
        });
    });
    </script>
@endsection

