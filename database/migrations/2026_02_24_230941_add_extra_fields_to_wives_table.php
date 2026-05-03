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
        Schema::table('wives', function (Blueprint $table) {
            $table->string('wife_id_number')->nullable()->after('name');
            $table->date('wife_birth_date')->nullable()->after('wife_id_number');
            $table->string('wife_phone')->nullable()->after('wife_birth_date');
            $table->string('wife_address')->nullable()->after('wife_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wives', function (Blueprint $table) {
            $table->dropColumn(['wife_id_number', 'wife_birth_date', 'wife_phone', 'wife_address']);
        });
    }
};
