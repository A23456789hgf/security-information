@extends('layouts.print')

@section('title', 'تقرير مصفوفة الأدوار والصلاحيات')

@section('content')
<div class="print-container">
    <div class="watermark-layer">وثيقة رسمية معتمدة</div>

    <x-print-header 
        title="تقرير مصفوفة الأدوار وصلاحيات النظام"
        subCode="مصفوفة الصلاحيات ونطاقات الوصول الأمنية • معتمدة رقمياً"
        :refNumber="'ROLE-ALL-' . date('Ymd')" 
    />

    @foreach($roles as $role)
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-shield-alt"></i>
                    <span>الدور الوظيفي: {{ $role->name }}</span>
                </div>
                <span class="section-header-badge">عدد الصلاحيات: {{ $role->permissions->count() }}</span>
            </div>
            
            <div style="padding: 10px 14px; background: #fafbfc; border-bottom: 1px solid #e2e8f0; font-size: 11px;">
                <strong>نطاقات الوصول الميداني:</strong>
                @if(is_array($role->scopes) && count($role->scopes) > 0)
                    @foreach($role->scopes as $mod => $scope)
                        <span class="status-badge status-badge-info" style="margin-right: 6px;">{{ $mod }}: {{ $scope }}</span>
                    @endforeach
                @else
                    <span style="color: #94a3b8;">شامل (كافة النطاقات)</span>
                @endif
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">#</th>
                        <th style="width: 200px;">الصلاحية البرمجية</th>
                        <th>اسم ووصف الصلاحية</th>
                        <th style="width: 100px; text-align: center;">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($role->permissions as $pIndex => $perm)
                        <tr>
                            <td style="text-align: center; font-weight: 700;">{{ $pIndex + 1 }}</td>
                            <td style="font-family: monospace; font-size: 10.5px; color: #475569;">{{ $perm->name }}</td>
                            <td style="font-weight: 700; color: #0f172a;">{{ $perm->name_ar ?? $perm->name }}</td>
                            <td style="text-align: center;">
                                <span class="status-badge status-badge-success">ممنوحة</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 15px; color: #94a3b8;">لا توجد صلاحيات مخصصة لهذا الدور</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endforeach

    <x-print-footer />
</div>
@endsection
