@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8" dir="rtl">
    <!-- Header with Breadcrumbs and Settings Link -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight flex items-center gap-3">
                <i class="fas fa-brain text-blue-600"></i>
                نظام تحليل وتمايز البيانات الذكي
            </h1>
            <p class="text-slate-500 mt-2 text-sm font-semibold">تحميل الملفات الخارجية لمطابقتها ديناميكياً مع كافة سجلات النظام والصفحات الاستقصائية.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            @if(auth()->check() && auth()->user()->hasRole('super-admin'))
                <a href="{{ route('data.differences.seed-mock-data') }}" 
                   class="flex items-center gap-2 px-5 py-3 bg-amber-50 text-amber-700 border border-amber-150 font-bold rounded-2xl hover:bg-amber-100 hover:text-amber-800 transition-all duration-300 shadow-sm hover:shadow"
                   onclick="return confirm('هل أنت متأكد من رغبتك في توليد 50 سجل محاكاة لكل صفحة افتراضية؟ سيقوم هذا الإجراء بإعادة تعيين السجلات التجريبية وتغذية سجلات جديدة.')">
                    <i class="fas fa-database text-amber-600"></i>
                    <span>تغذية 50 سجل تجريبي لكل وعاء</span>
                </a>
            @endif

            <a href="{{ route('data.differences.settings') }}" 
               class="flex items-center gap-2 px-5 py-3 bg-white text-slate-700 font-bold rounded-2xl border border-slate-200 hover:border-blue-300 hover:text-blue-600 transition-all duration-300 shadow-sm hover:shadow">
                <i class="fas fa-cog"></i>
                <span>إعدادات النظام والسجل الديناميكي</span>
            </a>
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-2xl font-bold flex items-center gap-3 animate-fade-in">
            <i class="fas fa-check-circle text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-rose-50 text-rose-800 border border-rose-200 rounded-2xl font-bold flex items-center gap-3 animate-fade-in">
            <i class="fas fa-exclamation-circle text-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Upload Card -->
        <div class="lg:col-span-1 bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex flex-col">
            <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-file-upload text-blue-500"></i>
                تحميل ملف جديد
            </h2>
            <p class="text-slate-400 text-xs font-semibold mb-6">يرجى اختيار ملف Excel أو CSV يحتوي على أسماء، هواتف أو أرقام هويات للتحليل والمطابقة.</p>

            <form action="{{ route('data.differences.store') }}" method="POST" enctype="multipart/form-data" class="flex-grow flex flex-col justify-between">
                @csrf
                <div class="border-2 border-dashed border-slate-200 hover:border-blue-400 rounded-2xl p-6 flex flex-col items-center justify-center text-center cursor-pointer transition-all duration-300 group min-h-[200px]"
                     onclick="document.getElementById('excel_file').click()">
                    <input type="file" name="excel_file" id="excel_file" class="hidden" accept=".xlsx,.xls,.csv" required onchange="displayFileName(this)">
                    <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <span class="text-sm font-bold text-slate-700 block mb-1 group-hover:text-blue-600 transition-colors">اسحب الملف أو اضغط للاختيار</span>
                    <span class="text-slate-400 text-xxs block" id="file-info">xlsx, xls, csv (الحد الأقصى 20 ميجا)</span>
                </div>

                <button type="submit" class="w-full mt-6 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                    <i class="fas fa-magic"></i>
                    <span>بدء المطابقة الفورية</span>
                </button>
            </form>
        </div>

        <!-- History Card -->
        <div class="lg:col-span-2 bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="fas fa-history text-slate-500"></i>
                سجل عمليات المطابقة السابقة
            </h2>

            @if($analyses->isEmpty())
                <div class="py-12 flex flex-col items-center justify-center text-center text-slate-400">
                    <i class="fas fa-folder-open text-5xl mb-4 text-slate-200"></i>
                    <span class="font-bold text-sm">لا توجد عمليات تحليل سابقة مسجلة</span>
                    <span class="text-xs mt-1 text-slate-300">قم بتحميل أول ملف لبدء معالجة التمايز والمطابقة.</span>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 text-xs font-bold uppercase tracking-wider">
                                <th class="pb-3 pr-2">تاريخ العملية</th>
                                <th class="pb-3">اسم الملف المستورد</th>
                                <th class="pb-3 text-center">إجمالي الصفوف</th>
                                <th class="pb-3 text-center">نسبة التقدم</th>
                                <th class="pb-3 text-center">الحالة</th>
                                <th class="pb-3 pl-2 text-center">الخيارات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm font-semibold text-slate-700">
                            @foreach($analyses as $item)
                                <tr class="hover:bg-slate-50/50 transition-colors duration-200">
                                    <td class="py-4 pr-2 text-slate-400 text-xs">{{ $item->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="py-4 font-bold text-slate-800 max-w-[200px] truncate">{{ $item->filename }}</td>
                                    <td class="py-4 text-center">{{ $item->total_rows ?: '---' }}</td>
                                    <td class="py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <div class="w-16 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                                <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $item->progress }}%"></div>
                                            </div>
                                            <span class="text-xs text-slate-400">{{ $item->progress }}%</span>
                                        </div>
                                    </td>
                                    <td class="py-4 text-center">
                                        @if($item->status === 'completed')
                                            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-150 rounded-full text-xs font-bold">مكتمل</span>
                                        @elseif($item->status === 'processing')
                                            <span class="px-3 py-1 bg-blue-50 text-blue-700 border border-blue-150 rounded-full text-xs font-bold animate-pulse">جاري التحليل</span>
                                        @elseif($item->status === 'pending')
                                            <span class="px-3 py-1 bg-amber-50 text-amber-700 border border-amber-150 rounded-full text-xs font-bold">في الانتظار</span>
                                        @else
                                            <span class="px-3 py-1 bg-rose-50 text-rose-700 border border-rose-150 rounded-full text-xs font-bold">فشل التحليل</span>
                                        @endif
                                    </td>
                                    <td class="py-4 pl-2 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('data.differences.show', $item->id) }}" 
                                               class="p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl transition-all duration-300 hover:scale-105 shadow-sm"
                                               title="عرض تفاصيل التمايز">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($item->status === 'completed')
                                                <a href="{{ route('data.differences.export-excel', $item->id) }}" 
                                                   class="p-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-xl transition-all duration-300 hover:scale-105 shadow-sm"
                                                   title="تصدير ملف Excel">
                                                    <i class="fas fa-file-excel"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function displayFileName(input) {
        const fileInfo = document.getElementById('file-info');
        if (input.files && input.files.length > 0) {
            const file = input.files[0];
            fileInfo.innerHTML = `<strong class="text-blue-600">${file.name}</strong> (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
        } else {
            fileInfo.innerText = "xlsx, xls, csv (الحد الأقصى 20 ميجا)";
        }
    }
</script>
@endsection
