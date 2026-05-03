@section('title', 'باني الصفحات: ' . $page->name)
@section('content')
<div class="container-fluid mt-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark">
                <i class="fas fa-tools text-primary me-2"></i>باني الصفحات: <span class="text-primary">{{ $page->name }}</span>
            </h2>
            <p class="text-muted">تحكم في إعدادات الصفحة وحقولها المخصصة</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('system.pages.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fas fa-arrow-right me-1"></i> العودة لقائمة الصفحات
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- إعدادات الصفحة -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-gradient-primary text-white py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0 fw-bold small uppercase"><i class="fas fa-cog me-2"></i>الإعدادات العامة</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('system.pages.update', $page) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">اسم الصفحة</label>
                            <input type="text" name="name" class="form-control rounded-3" value="{{ $page->name }}" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">الالقسم الإداري <span class="text-danger">*</span></label>
                            <select name="section_id" class="form-control rounded-3 shadow-sm" required>
                                <option value="" disabled>اختر القسم الإداري</option>
                                @foreach($sections as $sec)
                                    <option value="{{ $sec->id }}" {{ old('section_id', $page->section_id) == $sec->id ? 'selected' : '' }}>
                                        {{ $sec->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-1">يستخدم لتجميع الصفحات في القائمة الجانبية</small>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">الرابط (Slug)</label>
                            <input type="text" class="form-control rounded-3 bg-light" value="{{ $page->slug }}" readonly disabled>
                            <small class="text-muted small">يتم توليد الرابط تلقائياً ولا يمكن تعديله يدوياً.</small>
                        </div>
                        <div class="mb-4">
                            <div class="form-check form-switch p-0 ms-4">
                                <input class="form-check-input ms-0 me-2" type="checkbox" name="is_unique_id" value="1" id="isUniqueId" {{ $page->is_unique_id ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="isUniqueId">معرف فريد (Unique ID)</label>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="form-check form-switch p-0 ms-4">
                                <input class="form-check-input ms-0 me-2" type="checkbox" name="is_active" value="1" id="isActive" {{ $page->is_active ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="isActive">نشطة (تظهر للمستخدمين)</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm">
                            <i class="fas fa-save me-1"></i> تحديث الإعدادات
                        </button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4 bg-light border-start border-4 border-info">
                <div class="card-body">
                    <h6><i class="fas fa-info-circle text-info me-2"></i>معلومات</h6>
                    <p class="small text-muted mb-0">تأكد من أن أسماء الحقول التدخلية (Database Name) فريدة داخل نفس الصفحة.</p>
                </div>
            </div>
        </div>

        <!-- إدارة الحقول -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-list-ul text-primary me-2"></i>هيكلية الصفحة (الحقول)</h5>
                    <button type="button" class="btn btn-success btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#addFieldModal">
                        <i class="fas fa-plus me-1"></i> إضافة حقل جديد
                    </button>
                </div>
                <div class="card-body p-0">
                    @if($page->fields->isEmpty())
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-layer-group fa-3x text-light"></i>
                            </div>
                            <h5 class="text-muted">لا توجد حقول حتى الآن</h5>
                            <p class="text-muted small">ابدأ بإضافة أول حقل لبناء هذه الصفحة</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted small">
                                    <tr>
                                        <th class="ps-4">اسم الحقل</th>
                                        <th>النوع</th>
                                        <th>الاسم التقني</th>
                                        <th class="text-center">إلزامي</th>
                                        <th class="text-center pe-4">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($page->fields as $field)
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark">{{ $field->label }}</td>
                                        <td>
                                            @php
                                                $icons = [
                                                    'text' => 'fa-font text-primary',
                                                    'number' => 'fa-hashtag text-success',
                                                    'textarea' => 'fa-align-left text-info',
                                                    'select' => 'fa-list text-warning',
                                                    'date' => 'fa-calendar-alt text-danger',
                                                    'datetime' => 'fa-clock text-danger',
                                                    'checkbox' => 'fa-check-square text-success',
                                                    'link' => 'fa-link text-primary',
                                                    'table' => 'fa-table text-dark',
                                                    'file' => 'fa-paperclip text-secondary',
                                                    'image' => 'fa-image text-secondary'
                                                ];
                                                $labels = [
                                                    'text' => 'نص قصير',
                                                    'number' => 'رقمي',
                                                    'textarea' => 'نص طويل',
                                                    'select' => 'قائمة',
                                                    'date' => 'تاريخ',
                                                    'datetime' => 'تاريخ ووقت',
                                                    'checkbox' => 'نعم/لا',
                                                    'link' => 'ارتباط',
                                                    'table' => 'جدول فرعي',
                                                    'file' => 'ملف',
                                                    'image' => 'صورة'
                                                ];
                                            @endphp
                                            <span class="small">
                                                <i class="fas {{ $icons[$field->type] ?? 'fa-circle' }} me-1 opacity-75"></i> {{ $labels[$field->type] ?? $field->type }}
                                            </span>
                                        </td>
                                        <td><code class="small">{{ $field->name }}</code></td>
                                        <td class="text-center">
                                            @if($field->is_required)
                                                <i class="fas fa-check text-success"></i>
                                            @else
                                                <i class="fas fa-times text-light"></i>
                                            @endif
                                        </td>
                                        <td class="text-center pe-4">
                                            <form action="{{ route('system.fields.destroy', $field) }}" method="POST" class="d-inline" onsubmit="return confirm('هل تريد حذف هذا الحقل؟ سيؤدي ذلك لحذف البيانات المرتبطة به في السجلات.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle shadow-sm" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-trash small"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal التحسين -->
<div class="modal fade" id="addFieldModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('system.pages.fields.store', $page) }}" method="POST" class="w-100">
            @csrf
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold"><i class="fas fa-plus-square text-success me-2"></i>إضافة حقل جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">اسم الحقل (يظهر للمستخدم)</label>
                        <input type="text" name="label" class="form-control rounded-3" required placeholder="مثلاً: تاريخ الواقعة">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">نوع البيانات</label>
                        <select name="type" class="form-select rounded-3 field-type-select-modal" required>
                            <option value="text">بيانات (نص قصير)</option>
                            <option value="textarea">نص (نص طويل)</option>
                            <option value="date">تاريخ</option>
                            <option value="datetime">تاريخ ووقت</option>
                            <option value="number">أرقام (Int/Float/Currency)</option>
                            <option value="checkbox">تحقق (نعم/لا)</option>
                            <option value="select">قائمة منسدلة (خيارات)</option>
                            <option value="link">ارتباط بصفحة أخرى</option>
                            <option value="table">جدول فرعي (Child Table)</option>
                            <option value="file">مرفقات / صور</option>
                        </select>
                    </div>
                    <div class="mb-3 extra-settings-modal">
                        <div class="options-group" style="display:none;">
                            <label class="form-label fw-bold small text-info">خيارات القائمة (فصل بينها بفاصلة)</label>
                            <textarea name="options" class="form-control rounded-3" rows="3" placeholder='عسكري, مدني, آخر'></textarea>
                        </div>
                        
                        <div class="link-group" style="display:none;">
                            <label class="form-label fw-bold small text-primary">الصفحة المستهدفة</label>
                            <select name="metadata[target_page_id]" class="form-select rounded-3">
                                <option value="" disabled selected>اختر الصفحة</option>
                                {{-- Here we need $pages, but if not passed we might need a workaround or ensure it is passed --}}
                                @foreach(App\Models\Page::where('is_active', true)->get() as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="table-group" style="display:none;">
                            <label class="form-label fw-bold small text-dark">أعمدة الجدول (فصل بينها بفاصلة)</label>
                            <input type="text" name="metadata[table_columns]" class="form-control rounded-3" placeholder="الاسم, السن, العنوان">
                        </div>
                    </div>
                    <div class="form-check form-switch p-0 ms-4">
                        <input class="form-check-input ms-0 me-2" type="checkbox" name="is_required" value="1" id="isRequired">
                        <label class="form-check-label fw-bold small" for="isRequired">هذا الحقل إلزامي</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-toggle="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">إضافة الحقل</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.querySelector('.field-type-select-modal');
        const extraSettings = document.querySelector('.extra-settings-modal');
        const optionsGroup = extraSettings.querySelector('.options-group');
        const linkGroup = extraSettings.querySelector('.link-group');
        const tableGroup = extraSettings.querySelector('.table-group');

        typeSelect.addEventListener('change', function() {
            // Hide all
            optionsGroup.style.display = 'none';
            linkGroup.style.display = 'none';
            tableGroup.style.display = 'none';

            if (this.value === 'select') {
                optionsGroup.style.display = 'block';
            } else if (this.value === 'link') {
                linkGroup.style.display = 'block';
            } else if (this.value === 'table') {
                tableGroup.style.display = 'block';
            }
        });
    });
</script>
@endpush
@endsection
