<?php

namespace Database\Seeders;

use App\Models\Qualification;
use Illuminate\Database\Seeder;

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
