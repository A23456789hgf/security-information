<?php

namespace Database\Seeders;

use App\Models\Axis;
use App\Models\MilitaryDistrict;
use Illuminate\Database\Seeder;

class AxisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $axes = [
            // 'المنطقة العسكرية الأولى' => ['محور سيئون', 'محور ثمود'],
            // 'المنطقة العسكرية الثانية' => ['محور المكلا', 'محور الغيظة'],
            'المنطقة العسكرية الثالثة' => ['محور مأرب', 'محور بيحان'],
            'المنطقة العسكرية الرابعة' => ['محور عدن', 'محور أبين', 'محور لحج/العند'],
            'المنطقة العسكرية الخامسة' => ['محور ميدي'],
            'المنطقة العسكرية السادسة' => ['محور الجوف'],
            'المنطقة العسكرية السابعة' => ['محور ذمار'],
        ];

        foreach ($axes as $districtName => $axisList) {
            $district = MilitaryDistrict::where('name', $districtName)->first();
            if ($district) {
                foreach ($axisList as $axisName) {
                    Axis::updateOrCreate([
                        'military_district_id' => $district->id,
                        'name' => $axisName,
                    ]);
                }
            }
        }
    }
}
