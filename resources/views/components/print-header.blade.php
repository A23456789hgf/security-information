@props([
    'title' => 'تقرير رسمي',
    'subtitle' => 'بالأمن الوقائي بالتعبئة العامة',
    'refNumber' => null,
    'date' => null
])

<div class="print-header-official border-b-2 border-slate-900 pb-4 mb-6 pt-2">
    <div class="flex items-center justify-between text-right">
        {{-- الجانب الأيمن: الترويسة الرسمية --}}
        <div>
            <div class="font-bold text-lg text-slate-900">الجمهورية اليمنية</div>
            <div class="font-bold text-sm text-slate-700">{{ config('app.name', 'نظام المعلومات') }}</div>
            <div class="text-xs text-slate-500 font-semibold mt-1">{{ $subtitle }}</div>
        </div>

        {{-- المنتصف: عنوان التقرير --}}
        <div class="text-center">
            <h1 class="font-bold text-xl text-slate-900 border-b-2 border-slate-700 pb-1 mb-1 px-4 inline-block">
                {{ $title }}
            </h1>
            @if($refNumber)
                <div class="text-xs font-mono text-slate-600">الرقم المرجعي: {{ $refNumber }}</div>
            @endif
        </div>

        {{-- الجانب الأيسر: التاريخ والوقت --}}
        <div class="text-left text-xs text-slate-600 space-y-1">
            <div><strong>تاريخ الطباعة:</strong> {{ $date ?? now()->format('Y/m/d') }}</div>
            <div><strong>توقيت الطباعة:</strong> {{ now()->format('H:i A') }}</div>
            <div><strong>الحالة:</strong> وثيقة رسمية - سرية</div>
        </div>
    </div>
</div>
