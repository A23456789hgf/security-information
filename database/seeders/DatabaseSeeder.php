<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            BloodTypeSeeder::class,
            QualificationSeeder::class,
            MaritalStatusSeeder::class,
            JobStatusSeeder::class,
            RankSeeder::class,
            LocationSeeder::class,
            TechnicalSeeder::class,
            MilitaryDistrictSeeder::class,
            AxisSeeder::class,
            DivisionSeeder::class,
        ]);
    }
}
