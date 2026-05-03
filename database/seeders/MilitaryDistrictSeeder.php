<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MilitaryDistrict;

class MilitaryDistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            'المنطقة العسكرية الأولى',
            'المنطقة العسكرية الثانية',
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
