@props([
    'system' => 'نظام السجل الإلكتروني والمعلومات الأمنية',
    'date' => null
])

<footer class="report-footer">
    <div>
        تم استخراج هذه الوثيقة من النظام الإلكتروني بتاريخ {{ $date ?? date('Y-m-d H:i') }}
    </div>
    <div style="font-weight: 700;">
        {{ $system }} - صفحة 1 من 1
    </div>
</footer>
