@extends('layouts.app')

@section('title', 'مطابقة الحقول: ' . $page->name)
@section('content')
<div class="container-fluid mt-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">
                <i class="fas fa-project-diagram text-primary me-2"></i>مطابقة أعمدة الملف: {{ $page->name }}
            </h2>
            <p class="text-muted">اختر العمود المناسب من ملف الرفع لكل حقل من حقول الصفحة</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('system.dynamic-data.import', $page->slug) }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fas fa-arrow-right me-1"></i>تغيير الملف
            </a>
        </div>
    </div>

    <form action="{{ route('system.dynamic-data.import-store', $page->slug) }}" method="POST">
        @csrf
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0 fw-bold small"><i class="fas fa-link me-2"></i>خريطة مطابقة الحقول</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small">
                            <tr>
                                <th class="ps-4">حقل الصفحة الرسمي</th>
                                <th>العمود المختار من الملف</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($page->fields as $field)
                            <tr>
                                <td class="ps-4 fw-bold text-dark">{{ $field->label }}</td>
                                <td>
                                    <select name="mapping[{{ $field->id }}]" class="form-select rounded-pill border-0 bg-light">
                                        <option value="">-- تجاهل هذا الحقل --</option>
                                        @foreach($headers as $index => $header)
                                            <option value="{{ $index }}">{{ $header }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-light py-3 border-0">
                <h6 class="mb-0 fw-bold text-muted"><i class="fas fa-eye me-2"></i>معاينة البيانات (أول 5 صفوف)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0 small">
                        <thead>
                            <tr class="bg-light">
                                @foreach($headers as $header)
                                    <th class="py-2">{{ $header }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($preview as $row)
                            <tr>
                                @foreach($row as $cell)
                                    <td>{{ $cell }}</td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-3 mb-5">
            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow">
                بدء عملية الاستيراد <i class="fas fa-play ms-2"></i>
            </button>
        </div>
    </form>
</div>
@endsection
