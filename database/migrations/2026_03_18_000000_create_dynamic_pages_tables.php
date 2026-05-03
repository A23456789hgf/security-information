<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dynamic_pages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('dynamic_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('dynamic_pages')->onDelete('cascade');
            $table->string('name');
            $table->string('label');
            $table->string('type'); // text, number, select, date, file, etc.
            $table->boolean('is_required')->default(false);
            $table->json('options')->nullable(); // For select/radio options
            $table->integer('order_column')->default(0);
            $table->timestamps();
        });

        Schema::create('dynamic_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('dynamic_pages')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('created_by_entity_id')->nullable();
            $table->timestamps();
        });

        Schema::create('dynamic_record_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id')->constrained('dynamic_records')->onDelete('cascade');
            $table->foreignId('field_id')->constrained('dynamic_fields')->onDelete('cascade');
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dynamic_record_values');
        Schema::dropIfExists('dynamic_records');
        Schema::dropIfExists('dynamic_fields');
        Schema::dropIfExists('dynamic_pages');
    }
};
