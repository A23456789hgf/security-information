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
        Schema::create('personal_data', function (Blueprint $table) {
            $table->id();
            
            $table->string('name')->nullable();
            $table->string('jihadi_name')->nullable();
            $table->string('national_id_number')->nullable();
            
            $table->date('date_of_birth_gregorian')->nullable();
            $table->string('date_of_birth_hijri')->nullable();
            
            $table->foreignId('blood_type_id')->nullable()->constrained('blood_types')->onDelete('set null');
            $table->foreignId('marital_status_id')->nullable()->constrained('marital_statuses')->onDelete('set null');
            $table->foreignId('qualification_id')->nullable()->constrained('qualifications')->onDelete('set null');
            $table->foreignId('number_classification_id')->nullable()->constrained('number_classifications')->onDelete('set null');
            $table->foreignId('job_status_id')->nullable()->constrained('job_statuses')->onDelete('set null');
            $table->foreignId('rank_id')->nullable()->constrained('ranks')->onDelete('set null');
            
            $table->foreignId('military_district_id')->nullable()->constrained('military_districts')->onDelete('set null');
            $table->foreignId('division_id')->nullable()->constrained('divisions')->onDelete('set null');
            
            $table->foreignId('place_of_birth_country_id')->nullable()->constrained('countries')->onDelete('set null');
            $table->foreignId('place_of_birth_governorate_id')->nullable()->constrained('governorates')->onDelete('set null');
            $table->foreignId('place_of_birth_directorate_id')->nullable()->constrained('directorates')->onDelete('set null');
            $table->foreignId('place_of_birth_sub_area_id')->nullable()->constrained('sub_areas')->onDelete('set null');
            $table->foreignId('place_of_birth_village_id')->nullable()->constrained('villages')->onDelete('set null');
            
            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('set null');
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
        Schema::dropIfExists('personal_data');
    }
};
