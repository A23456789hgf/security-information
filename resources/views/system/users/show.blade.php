@extends('layouts.app')

@section('title', 'تفاصيل المستخدم')
@section('page_title', 'ملف المستخدم: ' . $user->name)

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <a href="{{ route('system.users.index') }}" class="btn btn-outline-secondary rounded-pill px-4 btn-sm">
        <i class="fas fa-arrow-right me-1"></i>العودة للقائمة
    </a>
    <div class="gap-2 d-flex">
        <a href="{{ route('system.users.edit', $user) }}" class="btn btn-warning rounded-pill px-4 btn-sm shadow-sm">
            <i class="fas fa-edit me-1"></i>تعديل البيانات
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- بطاقة التعريف الأساسية --}}
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 rounded-4 text-center p-4 h-100">
            <div class="mx-auto mb-3 rounded-circle bg-primary bg-opacity-10 p-4 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                <i class="fas fa-user text-primary fa-4x"></i>
            </div>
            <h4 class="fw-bold text-dark mb-1">{{ $user->name }}</h4>
            <div class="mb-3">
                @foreach($user->roles as $role)
                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3">{{ $role->name }}</span>
                @endforeach
            </div>
            <hr class="opacity-50">
            <div class="text-right">
                <p class="mb-1 text-muted small"><i class="fas fa-envelope me-2"></i>البريد الإلكتروني</p>
                <p class="fw-bold text-dark">{{ $user->email }}</p>
                
                <p class="mb-1 text-muted small mt-3"><i class="fas fa-calendar-alt me-2"></i>تاريخ الانضمام</p>
                <p class="fw-bold text-dark">{{ $user->created_at->format('Y-m-d') }}</p>
            </div>
        </div>
    </div>

    {{-- بطاقة الصلاحيات والارتباطات --}}
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-4 p-4 h-100">
            <h5 class="fw-bold mb-4 border-bottom pb-2"><i class="fas fa-id-card-clip me-2 text-primary"></i>معلومات إضافية</h5>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="p-3 rounded-3 bg-light">
                        <label class="text-muted small d-block mb-1">الجهة التابع لها</label>
                        @if($user->internal_entity)
                            <div class="d-flex align-items-center">
                                <i class="fas fa-building text-primary me-2"></i>
                                <span class="fw-bold">{{ $user->internal_entity->name }}</span>
                            </div>
                        @else
                            <span class="text-muted italic">لا يوجد جهة محددة</span>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 rounded-3 bg-light">
                        <label class="text-muted small d-block mb-1">حالة الحساب</label>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <span class="fw-bold">نشط</span>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <h6 class="fw-bold mt-2 mb-3">الصلاحيات الممنوحة لهذا الدور:</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @forelse($user->getAllPermissions() as $permission)
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">
                                {{ $permission->name }}
                            </span>
                        @empty
                            <p class="text-muted small italic">لا توجد صلاحيات فردية مخصصة</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
