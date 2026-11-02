<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_geographic_scopes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('governorate_id')->nullable()->constrained('governorates')->onDelete('set null');
            $table->foreignId('directorate_id')->nullable()->constrained('directorates')->onDelete('set null');
            $table->string('scope_type')->default('specific'); // 'specific', 'all', 'none'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_geographic_scopes');
    }
};

