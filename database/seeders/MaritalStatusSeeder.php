<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaritalStatus;

class MaritalStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ['أعزب', 'متزوج', 'مطلق', 'أرمل'];

        foreach ($statuses as $status) {
            MaritalStatus::updateOrCreate(['status' => $status]);
        }
    }
}
