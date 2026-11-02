@extends('layouts.print')

@section('title', isset($isList) && $isList ? 'كشف مستخدمي النظام والصلاحيات' : 'بطاقة بيانات المستخدم - ' . ($user->name ?? 'مستخدم'))

@section('content')
<div class="print-container">
    <div class="watermark-layer">وثيقة رسمية معتمدة</div>

    <x-print-header 
        :title="isset($isList) && $isList ? 'كشف مستخدمي النظام وحسابات الدخول' : 'بطاقة بيانات المستخدم وحساب النظام'"
        :subCode="isset($isList) && $isList ? 'سجل حسابات وصلاحيات النظام الإلكتروني • معتمد رسمياً' : 'بيانات اعتماد حساب مستخدم • معتمدة رسمياً'"
        :refNumber="isset($isList) && $isList ? 'USR-ALL-' . date('Ymd') : 'USR-' . str_pad($user->id ?? 1, 6, '0', STR_PAD_LEFT)" 
    />

    @if(isset($isList) && $isList)
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-users-cog"></i>
                    <span>قائمة مستخدمي النظام الإلكتروني (إجمالي: {{ count($users) }} حساب)</span>
                </div>
                <span class="section-header-badge">إدارة المستخدمين</span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">#</th>
                        <th>اسم المستخدم (الاسم الكامل)</th>
                        <th>اسم الدخول (Username)</th>
                        <th>الدور الوظيفي / الصلاحية</th>
                        <th>الجهة التابع لها</th>
                        <th>رقم الهاتف</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $item)
                        <tr>
                            <td style="text-align: center; font-weight: 700;">{{ $index + 1 }}</td>
                            <td style="font-weight: 800; color: #0f172a;">{{ $item->name }}</td>
                            <td style="font-family: monospace; font-weight: 700;">{{ $item->username }}</td>
                            <td>
                                @foreach($item->roles as $role)
                                    <span class="status-badge status-badge-info">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>{{ $item->internal_entity->name ?? 'المركز الرئيسي' }}</td>
                            <td dir="ltr" style="text-align: right; font-family: monospace;">{{ $item->phone ?? '-' }}</td>
                            <td>
                                @if($item->is_active)
                                    <span class="status-badge status-badge-success">نشط</span>
                                @else
                                    <span class="status-badge status-badge-danger">معطل</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 25px; color: #64748b;">لا توجد حسابات مستخدمين مسجلة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-user-circle"></i>
                    <span>بيانات المستخدم والحساب</span>
                </div>
                <span class="section-header-badge">سجل فردي</span>
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
                        <th>الدور والصلاحية</th>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="status-badge status-badge-info">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <th>حالة الحساب</th>
                        <td>
                            @if($user->is_active)
                                <span class="status-badge status-badge-success">نشط ومفعل</span>
                            @else
                                <span class="status-badge status-badge-danger">حساب معطل</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>الجهة التابع لها</th>
                        <td style="font-weight: 700;">{{ $user->internal_entity->name ?? 'المركز الرئيسي' }}</td>
                        <th>صفة ومسمى العمل</th>
                        <td>{{ $user->work_status ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>رقم الهاتف</th>
                        <td dir="ltr" style="text-align: right; font-family: monospace;">{{ $user->phone ?? '-' }}</td>
                        <th>تاريخ إنشاء الحساب</th>
                        <td>{{ $user->created_at ? $user->created_at->format('Y/m/d H:i') : '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

    <x-print-footer />
</div>
@endsection
