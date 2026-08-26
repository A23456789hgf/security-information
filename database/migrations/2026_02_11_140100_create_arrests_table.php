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
        Schema::create('arrests', function (Blueprint $table) {
            $table->id();

            // Foreign key to personal data (for auto-populated fields)
            $table->foreignId('personal_data_id')->constrained('personal_data')->onDelete('cascade');

            // Arrest authority and case type
            $table->foreignId('arresting_authority_id')->nullable()->constrained('arresting_authorities')->onDelete('set null');
            $table->foreignId('case_type_id')->nullable()->constrained('case_types')->onDelete('set null');

            // Arrest dates
            $table->date('arrest_date')->nullable();

            // Location information
            $table->string('location')->nullable();
            $table->string('arrest_location')->nullable();

            // Case information
            $table->text('case_details')->nullable();
            $table->text('seized_items')->nullable();
            $table->text('case_follow_up_results')->nullable();
            $table->text('related_information')->nullable();

            // Status and duration
            $table->string('status')->nullable();
            $table->string('duration_period')->nullable();

            // Conclusion and reports
            $table->text('conclusion')->nullable();
            $table->text('investigation_reports')->nullable();

            // Attachments (stored as JSON array of file paths)
            $table->json('attachments')->nullable();

            // Release information
            $table->date('release_date')->nullable();
            $table->text('release_reason')->nullable();

            // Additional notes
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arrests');
    }
};
