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
        Schema::create('suspects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_data_id')->constrained('personal_data')->onDelete('cascade');
            $table->string('suspect_number')->nullable();
            $table->date('suspicion_date')->nullable();
            $table->string('suspicion_type')->nullable();
            $table->string('suspicion_location')->nullable();
            $table->string('suspicion_level')->nullable();
            $table->string('information_source')->nullable();
            $table->boolean('is_arrested')->default(false);
            $table->text('suspicion_details')->nullable();
            $table->text('monitoring_results')->nullable();
            $table->string('suspicion_follower')->nullable();
            $table->string('suspicion_report')->nullable();
            $table->text('observations')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suspects');
    }
};
