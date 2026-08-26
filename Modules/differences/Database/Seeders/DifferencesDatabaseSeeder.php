<?php

namespace Modules\Differences\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Differences\Models\DifferenceSearchTarget;
use Modules\Differences\Models\DifferenceAnalysisSetting;

class DifferencesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed global settings
        DifferenceAnalysisSetting::updateOrCreate(
            ['id' => 1],
            [
                'similarity_threshold' => 80,
                'excluded_words' => ['السيد', 'الدكتور', 'الدكتورة', 'الاستاذ', 'الاستاذة', 'الشيخ', 'الشيخه', 'ابو', 'ام', 'ابن', 'بنت', 'عبد'],
                'selected_strategy' => 'standard',
                'matching_rules' => [
                    ['id' => 1, 'columns' => ['name', 'id'], 'result_status' => 'match', 'label' => 'الاسم + الهوية (مطابقة مؤكدة)'],
                    ['id' => 2, 'columns' => ['name'], 'result_status' => 'similarity', 'label' => 'الاسم فقط (تشابه)'],
                    ['id' => 3, 'columns' => ['phone'], 'result_status' => 'similarity', 'label' => 'الهاتف فقط (يحتاج مراجعة)']
                ]
            ]
        );

        // 2. Seed default static search targets registry
        DifferenceSearchTarget::updateOrCreate(
            ['source_identifier' => 'App\Models\PersonalData'],
            [
                'name' => 'البيانات الشخصية للمنتسبين',
                'type' => 'static',
                'columns' => [
                    'name' => 'name',
                    'id' => 'national_id_number',
                    'phone' => 'phone_1',
                    'address' => 'workplace'
                ],
                'is_active' => true
            ]
        );

        DifferenceSearchTarget::updateOrCreate(
            ['source_identifier' => 'App\Models\Wife'],
            [
                'name' => 'بيانات الزوجات',
                'type' => 'static',
                'columns' => [
                    'name' => 'name',
                    'id' => 'wife_id_number',
                    'phone' => 'wife_phone',
                    'address' => 'wife_address'
                ],
                'is_active' => true
            ]
        );

        DifferenceSearchTarget::updateOrCreate(
            ['source_identifier' => 'App\Models\SecurityReport'],
            [
                'name' => 'التقارير الأمنية للجرائم والانتساب',
                'type' => 'static',
                'columns' => [
                    'name' => 'full_name',
                    'id' => 'id_number',
                    'phone' => 'phone',
                    'address' => 'residence_village'
                ],
                'is_active' => true
            ]
        );
    }
}
