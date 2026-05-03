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
            $table->boolean('is_unique_id')->default(false)->after('slug');
        });

        Schema::table('dynamic_fields', function (Blueprint $table) {
            $table->json('metadata')->nullable()->after('options');
        });
    }

    public function down(): void
    {
        Schema::table('dynamic_pages', function (Blueprint $table) {
            $table->dropColumn('is_unique_id');
        });

        Schema::table('dynamic_fields', function (Blueprint $table) {
            $table->dropColumn('metadata');
        });
    }
};
