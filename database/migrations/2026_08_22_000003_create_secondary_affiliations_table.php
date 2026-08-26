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
        Schema::create('secondary_affiliations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('secondary_data_id')->constrained('secondary_data')->cascadeOnDelete();
            $table->string('party')->nullable(); // الجهة
            $table->foreignId('ideological_affiliation_id')->nullable()->constrained('ideological_affiliations')->nullOnDelete(); // الانتماء العقائدي
            $table->foreignId('political_affiliation_id')->nullable()->constrained('political_affiliations')->nullOnDelete(); // الانتماء السياسي
            $table->date('affiliation_date')->nullable(); // تاريخ الارتباط
            $table->string('duration')->nullable(); // مدة الانتماء
            $table->string('workplace')->nullable(); // مكان العمل
            $table->string('job_type')->nullable(); // نوع العمل
            $table->string('direct_supervisor')->nullable(); // المسؤول المباشر
            $table->date('leaving_date')->nullable(); // تاريخ الترك
            $table->text('leaving_reason')->nullable(); // سبب الترك
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secondary_affiliations');
    }
};
