@extends('layouts.print')

@section('title', 'كشف الصفحات المخصصة والديناميكية')

@section('content')
<div class="print-container">
    <div class="watermark-layer">وثيقة رسمية معتمدة</div>

    <x-print-header 
        title="كشف الصفحات المخصصة والديناميكية"
        subCode="هيكل النماذج وقواعد البيانات المخصصة • معتمد رسمياً"
        :refNumber="'PAGE-ALL-' . date('Ymd')" 
    />

    <div class="section-card">
        <div class="section-header">
            <div class="section-header-title">
                <i class="fas fa-layer-group"></i>
                <span>قائمة الصفحات والنماذج المخصصة في النظام (إجمالي: {{ count($pages) }} صفحة)</span>
            </div>
            <span class="section-header-badge">إدارة النماذج</span>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">#</th>
                    <th>اسم الصفحة المخصصة</th>
                    <th>القسم الإداري التابعة له</th>
                    <th>المعرف البرمجي (Slug)</th>
                    <th>عدد الحقول المصممة</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $index => $item)
                    <tr>
                        <td style="text-align: center; font-weight: 700;">{{ $index + 1 }}</td>
                        <td style="font-weight: 800; color: #0f172a;">{{ $item->name }}</td>
                        <td>{{ $item->section->name ?? 'غير محدد' }}</td>
                        <td style="font-family: monospace; color: #64748b;">{{ $item->slug }}</td>
                        <td style="font-weight: 700;">{{ $item->fields_count }} حقل</td>
                        <td>
                            @if($item->is_active)
                                <span class="status-badge status-badge-success">نشطة</span>
                            @else
                                <span class="status-badge status-badge-danger">معطلة</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 25px; color: #64748b;">لا توجد صفحات مخصصة مسجلة</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-print-footer />
</div>
@endsection
