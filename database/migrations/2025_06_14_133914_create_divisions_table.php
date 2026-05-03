<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('divisions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('military_district_id')->constrained()->onDelete('cascade');
        $table->string('name'); // اسم الشعبة
        $table->timestamps();

        $table->unique(['military_district_id', 'name']); // الشعبة فريدة ضمن المنطقة
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('divisions');
    }
};
