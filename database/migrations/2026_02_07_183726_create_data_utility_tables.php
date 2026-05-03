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
        Schema::create('data_tables', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('primary'); // primary or secondary
            $table->json('column_headers')->nullable(); // Stores the current headers of the table
            $table->timestamps();
        });

        Schema::create('data_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('data_table_id');
            $table->json('data'); // Stores the actual row data as key-value pairs
            $table->integer('sequence')->default(0); // For numbering records
            $table->timestamps();

            $table->foreign('data_table_id')->references('id')->on('data_tables')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_records');
        Schema::dropIfExists('data_tables');
    }
};
