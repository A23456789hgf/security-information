<?php

namespace Database\Seeders;

use App\Models\MilitaryDistrict;
use Illuminate\Database\Seeder;

class MilitaryDistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            // 'المنطقة العسكرية الأولى',
            // 'المنطقة العسكرية الثانية',
            'المنطقة العسكرية الثالثة',
            'المنطقة العسكرية الرابعة',
            'المنطقة العسكرية الخامسة',
            'المنطقة العسكرية السادسة',
            'المنطقة العسكرية السابعة',
        ];

        foreach ($districts as $name) {
            MilitaryDistrict::updateOrCreate(['name' => $name]);
        }
    }
}
