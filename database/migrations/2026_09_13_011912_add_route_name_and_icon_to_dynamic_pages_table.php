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
        Schema::table('dynamic_pages', function (Blueprint $table) {
            $table->string('route_name')->nullable()->after('slug')->comment('System route name for built-in pages');
            $table->string('icon')->nullable()->after('route_name')->comment('FontAwesome icon class');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dynamic_pages', function (Blueprint $table) {
            $table->dropColumn(['route_name', 'icon']);
        });
    }
};
