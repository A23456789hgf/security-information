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
        Schema::table('personal_data', function (Blueprint $table) {
            $table->string('unit')->nullable()->after('rank_id');
            $table->string('job')->nullable()->after('unit');
            $table->string('workplace')->nullable()->after('job');
            $table->string('job_specialty')->nullable()->after('workplace');
            $table->string('jihadist_number')->nullable()->after('job_specialty');
            $table->foreignId('financial_status_id')->nullable()->after('jihadist_number')->constrained('financial_statuses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal_data', function (Blueprint $table) {
            $table->dropForeign(['financial_status_id']);
            $table->dropColumn([
                'unit',
                'job',
                'workplace',
                'job_specialty',
                'jihadist_number',
                'financial_status_id',
            ]);
        });
    }
};
