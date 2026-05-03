<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobStatusesTable extends Migration
{
    public function up()
    {
        Schema::create('job_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('job_status')->unique(); // الحالة الوظيفية
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_statuses');
    }
}
