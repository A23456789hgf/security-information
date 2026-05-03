<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BloodType;

class BloodTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];

        foreach ($types as $type) {
            BloodType::updateOrCreate(['type' => $type]);
        }
    }
}
