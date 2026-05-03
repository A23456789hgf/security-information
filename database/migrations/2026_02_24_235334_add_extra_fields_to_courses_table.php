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
        Schema::table('courses', function (Blueprint $table) {
            $table->string('national_id_number')->nullable()->after('personal_data_id');
            $table->date('birth_date')->nullable()->after('national_id_number');
            $table->string('phone')->nullable()->after('birth_date');
            $table->string('address')->nullable()->after('phone');
            $table->foreignId('governorate_id')->nullable()->after('address')->constrained('governorates');
            $table->foreignId('directorate_id')->nullable()->after('governorate_id')->constrained('directorates');
            $table->foreignId('sub_area_id')->nullable()->after('directorate_id')->constrained('sub_areas');
            $table->foreignId('village_id')->nullable()->after('sub_area_id')->constrained('villages');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['governorate_id']);
            $table->dropForeign(['directorate_id']);
            $table->dropForeign(['sub_area_id']);
            $table->dropForeign(['village_id']);
            $table->dropColumn(['national_id_number', 'birth_date', 'phone', 'address', 'governorate_id', 'directorate_id', 'sub_area_id', 'village_id']);
        });
    }
};
