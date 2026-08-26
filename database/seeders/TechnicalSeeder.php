<?php

namespace Database\Seeders;

use App\Models\CaseType;
use App\Models\CourseType;
use App\Models\ElectronicInput;
use App\Models\FinancialStatus;
use App\Models\NumberClassification;
use Illuminate\Database\Seeder;

class TechnicalSeeder extends Seeder
{
    public function run(): void
    {
        // Course Types
        $courseTypes = ['أمنية', 'عسكرية', 'فنية', 'إدارية', 'تخصصية', 'ثقافية', 'لغات'];
        foreach ($courseTypes as $name) {
            CourseType::updateOrCreate(['name' => $name]);
        }

        // Case Types
        $caseTypes = ['جنائية', 'مدنية', 'عسكرية', 'سياسية', 'إرهاب', 'مخدرات'];
        foreach ($caseTypes as $name) {
            CaseType::updateOrCreate(['name' => $name]);
        }

        // Number Classifications
        $classifications = ['رقم عسكري', 'رقم مدني', 'رقم جهادي', 'أخرى'];
        foreach ($classifications as $classification) {
            NumberClassification::updateOrCreate(['classification' => $classification]);
        }

        // Financial Statuses
        $financialStatuses = ['معتمد', 'غير معتمد', 'تحت المراجعة', 'موقوف'];
        foreach ($financialStatuses as $name) {
            FinancialStatus::updateOrCreate(['name' => $name]);
        }

        // Electronic Inputs
        $inputs = ['نظام البصمة', 'البطاقة الذكية', 'النظام اليدوي'];
        foreach ($inputs as $name) {
            ElectronicInput::updateOrCreate(['name' => $name]);
        }
    }
}
