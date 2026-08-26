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
        Schema::table('secondary_data', function (Blueprint $table) {
            $table->foreignId('health_status_id')->nullable()->after('personal_data_id')->constrained('health_statuses')->nullOnDelete();
            $table->unsignedInteger('male_children_count')->nullable()->default(0)->after('health_status_id');
            $table->unsignedInteger('female_children_count')->nullable()->default(0)->after('male_children_count');
            $table->unsignedInteger('total_children_count')->nullable()->default(0)->after('female_children_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('secondary_data', function (Blueprint $table) {
            $table->dropForeign(['health_status_id']);
            $table->dropColumn([
                'health_status_id',
                'male_children_count',
                'female_children_count',
                'total_children_count',
            ]);
        });
    }
};
