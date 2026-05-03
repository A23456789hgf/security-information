@extends('layouts.app')

@section('title', 'تفاصيل الجهة')
@section('page_title', 'ملف الجهة: ' . $entity->name)

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <a href="{{ route('system.entities.index') }}" class="btn btn-outline-secondary rounded-pill px-4 btn-sm">
        <i class="fas fa-arrow-right me-1"></i>العودة للقائمة
    </a>
    <div class="gap-2 d-flex">
        <a href="{{ route('system.entities.edit', $entity) }}" class="btn btn-warning rounded-pill px-4 btn-sm shadow-sm">
            <i class="fas fa-edit me-1"></i>تعديل الجهة
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- تفاصيل الهيكل --}}
    <div class="col-lg-5">
        <div class="card shadow-sm border-0 rounded-4 p-4 h-100">
            <h5 class="fw-bold mb-4 border-bottom pb-2"><i class="fas fa-network-wired me-2 text-primary"></i>هيكل الجهة</h5>
            
            <div class="mb-4 text-center py-3 bg-light rounded-3">
                <div class="text-muted small mb-1">الجهة الأعلى</div>
                <div class="fw-bold">
                    @if($entity->parent)
                        <i class="fas fa-chevron-up text-primary me-1"></i>{{ $entity->parent->name }}
                    @else
                        <span class="badge bg-secondary rounded-pill">جهة جذرية (رئيسية)</span>
                    @endif
                </div>
            </div>

            <div class="text-center py-4 rounded-4 border-2 border-primary border-dashed mb-4" style="border: 2px dashed rgba(56, 189, 248, 0.4); background: rgba(56, 189, 248, 0.02);">
                <i class="fas fa-building fa-3x text-primary mb-2"></i>
                <h4 class="fw-bold mb-0">{{ $entity->name }}</h4>
            </div>

            <div class="mt-4">
                <h6 class="fw-bold mb-3 small text-muted">الجهات التابعة (الأبناء):</h6>
                <div class="list-group list-group-flush rounded-3 border">
                    @forelse($entity->children as $child)
                        <a href="{{ route('system.entities.show', $child) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-level-down-alt me-2 text-muted"></i>{{ $child->name }}</span>
                            <i class="fas fa-eye small text-info"></i>
                        </a>
                    @empty
                        <div class="list-group-item text-center text-muted small py-3">لا توجد جهات تابعة</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- الموقع والمستخدمين --}}
    <div class="col-lg-7">
        <div class="card shadow-sm border-0 rounded-4 p-4 mb-4">
            <h5 class="fw-bold mb-4 border-bottom pb-2"><i class="fas fa-map-marked-alt me-2 text-primary"></i>الموقع الإداري</h5>
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted small mb-1">المحافظة</p>
                    <p class="fw-bold"><i class="fas fa-map-marker-alt text-danger me-2"></i>{{ $entity->governorate->name ?? 'غير محدد' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted small mb-1">المديرية</p>
                    <p class="fw-bold"><i class="fas fa-map-pin text-info me-2"></i>{{ $entity->directorate->name ?? 'غير محدد' }}</p>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4 p-4">
            <h5 class="fw-bold mb-4 border-bottom pb-2"><i class="fas fa-users-viewfinder me-2 text-primary"></i>الموظفون المرتبطون</h5>
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th class="text-center">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entity->users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td class="small">{{ $user->email }}</td>
                            <td class="text-center">
                                <a href="{{ route('system.users.show', $user) }}" class="btn btn-link btn-sm p-0"><i class="fas fa-external-link-alt"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3 small">لا يوجد مستخدمون مرتبطون بهذه الجهة</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
