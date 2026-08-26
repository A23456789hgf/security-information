<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\MilitaryDistrict;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = MilitaryDistrict::all();

        if ($districts->isEmpty()) {
            // Districts are seeded in AxisSeeder or should be seeded separately
            return;
        }

        $divisions = [
            'المنطقة العسكرية الأولى' => ['شعبة الأفراد', 'شعبة العمليات', 'شعبة الاستخبارات'],
            'المنطقة العسكرية الثانية' => ['شعبة الأفراد', 'شعبة العمليات', 'شعبة الاستخبارات'],
            'المنطقة العسكرية الثالثة' => ['شعبة الأفراد', 'شعبة العمليات', 'شعبة الاستخبارات'],
            'المنطقة العسكرية الرابعة' => ['شعبة الأفراد', 'شعبة العمليات', 'شعبة الاستخبارات'],
            'المنطقة العسكرية الخامسة' => ['شعبة الأفراد', 'شعبة العمليات', 'شعبة الاستخبارات'],
            'المنطقة العسكرية السادسة' => ['شعبة الأفراد', 'شعبة العمليات', 'شعبة الاستخبارات'],
            'المنطقة العسكرية السابعة' => ['شعبة الأفراد', 'شعبة العمليات', 'شعبة الاستخبارات'],
        ];

        foreach ($divisions as $districtName => $divList) {
            $district = $districts->where('name', $districtName)->first();
            if ($district) {
                foreach ($divList as $divName) {
                    Division::updateOrCreate([
                        'military_district_id' => $district->id,
                        'name' => $divName,
                    ]);
                }
            }
        }
    }
}
