@extends('layouts.print')

@section('title', 'تقرير كشف الأقسام الإدارية والصفحات')

@section('content')
<div class="print-container">
    <div class="watermark-layer">وثيقة رسمية معتمدة</div>

    <x-print-header 
        title="تقرير قائمة الأقسام الإدارية والصفحات"
        subCode="الهيكل التنظيمي للأقسام والصفحات • معتمد رسمياً"
        :refNumber="'SEC-ALL-' . date('Ymd')" 
    />

    <div class="section-card">
        <div class="section-header">
            <div class="section-header-title">
                <i class="fas fa-th-large"></i>
                <span>قائمة الأقسام الإدارية والصفحات المرتبطة بها (إجمالي: {{ count($sections) }} قسم)</span>
            </div>
            <span class="section-header-badge">الهيكل الإداري</span>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">الترتيب</th>
                    <th>اسم القسم الإداري</th>
                    <th>المعرف البرمجي (Slug)</th>
                    <th>عدد الصفحات التابعة</th>
                    <th>حالة القسم</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sections as $section)
                    <tr>
                        <td style="text-align: center; font-weight: 700;">{{ $section->order_column }}</td>
                        <td style="font-weight: 800; color: #0f172a;">{{ $section->name }}</td>
                        <td style="font-family: monospace; color: #64748b;">{{ $section->slug }}</td>
                        <td style="font-weight: 700;">{{ $section->pages_count ?? $section->pages()->count() }} صفحة</td>
                        <td>
                            @if($section->is_active)
                                <span class="status-badge status-badge-success">نشط</span>
                            @else
                                <span class="status-badge status-badge-danger">متوقف</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 25px; color: #64748b;">لا توجد أقسام إدارية مسجلة</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-print-footer />
</div>
@endsection
