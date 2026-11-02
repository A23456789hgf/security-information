@extends('layouts.print')

@section('title', isset($isList) && $isList ? 'كشف الهيكل التنظيمي للجهات' : 'بطاقة بيانات الجهة - ' . ($entity->name ?? 'جهة'))

@section('content')
<div class="print-container">
    <div class="watermark-layer">وثيقة رسمية معتمدة</div>

    <x-print-header 
        :title="isset($isList) && $isList ? 'كشف الهيكل التنظيمي وسجل الجهات' : 'بطاقة بيانات وقيد الجهة الرسمية'"
        :subCode="isset($isList) && $isList ? 'سجل الجهات الإدارية والوحدات التابعة • معتمد رسمياً' : 'بطاقة تعريف جهة إدارية • معتمدة رسمياً'"
        :refNumber="isset($isList) && $isList ? 'ENT-ALL-' . date('Ymd') : 'ENT-' . str_pad($entity->id ?? 1, 6, '0', STR_PAD_LEFT)" 
    />

    @if(isset($isList) && $isList)
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-sitemap"></i>
                    <span>قائمة الجهات والوحدات الإدارية (إجمالي: {{ count($entities) }} جهة)</span>
                </div>
                <span class="section-header-badge">الهيكل الإداري</span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">#</th>
                        <th>اسم الجهة الإدارية</th>
                        <th>الجهة الأم (التبعية)</th>
                        <th>المحافظة والمديرية</th>
                        <th>عدد المستخدمين التابعين</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entities as $index => $item)
                        <tr>
                            <td style="text-align: center; font-weight: 700;">{{ $index + 1 }}</td>
                            <td style="font-weight: 800; color: #0f172a;">{{ $item->name }}</td>
                            <td>
                                @if($item->parent)
                                    <span style="font-weight: 600; color: #475569;">{{ $item->parent->name }}</span>
                                @else
                                    <span class="status-badge status-badge-info">جهة رئيسية</span>
                                @endif
                            </td>
                            <td>{{ $item->governorate->name ?? '-' }} / {{ $item->directorate->name ?? '-' }}</td>
                            <td style="font-weight: 700; color: #0f2942;">{{ $item->users ? $item->users->count() : 0 }} مستخدم</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 25px; color: #64748b;">لا توجد جهات مسجلة للطباعة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-building"></i>
                    <span>بيانات الجهة الأساسية والموقع</span>
                </div>
                <span class="section-header-badge">سجل معتمد</span>
            </div>
            <table class="data-table">
                <tbody>
                    <tr>
                        <th>اسم الجهة الإدارية</th>
                        <td style="font-size: 13px; font-weight: 800; color: #0f172a;">{{ $entity->name }}</td>
                        <th>المستوى / التبعية</th>
                        <td>
                            @if($entity->parent)
                                تابعة لـ: <strong>{{ $entity->parent->name }}</strong>
                            @else
                                <span class="status-badge status-badge-info">جهة رئيسية عليا</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>المحافظة والمديرية</th>
                        <td>{{ $entity->governorate->name ?? '-' }} / {{ $entity->directorate->name ?? '-' }}</td>
                        <th>تاريخ الإنشاء والقيد</th>
                        <td>{{ $entity->created_at ? $entity->created_at->format('Y/m/d') : '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($entity->children && $entity->children->count() > 0)
            <div class="section-card">
                <div class="section-header">
                    <div class="section-header-title">
                        <i class="fas fa-network-wired"></i>
                        <span>الجهات والوحدات التابعة لها فرعياً</span>
                    </div>
                    <span class="section-header-badge">{{ $entity->children->count() }} جهة فرعية</span>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align: center;">#</th>
                            <th>اسم الجهة التابعة</th>
                            <th>الموقع الجغرافي</th>
                            <th>عدد المستخدمين</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entity->children as $cIndex => $child)
                            <tr>
                                <td style="text-align: center; font-weight: 700;">{{ $cIndex + 1 }}</td>
                                <td style="font-weight: 700;">{{ $child->name }}</td>
                                <td>{{ $child->governorate->name ?? '-' }} / {{ $child->directorate->name ?? '-' }}</td>
                                <td>{{ $child->users ? $child->users->count() : 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if($entity->users && $entity->users->count() > 0)
            <div class="section-card">
                <div class="section-header">
                    <div class="section-header-title">
                        <i class="fas fa-users-cog"></i>
                        <span>المستخدمين والموظفين المنتسبين للجهة</span>
                    </div>
                    <span class="section-header-badge">{{ $entity->users->count() }} مستخدم</span>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align: center;">#</th>
                            <th>الاسم الكامل</th>
                            <th>اسم المستخدم</th>
                            <th>رقم الهاتف</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entity->users as $uIndex => $user)
                            <tr>
                                <td style="text-align: center; font-weight: 700;">{{ $uIndex + 1 }}</td>
                                <td style="font-weight: 700;">{{ $user->name }}</td>
                                <td style="font-family: monospace;">{{ $user->username }}</td>
                                <td dir="ltr" style="text-align: right; font-family: monospace;">{{ $user->phone ?? '-' }}</td>
                                <td>
                                    @if($user->is_active)
                                        <span class="status-badge status-badge-success">نشط</span>
                                    @else
                                        <span class="status-badge status-badge-danger">معطل</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif

    <x-print-footer />
</div>
@endsection
