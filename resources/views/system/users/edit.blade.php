@extends('layouts.app')

@section('title', 'تعديل بيانات المستخدم')
@section('page_title', 'تعديل المستخدم: ' . $user->name)

@section('page_actions')
    <a href="{{ route('system.users.index') }}" class="btn btn-sm btn-light shadow-sm rounded-pill px-4">
        <i class="fas fa-arrow-right me-2 text-primary"></i>العودة للقائمة
    </a>
@endsection

@section('content')
    <div class="container-fluid pb-5">
        <form action="{{ route('system.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row justify-content-center">
                <div class="col-lg-10">

                    {{-- القسم الأول: المعلومات الشخصية --}}
                    <div class="card border-0 shadow-sm mb-4 br-15">
                        <div class="card-body p-4">
                            <h5 class="section-title mb-4"><i class="fas fa-id-card me-2"></i>المعلومات الأساسية</h5>
                            <div class="row g-4">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold small">الاسم الكامل <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="fas fa-signature text-muted"></i></span>
                                        <input type="text" name="name"
                                            class="form-control bg-light border-0 @error('name') is-invalid @enderror"
                                            value="{{ old('name', $user->name) }}" required>
                                    </div>
                                    @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">اسم المستخدم <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="fas fa-user-tag text-muted"></i></span>
                                        <input type="text" name="username"
                                            class="form-control bg-light border-0 @error('username') is-invalid @enderror"
                                            value="{{ old('username', $user->username) }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">رقم الهاتف</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="fas fa-phone text-muted"></i></span>
                                        <input type="text" name="phone" class="form-control bg-light border-0"
                                            value="{{ old('phone', $user->phone) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- القسم الثاني: الأمان (تغيير كلمة المرور) --}}
                    <div class="card border-0 shadow-sm mb-4 br-15">
                        <div class="card-body p-4">
                            <h5 class="section-title mb-3"><i class="fas fa-lock me-2"></i>تغيير كلمة المرور</h5>

                            <div class="alert alert-info border-0 shadow-sm br-10 mb-4 d-flex align-items-center">
                                <i class="fas fa-info-circle me-3 fs-4"></i>
                                <span class="small">اترك حقول كلمة المرور فارغة إذا كنت لا ترغب في تغييرها.</span>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">كلمة المرور الجديدة</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="fas fa-key text-muted"></i></span>
                                        <input type="password" name="password"
                                            class="form-control bg-light border-0 @error('password') is-invalid @enderror"
                                            placeholder="أدخل كلمة مرور جديدة">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">تأكيد كلمة المرور</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="fas fa-check-double text-muted"></i></span>
                                        <input type="password" name="password_confirmation"
                                            class="form-control bg-light border-0" placeholder="أعد كتابة كلمة المرور">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- القسم الثالث: الصلاحيات والعمل --}}
                    <div class="card border-0 shadow-sm mb-4 br-15">
                        <div class="card-body p-4">
                            <h5 class="section-title mb-4"><i class="fas fa-user-shield me-2"></i>الصلاحيات والحالة</h5>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">الدور / الصلاحية <span
                                            class="text-danger">*</span></label>
                                    <select name="role_id"
                                        class="form-select bg-light border-0 py-2 @error('role_id') is-invalid @enderror"
                                        required>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('role_id', $userRole) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">الجهة التابع لها</label>
                                    <select name="internal_entity_id" class="form-select bg-light border-0 py-2">
                                        <option value="">لا تتبع لجهة محددة</option>
                                        @foreach($entities as $entity)
                                            <option value="{{ $entity->id }}" {{ old('internal_entity_id', $user->internal_entity_id) == $entity->id ? 'selected' : '' }}>{{ $entity->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-bold small">حالة العمل</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="fas fa-briefcase text-muted"></i></span>
                                        <input type="text" name="work_status" class="form-control bg-light border-0"
                                            value="{{ old('work_status', $user->work_status) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- التفعيل والزر النهائي --}}
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="form-check form-switch custom-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ $user->is_active ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-secondary ms-2" for="is_active">حساب المستخدم
                                    نشط</label>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <button type="submit" class="btn btn-success px-5 py-2 shadow-sm rounded-pill fw-bold">
                                <i class="fas fa-sync-alt me-2"></i> تحديث البيانات
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>

    <style>
        :root {
            --primary-color: #4e73df;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
        }

        .br-15 {
            border-radius: 15px !important;
        }

        .br-10 {
            border-radius: 10px !important;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-color);
            border-right: 4px solid var(--primary-color);
            padding-right: 15px;
        }

        .form-control,
        .form-select,
        .input-group-text {
            border-radius: 10px !important;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background-color: #fff !important;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.1);
            border: 1px solid var(--primary-color) !important;
        }

        .custom-switch .form-check-input {
            width: 3.2rem;
            height: 1.6rem;
            cursor: pointer;
        }

        .alert-info {
            background-color: #f0f7ff;
            color: #0c5460;
        }

        .btn-success {
            background-color: var(--success-color);
            border: none;
        }

        .btn-success:hover {
            background-color: #17a673;
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .btn-success {
                width: 100%;
            }
        }
    </style>
@endsection