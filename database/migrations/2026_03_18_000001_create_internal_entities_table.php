<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internal_entities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('governorate_id')->nullable()->constrained('governorates');
            $table->foreignId('directorate_id')->nullable()->constrained('directorates');
            $table->foreignId('parent_id')->nullable()->constrained('internal_entities')->onDelete('cascade');
            $table->timestamps();
        });

        // Add internal_entity_id to users for scoping
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('internal_entity_id')->nullable()->constrained('internal_entities');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['internal_entity_id']);
            $table->dropColumn('internal_entity_id');
        });
        Schema::dropIfExists('internal_entities');
    }
};
