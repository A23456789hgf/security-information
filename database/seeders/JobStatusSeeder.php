<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobStatus;

class JobStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ['عامل', 'متقاعد', 'منقطع', 'متوفى', 'شهيد', 'مفقود'];

        foreach ($statuses as $status) {
            JobStatus::updateOrCreate(['job_status' => $status]);
        }
    }
}
