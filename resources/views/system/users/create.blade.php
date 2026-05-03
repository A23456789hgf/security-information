@extends('layouts.app')

@section('title', 'إضافة مستخدم جديد')
@section('page_title', 'إنشاء حساب مستخدم جديد')

@section('page_actions')
    <a href="{{ route('system.users.index') }}" class="btn btn-sm btn-light shadow-sm rounded-pill px-4">
        <i class="fas fa-arrow-right me-2 text-primary"></i>العودة للقائمة
    </a>
@endsection

@section('content')
    <div class="container-fluid pb-5">
        <form action="{{ route('system.users.store') }}" method="POST">
            @csrf
            <div class="row justify-content-center">
                <div class="col-lg-10">

                    <div class="card border-0 shadow-sm mb-4 br-15">
                        <div class="card-body p-4">
                            <h5 class="section-title mb-4"><i class="fas fa-user-circle me-2"></i>المعلومات الأساسية</h5>
                            <div class="row g-4">
                                {{-- الاسم الكامل --}}
                                <div class="col-md-12">
                                    <label class="form-label fw-bold small">الاسم الكامل <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="fas fa-signature text-muted"></i></span>
                                        <input type="text" name="name"
                                            class="form-control bg-light border-0 @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}" required placeholder="أدخل الاسم الثلاثي">
                                    </div>
                                    @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                {{-- اسم المستخدم ورقم الهاتف --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">اسم المستخدم <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="fas fa-user-tag text-muted"></i></span>
                                        <input type="text" name="username"
                                            class="form-control bg-light border-0 @error('username') is-invalid @enderror"
                                            value="{{ old('username') }}" required placeholder="username">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">رقم الهاتف</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="fas fa-phone text-muted"></i></span>
                                        <input type="text" name="phone" class="form-control bg-light border-0"
                                            value="{{ old('phone') }}" placeholder="09xxxxxxxx">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4 br-15">
                        <div class="card-body p-4">
                            <h5 class="section-title mb-4"><i class="fas fa-shield-alt me-2"></i>الأمان والصلاحيات</h5>
                            <div class="row g-4">
                                {{-- كلمة المرور --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">كلمة المرور <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="fas fa-key text-muted"></i></span>
                                        <input type="password" name="password"
                                            class="form-control bg-light border-0 @error('password') is-invalid @enderror"
                                            required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">تأكيد كلمة المرور <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="fas fa-check-double text-muted"></i></span>
                                        <input type="password" name="password_confirmation"
                                            class="form-control bg-light border-0" required>
                                    </div>
                                </div>

                                {{-- الدور والجهة --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">الدور / الصلاحية <span
                                            class="text-danger">*</span></label>
                                    <select name="role_id"
                                        class="form-select bg-light border-0 py-2 @error('role_id') is-invalid @enderror"
                                        required>
                                        <option value="">اختر الدور...</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">الجهة التابع لها</label>
                                    <select name="internal_entity_id" class="form-select bg-light border-0 py-2">
                                        <option value="">اختر الجهة...</option>
                                        @foreach($entities as $entity)
                                            <option value="{{ $entity->id }}" {{ old('internal_entity_id') == $entity->id ? 'selected' : '' }}>{{ $entity->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="form-check form-switch custom-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                    checked>
                                <label class="form-check-label fw-bold text-secondary ms-2" for="is_active">تفعيل الحساب
                                    فوراً</label>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <button type="submit" class="btn btn-primary px-5 py-2 shadow-sm rounded-pill fw-bold">
                                <i class="fas fa-save me-2"></i> حفظ بيانات المستخدم
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>

    <style>
        /* تحسينات عامة */
        :root {
            --primary-color: #4e73df;
            --section-bg: #f8f9fc;
        }

        .br-15 {
            border-radius: 15px !important;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-color);
            border-right: 4px solid var(--primary-color);
            padding-right: 15px;
        }

        /* تحسين شكل المدخلات */
        .form-control,
        .form-select,
        .input-group-text {
            border-radius: 10px !important;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background-color: #fff !important;
            box-shadow: 0 0 0 0.25 darkgrey;
            border: 1px solid var(--primary-color) !important;
        }

        /* زر التبديل (Switch) */
        .custom-switch .form-check-input {
            width: 3rem;
            height: 1.5rem;
            cursor: pointer;
        }

        /* استجابة الهواتف */
        @media (max-width: 768px) {
            .btn-primary {
                width: 100%;
            }
        }
    </style>
@endsection