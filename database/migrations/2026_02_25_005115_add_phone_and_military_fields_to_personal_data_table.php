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
            $table->string('phone_1')->nullable()->after('jihadi_name');
            $table->string('phone_2')->nullable()->after('phone_1');
            $table->string('phone_3')->nullable()->after('phone_2');
            $table->string('military_number')->nullable()->after('national_id_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal_data', function (Blueprint $table) {
            $table->dropColumn(['phone_1', 'phone_2', 'phone_3', 'military_number']);
        });
    }
};
