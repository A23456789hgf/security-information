<?php

namespace Database\Seeders;

use App\Models\JobStatus;
use Illuminate\Database\Seeder;

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
