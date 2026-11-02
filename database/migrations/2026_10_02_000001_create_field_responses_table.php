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
        Schema::create('field_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_data_id')->constrained('personal_data')->cascadeOnDelete();
            $table->text('field_response'); // الرد الميداني
            $table->date('response_date')->nullable(); // تاريخ الرد
            $table->string('source')->nullable(); // المصدر
            $table->string('source_job')->nullable(); // عمل المصدر
            $table->text('notes')->nullable(); // الملاحظات
            $table->foreignId('internal_entity_id')->nullable()->constrained('internal_entities')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_responses');
    }
};
