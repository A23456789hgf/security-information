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
        // 1. registry of targets
        Schema::create('difference_search_targets', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "البيانات الشخصية"
            $table->string('type'); // static, dynamic, custom_table
            $table->string('source_identifier'); // Model class, page slug, or table ID
            $table->json('columns'); // mappings: {"name":"name","id_number":"id",...}
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. global settings
        Schema::create('difference_analysis_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('similarity_threshold')->default(80);
            $table->json('matching_rules'); // custom composite rules
            $table->json('excluded_words')->nullable(); // stopwords / titles list
            $table->string('selected_strategy')->default('standard'); // standard, soundex, nlp
            $table->timestamps();
        });

        // 3. analyses history
        Schema::create('difference_analyses', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->integer('progress')->default(0);
            $table->integer('total_rows')->default(0);
            $table->integer('processed_rows')->default(0);
            $table->json('stats')->nullable(); // matches, similarities, duplicates, not_found
            $table->json('settings_snapshot')->nullable(); // snapshot of rules/thresholds used
            $table->timestamps();
        });

        // 4. individual row results
        Schema::create('difference_analysis_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('analysis_id')->constrained('difference_analyses')->onDelete('cascade');
            $table->string('external_name')->nullable();
            $table->json('external_row_data')->nullable(); // all excel columns
            $table->string('status'); // match, similarity, duplicate, not_found
            $table->decimal('similarity_percentage', 5, 2)->default(0.00);
            $table->string('matching_name')->nullable();
            $table->string('source_target_id')->nullable(); // target registry ID
            $table->string('source_target_name')->nullable(); // target name (e.g. "البيانات الشخصية")
            $table->string('source_table')->nullable();
            $table->string('source_page')->nullable();
            $table->string('source_page_type')->nullable(); // static, dynamic, custom
            $table->integer('occurrences_count')->default(0);
            $table->string('record_status')->nullable();
            $table->string('original_record_id')->nullable();
            $table->json('original_record_data')->nullable();
            $table->json('all_matches')->nullable(); // other matches list
            $table->json('matching_details')->nullable(); // detailed breakdown
            $table->timestamps();

            // Indexes for fast queries
            $table->index('status');
            $table->index('similarity_percentage');
        });

        // 5. process logs
        Schema::create('difference_analysis_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('analysis_id')->constrained('difference_analyses')->onDelete('cascade');
            $table->string('level')->default('info'); // info, warning, error
            $table->text('message');
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('difference_analysis_logs');
        Schema::dropIfExists('difference_analysis_results');
        Schema::dropIfExists('difference_analyses');
        Schema::dropIfExists('difference_analysis_settings');
        Schema::dropIfExists('difference_search_targets');
    }
};
