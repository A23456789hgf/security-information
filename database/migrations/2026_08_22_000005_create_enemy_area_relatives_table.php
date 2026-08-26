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
        Schema::create('enemy_area_relatives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_data_id')->constrained('personal_data')->cascadeOnDelete();
            $table->string('full_name'); // الاسم
            $table->foreignId('kinship_id')->nullable()->constrained('kinships')->nullOnDelete(); // صلة القرابة
            $table->string('residence_place')->nullable(); // مكان السكن
            $table->string('job')->nullable(); // العمل
            $table->foreignId('political_affiliation_id')->nullable()->constrained('political_affiliations')->nullOnDelete(); // الانتماء السياسي
            $table->foreignId('ideological_affiliation_id')->nullable()->constrained('ideological_affiliations')->nullOnDelete(); // الانتماء العقائدي
            $table->string('communication_method')->nullable(); // طريقة التواصل
            $table->string('relationship')->nullable(); // العلاقة
            $table->foreignId('financial_status_id')->nullable()->constrained('financial_statuses')->nullOnDelete(); // الحالة المادية
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enemy_area_relatives');
    }
};
