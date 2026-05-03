<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('security_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_type')->nullable(); // نوع البلاغ
            $table->string('area')->nullable(); // المنطقة
            $table->string('military_number')->nullable(); // الرقم العسكري
            $table->string('full_name')->nullable(); // الاسم الكامل واللقب
            $table->string('sub_unit')->nullable(); // الوحدة الفرعية
            $table->string('detailed_unit')->nullable(); // الوحدة التفصيلية
            $table->string('salary')->nullable(); // الراتب
            $table->text('notes')->nullable(); // ملاحظات
            $table->date('date_of_birth')->nullable(); // تاريخ الميلاد
            $table->string('original_governorate')->nullable(); // الأصل / المحافظة
            $table->string('original_directorate')->nullable(); // الأصل / المديرية
            $table->string('original_village')->nullable(); // الأصل / القرية
            $table->string('residence_governorate')->nullable(); // السكن / المحافظة
            $table->string('residence_directorate')->nullable(); // السكن / المديرية
            $table->string('residence_village')->nullable(); // السكن / القرية
            $table->string('phone')->nullable(); // الهاتف
            $table->string('affiliation')->nullable(); // الانتماء
            $table->string('work')->nullable(); // العمل / الجهة
            $table->string('unit')->nullable(); // الوحدة
            $table->text('procedures')->nullable(); // الإجراءات
            $table->string('qualification')->nullable(); // المؤهل
            $table->string('marital_status')->nullable(); // الحالة الاجتماعية
            $table->string('number_of_children')->nullable(); // عدد الأطفال
            $table->string('id_number')->nullable(); // رقم البطاقة
            $table->string('police_station')->nullable(); // القسم
            $table->string('report_name')->nullable(); // اسم البلاغ / الموضوع
            $table->date('report_date')->nullable(); // تاريخ البلاغ
            $table->string('age')->nullable(); // العمر
            $table->string('gender')->nullable(); // الجنس
            $table->string('educational_status')->nullable(); // الحالة التعليمية
            $table->string('arrest_status')->nullable(); // حالة القبض
            $table->string('job_title')->nullable(); // المسمى الوظيفي
            $table->text('positional_assets')->nullable(); // الأصول المتموضعة
            $table->text('current_activity')->nullable(); // النشاط الحالي
            $table->string('broker_name')->nullable(); // اسم الوسيط
            $table->string('broker_number')->nullable(); // رقم الوسيط
            $table->string('front_of_operation')->nullable(); // جهة العمليات
            $table->string('position_with_enemy')->nullable(); // الموقع لدى العدو
            $table->string('classification')->nullable(); // التصنيف
            $table->string('status')->nullable(); // الحالة
            $table->string('case_fate')->nullable(); // مصير القضية
            $table->string('source_of_release')->nullable(); // جهة الإطلاق
            $table->date('date_of_release')->nullable(); // تاريخ الإطلاق
            $table->string('rank')->nullable(); // الرتبة
            $table->string('designation')->nullable(); // التعيين
            $table->string('charge')->nullable(); // التهمة
            $table->date('deposit_date')->nullable(); // تاريخ الإيداع
            $table->string('detention_source')->nullable(); // جهة الحجز
            $table->date('arrest_date')->nullable(); // تاريخ القبض
            $table->string('referral_authority')->nullable(); // الجهة المحال إليها القضية
            $table->string('case_classification')->nullable(); // تصنيف القضية
            $table->text('additional_notes')->nullable(); // ملاحظات إضافية
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_reports');
    }
};
