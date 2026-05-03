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
        Schema::create('secondary_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_data_id')->constrained('personal_data')->onDelete('cascade');
            $table->date('start_date')->nullable();
            $table->text('previous_jihadist_activity')->nullable();
            $table->text('current_jihadist_activity')->nullable();
            $table->string('direct_supervisor')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secondary_data');
    }
};
