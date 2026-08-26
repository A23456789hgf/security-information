<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->string('work_status')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('work_status');
        });

        // Populate username from name (sanitized)
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            $baseUsername = strtolower(str_replace(' ', '.', $user->name));
            $username = $baseUsername;
            $count = 1;
            while (DB::table('users')->where('username', $username)->exists()) {
                $username = $baseUsername.$count;
                $count++;
            }
            DB::table('users')->where('id', $user->id)->update(['username' => $username]);
        }

        // Now make it non-nullable if desired, but for now we keep it nullable to allow migration
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'phone', 'work_status', 'is_active']);
        });
    }
};
