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
        Schema::create('security_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_data_id')->constrained('personal_data')->cascadeOnDelete();
            $table->string('file_number')->nullable()->index();
            $table->date('file_date_gregorian')->nullable();
            $table->string('file_date_hijri')->nullable();
            $table->foreignId('preparing_entity_id')->nullable()->constrained('internal_entities')->nullOnDelete();
            $table->string('file_preparer')->nullable();
            $table->longText('highlight_summary')->nullable();
            $table->longText('jihadist_status')->nullable();
            $table->string('summary_preparer')->nullable();
            $table->date('summary_date_gregorian')->nullable();
            $table->string('summary_date_hijri')->nullable();
            $table->json('file_attachments')->nullable();
            $table->json('summary_attachments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_files');
    }
};
