<!-- Add Column Modal -->
<div class="modal-overlay" id="addColumnModal">
    <div class="modal-box">
        <h4 class="mb-4"><i class="fas fa-columns me-2 text-primary"></i>إضافة عمود جديد للمرجعية</h4>
        <div class="mb-3">
            <label class="form-label">اسم العمود</label>
            <input type="text" class="form-control" id="newColumnName" placeholder="مثلاً: ملاحظات">
        </div>
        <div class="d-flex gap-2 justify-content-end">
            <button class="btn btn-secondary" onclick="closeModal('addColumnModal')">إلغاء</button>
            <button class="btn btn-primary" onclick="addColumn()">إضافة</button>
        </div>
    </div>
</div>

<!-- Matching & Discrepancy Progress Modal -->
<div class="modal-overlay" id="progressModal">
    <div class="modal-box" style="max-width: 500px; text-align: center; padding: 30px;">
        <h4 class="mb-3" style="color: #1a1c2e; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 8px;">
            <i class="fas fa-sync fa-spin text-primary" id="matchingSpinIcon"></i>
            <span>تحليل ومطابقة البيانات</span>
        </h4>
        <p class="text-muted small mb-4" id="matchingProgressSubtitle">يرجى الانتظار، يتم الآن معالجة الملفات واحتساب التباين...</p>
        
        <!-- Progress Steps checklist -->
        <div class="progress-steps text-start mb-4">
            <div class="step-item active" id="step-read">
                <i class="fas fa-circle-notch fa-spin text-primary" id="icon-read"></i>
                <span>قراءة وتحميل ملف البيانات الفرعية</span>
            </div>
            <div class="step-item" style="opacity: 0.5" id="step-init">
                <i class="far fa-circle" id="icon-init"></i>
                <span>تهيئة خوارزميات الفرز والمطابقة</span>
            </div>
            <div class="step-item" style="opacity: 0.5" id="step-compare">
                <i class="far fa-circle" id="icon-compare"></i>
                <span>مقارنة السجلات واحتساب الفروقات</span>
            </div>
            <div class="step-item" style="opacity: 0.5" id="step-results">
                <i class="far fa-circle" id="icon-results"></i>
                <span>حصر التباين وتصنيف مجموعات التطابق</span>
            </div>
        </div>

        <!-- Animated Progress Bar -->
        <div class="progress-bar-container" style="background: #eef0f6; border-radius: 20px; height: 12px; position: relative; overflow: hidden; margin-bottom: 15px;">
            <div class="progress-bar-fill" id="progressBarFill" style="background: linear-gradient(90deg, #4cc9f0, #1a1c2e); height: 100%; width: 0%; transition: width 0.4s ease;"></div>
        </div>
        <div class="d-flex justify-content-between small text-muted">
            <span id="progressPercent" style="font-weight: 700;">0%</span>
            <span id="progressTime">الوقت المقدر: ثوانٍ معدودة</span>
        </div>
    </div>
</div>
