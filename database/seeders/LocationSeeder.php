<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Governorate;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // Countries
        $countries = ['اليمن', 'السعودية', 'مصر', 'الأردن', 'عمان', 'الإمارات', 'الكويت', 'قطر', 'البحرين', 'العراق', 'سوريا', 'فلسطين', 'لبنان', 'تركيا'];
        foreach ($countries as $name) {
            Country::updateOrCreate(['name' => $name]);
        }

        // Yemeni Governorates
        $governorates = [
            'أمانة العاصمة',
            'صنعاء',
            'تعز',
            'عدن',
            'الحديدة',
            'إب',
            'حضرموت',
            'البيضاء',
            'شبوة',
            'الجوف',
            'مأرب',
            'حجة',
            'صعدة',
            'المحويت',
            'ذمار',
            'عمران',
            'المهرة',
            'سقطرى',
            'ريمة',
            'الضالع',
            'لحج',
            'أبين'
        ];

        foreach ($governorates as $name) {
            Governorate::updateOrCreate(['name' => $name]);
        }
    }
}
