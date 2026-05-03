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
        Schema::table('security_reports', function (Blueprint $table) {
            $table->foreignId('internal_entity_id')->nullable()->after('work')->constrained('internal_entities')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('security_reports', function (Blueprint $table) {
            $table->dropForeign(['internal_entity_id']);
            $table->dropColumn('internal_entity_id');
        });
    }
};
