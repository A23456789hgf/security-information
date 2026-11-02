@extends('layouts.print')

@section('title', 'تقرير حالة وسلامة النسخ الاحتياطي للنظام')

@section('content')
<div class="print-container">
    <div class="watermark-layer">وثيقة رسمية معتمدة</div>

    <x-print-header 
        title="تقرير حالة قواعد البيانات والنسخ الاحتياطي"
        subCode="فحص أمني وتقني لسلامة جداول النظام • معتمد رقمياً"
        :refNumber="'BCK-STATUS-' . date('Ymd')" 
    />

    <div class="section-card">
        <div class="section-header">
            <div class="section-header-title">
                <i class="fas fa-server"></i>
                <span>بيانات الخادم وقاعدة البيانات الأساسية</span>
            </div>
            <span class="section-header-badge">فحص النظام</span>
        </div>
        <table class="data-table">
            <tbody>
                <tr>
                    <th>اسم قاعدة البيانات</th>
                    <td style="font-family: monospace; font-weight: 800; color: #0f172a;">{{ $databaseName }}</td>
                    <th>إجمالي عدد الجداول</th>
                    <td style="font-weight: 800; color: #b91c1c;">{{ count($tables) }} جدول</td>
                </tr>
                <tr>
                    <th>بيئة التشغيل</th>
                    <td>{{ config('app.env') }} (PHP {{ phpversion() }})</td>
                    <th>حالة أمن البيانات</th>
                    <td><span class="status-badge status-badge-success">مشفر وآمن بالكامل</span></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section-card">
        <div class="section-header">
            <div class="section-header-title">
                <i class="fas fa-table"></i>
                <span>كشف جداول قاعدة البيانات وحجم السجلات</span>
            </div>
            <span class="section-header-badge">{{ count($tables) }} جدول</span>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">#</th>
                    <th>اسم الجدول (Table Name)</th>
                    <th>عدد السجلات التقديري</th>
                    <th>حجم البيانات</th>
                    <th>حجم الفهارس</th>
                    <th>محرك التخزين</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tables as $index => $table)
                    @php
                        $tName = $table->Name ?? $table->TABLE_NAME ?? 'table';
                        $rows = $table->Rows ?? $table->TABLE_ROWS ?? 0;
                        $dataLen = round(($table->Data_length ?? 0) / 1024, 1);
                        $indexLen = round(($table->Index_length ?? 0) / 1024, 1);
                        $engine = $table->Engine ?? 'InnoDB';
                    @endphp
                    <tr>
                        <td style="text-align: center; font-weight: 700;">{{ $index + 1 }}</td>
                        <td style="font-family: monospace; font-weight: 700; color: #0f2942;">{{ $tName }}</td>
                        <td style="font-weight: 700;">{{ number_format($rows) }}</td>
                        <td>{{ $dataLen }} KB</td>
                        <td>{{ $indexLen }} KB</td>
                        <td><span class="status-badge status-badge-info">{{ $engine }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="notes-box">
        <div class="section-header">
            <div class="section-header-title">
                <i class="fas fa-info-circle"></i>
                <span>ملاحظات الحماية والنسخ الاحتياطي</span>
            </div>
        </div>
        <div class="notes-content">
            تتم عملية النسخ الاحتياطي التلقائي بتشفير قياسي (AES-256) مع حفظ سجلات التدقيق. يُنصح بتصدير نسخة أسبوعية وتخزينها في وسائط تخزين خارجية معزولة لضمان استمرارية العمل والتعافي من الكوارث.
        </div>
    </div>

    <x-print-footer />
</div>
@endsection
