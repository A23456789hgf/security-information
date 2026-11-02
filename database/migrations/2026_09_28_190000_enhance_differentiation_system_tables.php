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
        // 1. Enhance data_tables to support full secondary datasets management
        Schema::table('data_tables', function (Blueprint $table) {
            if (!Schema::hasColumn('data_tables', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('data_tables', 'source')) {
                $table->string('source')->nullable()->after('type'); // e.g. Excel, CSV, Manual, System Page
            }
            if (!Schema::hasColumn('data_tables', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('data_tables', 'field_mappings')) {
                $table->json('field_mappings')->nullable()->after('column_headers');
            }
            if (!Schema::hasColumn('data_tables', 'records_count')) {
                $table->integer('records_count')->default(0)->after('field_mappings');
            }
        });

        // 2. Create differentiation_logs table for operations auditing
        if (!Schema::hasTable('differentiation_logs')) {
            Schema::create('differentiation_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('action'); // import_main, import_secondary, match_datasets, match_system_page, delete_dataset, update_dataset, export
                $table->string('dataset_name')->nullable();
                $table->json('details')->nullable();
                $table->timestamps();
            });
        }

        // 3. Create differentiation_saved_matches table for saved comparison runs
        if (!Schema::hasTable('differentiation_saved_matches')) {
            Schema::create('differentiation_saved_matches', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('title');
                $table->string('primary_source_type'); // 'data_table' or 'system_model' or 'dynamic_page'
                $table->string('primary_source_id')->nullable();
                $table->unsignedBigInteger('secondary_table_id')->nullable();
                $table->json('key_fields')->nullable();
                $table->json('field_mappings')->nullable();
                $table->json('summary')->nullable(); // matched, different, in_sec_only, in_pri_only, incomplete counts
                $table->longText('results_data')->nullable(); // serialized comparison breakdown
                $table->timestamps();

                $table->foreign('secondary_table_id')->references('id')->on('data_tables')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('differentiation_saved_matches');
        Schema::dropIfExists('differentiation_logs');
        Schema::table('data_tables', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'source', 'description', 'field_mappings', 'records_count']);
        });
    }
};

