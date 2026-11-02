@extends('layouts.print')

@section('title', 'بطاقة التعريف بالملف الشخصي - ' . $user->name)

@section('content')
<div class="print-container">
    <div class="watermark-layer">وثيقة رسمية معتمدة</div>

    <x-print-header 
        title="بطاقة التعريف بالملف الشخصي للمستخدم"
        subCode="بيانات اعتماد الحساب والصلاحيات • معتمدة رقمياً"
        :refNumber="'PRF-' . str_pad($user->id, 6, '0', STR_PAD_LEFT)" 
    />

    <div class="section-card">
        <div class="section-header">
            <div class="section-header-title">
                <i class="fas fa-id-card"></i>
                <span>بيانات المستخدم الأساسية والوظيفية</span>
            </div>
            <span class="section-header-badge">حساب نشط</span>
        </div>
        <table class="data-table">
            <tbody>
                <tr>
                    <th>الاسم الكامل للمستخدم</th>
                    <td style="font-size: 13px; font-weight: 800; color: #0f172a;">{{ $user->name }}</td>
                    <th>اسم الدخول (Username)</th>
                    <td style="font-family: monospace; font-weight: 800; color: #0f2942;">{{ $user->username }}</td>
                </tr>
                <tr>
                    <th>الجهة الإدارية التابع لها</th>
                    <td style="font-weight: 700;">{{ $user->internal_entity->name ?? 'المركز الرئيسي' }}</td>
                    <th>المسمى / طبيعة العمل</th>
                    <td>{{ $user->work_status ?? 'موظف نظام' }}</td>
                </tr>
                <tr>
                    <th>رقم الهاتف المسجل</th>
                    <td dir="ltr" style="text-align: right; font-family: monospace;">{{ $user->phone ?? '-' }}</td>
                    <th>تاريخ التسجيل وقيد الحساب</th>
                    <td>{{ $user->created_at ? $user->created_at->format('Y/m/d H:i') : '-' }}</td>
                </tr>
                <tr>
                    <th>الأدوار والرتب البرمجية</th>
                    <td colspan="3">
                        @foreach($user->roles as $role)
                            <span class="profile-rank-tag" style="margin-left: 6px;">{{ $role->name }}</span>
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    @if($user->roles->count() > 0)
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-key"></i>
                    <span>مصفوفة الصلاحيات الممنوحة للمستخدم</span>
                </div>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">#</th>
                        <th>الدور الوظيفي</th>
                        <th>الصلاحيات البرمجية الممنوحة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->roles as $rIdx => $role)
                        <tr>
                            <td style="text-align: center; font-weight: 700;">{{ $rIdx + 1 }}</td>
                            <td style="font-weight: 800; color: #0f172a;">{{ $role->name }}</td>
                            <td>
                                @forelse($role->permissions as $perm)
                                    <span class="status-badge status-badge-info" style="margin: 2px 4px;">{{ $perm->name_ar ?? $perm->name }}</span>
                                @empty
                                    <span style="color: #94a3b8;">لا توجد صلاحيات تفصيلية</span>
                                @endforelse
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="notes-box">
        <div class="section-header">
            <div class="section-header-title">
                <i class="fas fa-user-shield"></i>
                <span>تعليمات الأمن والسرية</span>
            </div>
        </div>
        <div class="notes-content">
            هذه الوثيقة تحتوي على بيانات اعتماد وظيفية رسمية. يُحظر مشاركة بيانات الحساب أو الصلاحيات مع أي طرف غير مخول. جميع العمليات التي تتم بواسطة هذا الحساب مسجلة في سجلات التدقيق الأمني للنظام.
        </div>
    </div>

    <x-print-footer />
</div>
@endsection
