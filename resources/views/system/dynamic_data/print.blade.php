@extends('layouts.print')

@section('title', 'كشف بيانات صفحة: ' . $page->name)

@section('content')
<div class="print-container">
    <div class="watermark-layer">وثيقة رسمية معتمدة</div>

    <x-print-header 
        :title="'كشف بيانات: ' . $page->name"
        subCode="سجل قيود وإدخالات النماذج المخصصة • معتمد رسمياً"
        :refNumber="'DYN-' . strtoupper($page->slug) . '-' . date('Ymd')" 
    />

    <div class="section-card">
        <div class="section-header">
            <div class="section-header-title">
                <i class="fas fa-database"></i>
                <span>قائمة السجلات المقيدة في {{ $page->name }} (إجمالي: {{ count($records) }} سجل)</span>
            </div>
            <span class="section-header-badge">سجلات ديناميكية</span>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 35px; text-align: center;">#</th>
                    @foreach($page->fields as $field)
                        <th>{{ $field->label }}</th>
                    @endforeach
                    <th style="width: 90px;">تاريخ القيد</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $index => $record)
                    <tr>
                        <td style="text-align: center; font-weight: 700;">{{ $index + 1 }}</td>
                        @foreach($page->fields as $field)
                            @php
                                $valObj = $record->values->firstWhere('field_id', $field->id);
                                $val = $valObj?->value;
                            @endphp
                            <td>
                                @if($field->type === 'file' || $field->type === 'image')
                                    {{ $val ? 'مرفق مسجل' : '-' }}
                                @elseif($field->type === 'checkbox')
                                    {{ $val ? 'نعم' : 'لا' }}
                                @else
                                    {{ $val ?? '-' }}
                                @endif
                            </td>
                        @endforeach
                        <td style="font-size: 10px; color: #64748b;">
                            {{ $record->created_at ? $record->created_at->format('Y/m/d') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($page->fields) + 2 }}" style="text-align: center; padding: 25px; color: #64748b;">
                            لا توجد بيانات مقيدة في هذه الصفحة
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-print-footer />
</div>
@endsection
