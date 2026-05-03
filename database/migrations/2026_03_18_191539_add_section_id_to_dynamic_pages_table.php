<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add section_id column
        Schema::table('dynamic_pages', function (Blueprint $table) {
            $table->foreignId('section_id')->nullable()->after('slug')->constrained('sections')->onDelete('set null');
        });

        // 2. Migrate data
        $pages = DB::table('dynamic_pages')->get();
        foreach ($pages as $page) {
            $sectionName = $page->section ?: 'نماذج عامة';
            
            $sectionId = DB::table('sections')->where('name', $sectionName)->value('id');
            
            if (!$sectionId) {
                // Generate a simple slug
                $slug = Str::slug($sectionName);
                if (empty($slug) || DB::table('sections')->where('slug', $slug)->exists()) {
                    $slug = 'sec-' . Str::random(5);
                }
                
                $sectionId = DB::table('sections')->insertGetId([
                    'name' => $sectionName,
                    'slug' => $slug,
                    'icon' => 'fa-folder',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            DB::table('dynamic_pages')->where('id', $page->id)->update(['section_id' => $sectionId]);
        }

        // 3. Drop old section column
        Schema::table('dynamic_pages', function (Blueprint $table) {
            $table->dropColumn('section');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Add back the section column
        Schema::table('dynamic_pages', function (Blueprint $table) {
            $table->string('section')->nullable()->after('name');
        });

        // 2. Reverse data
        $pages = DB::table('dynamic_pages')->get();
        foreach ($pages as $page) {
            if ($page->section_id) {
                $sectionName = DB::table('sections')->where('id', $page->section_id)->value('name');
                DB::table('dynamic_pages')->where('id', $page->id)->update(['section' => $sectionName]);
            }
        }

        // 3. Drop section_id column
        Schema::table('dynamic_pages', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->dropColumn('section_id');
        });
    }
};
