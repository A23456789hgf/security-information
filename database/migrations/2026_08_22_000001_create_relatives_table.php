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
        Schema::create('relatives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_data_id')->constrained('personal_data')->cascadeOnDelete();
            $table->string('full_name');
            $table->unsignedInteger('age')->nullable();
            $table->foreignId('kinship_id')->nullable()->constrained('kinships')->nullOnDelete();
            $table->string('current_job')->nullable();
            $table->foreignId('ideological_affiliation_id')->nullable()->constrained('ideological_affiliations')->nullOnDelete();
            $table->foreignId('political_affiliation_id')->nullable()->constrained('political_affiliations')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relatives');
    }
};
