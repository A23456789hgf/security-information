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
        Schema::create('wives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_data_id')->constrained('personal_data')->onDelete('cascade');
            $table->string('name');
            $table->foreignId('governorate_id')->nullable()->constrained('governorates')->onDelete('set null');
            $table->foreignId('directorate_id')->nullable()->constrained('directorates')->onDelete('set null');
            $table->foreignId('sub_area_id')->nullable()->constrained('sub_areas')->onDelete('set null');
            $table->foreignId('village_id')->nullable()->constrained('villages')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wives');
    }
};
