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
        Schema::create('previous_jihadist_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('secondary_data_id')->constrained('secondary_data')->cascadeOnDelete();
            $table->text('activity')->nullable(); // النشاط
            $table->date('activity_date')->nullable(); // التاريخ
            $table->string('duration')->nullable(); // المدة الزمنية
            $table->string('location')->nullable(); // المكان
            $table->string('direct_supervisor')->nullable(); // المسؤول المباشر
            $table->text('leaving_reason')->nullable(); // سبب الترك
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('previous_jihadist_actions');
    }
};
