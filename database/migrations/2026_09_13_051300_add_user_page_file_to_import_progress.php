<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (Schema::hasTable('import_progress')) {
            Schema::table('import_progress', function (Blueprint $table) {
                if (!Schema::hasColumn('import_progress', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->constrained()->after('id');
                }
                if (!Schema::hasColumn('import_progress', 'page_id')) {
                    $table->foreignId('page_id')->nullable()->constrained('pages')->after('user_id');
                }
                if (!Schema::hasColumn('import_progress', 'file_name')) {
                    $table->string('file_name')->nullable()->after('page_id');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('import_progress')) {
            Schema::table('import_progress', function (Blueprint $table) {
                if (Schema::hasColumn('import_progress', 'user_id')) {
                    $table->dropForeign(['user_id']);
                    $table->dropColumn('user_id');
                }
                if (Schema::hasColumn('import_progress', 'page_id')) {
                    $table->dropForeign(['page_id']);
                    $table->dropColumn('page_id');
                }
                if (Schema::hasColumn('import_progress', 'file_name')) {
                    $table->dropColumn('file_name');
                }
            });
        }
    }
};
?>
