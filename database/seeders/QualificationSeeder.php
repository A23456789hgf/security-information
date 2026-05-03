<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Qualification;

class QualificationSeeder extends Seeder
{
    public function run(): void
    {
        $qualifications = ['دكتوراه', 'ماجستير', 'بكالوريوس', 'دبلوم عالي', 'دبلوم', 'ثانوية عامة', 'إعدادية', 'ابتدائية', 'يقرأ ويكتب', 'أمي'];

        foreach ($qualifications as $name) {
            Qualification::updateOrCreate(['name' => $name]);
        }
    }
}
