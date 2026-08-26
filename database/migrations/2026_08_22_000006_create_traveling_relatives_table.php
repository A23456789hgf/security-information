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
        Schema::create('traveling_relatives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_data_id')->constrained('personal_data')->cascadeOnDelete();
            $table->string('full_name'); // الاسم
            $table->unsignedInteger('age')->nullable(); // العمر
            $table->foreignId('kinship_id')->nullable()->constrained('kinships')->nullOnDelete(); // صلة القرابة
            $table->string('travel_reason')->nullable(); // سبب السفر
            $table->string('current_job')->nullable(); // العمل الحالي
            $table->string('workplace')->nullable(); // مكانه
            $table->string('affiliated_entity')->nullable(); // الجهة التابع لها
            $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete(); // الدولة
            $table->foreignId('political_affiliation_id')->nullable()->constrained('political_affiliations')->nullOnDelete(); // الانتماء السياسي
            $table->foreignId('ideological_affiliation_id')->nullable()->constrained('ideological_affiliations')->nullOnDelete(); // الانتماء العقائدي
            $table->text('march_impression')->nullable(); // انطباعه عن المسيرة
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traveling_relatives');
    }
};
