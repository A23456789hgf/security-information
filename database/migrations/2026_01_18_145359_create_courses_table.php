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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_data_id')->constrained('personal_data')->cascadeOnDelete();
            $table->foreignId('course_type_id')->constrained('course_types')->cascadeOnDelete();
            $table->date('course_date_gregorian');
            $table->string('course_date_hijri')->nullable();
            $table->string('course_duration')->nullable();
            $table->foreignId('sending_entity_id')->nullable()->constrained('filling_agencies')->cascadeOnDelete();
            $table->string('course_administrator')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
