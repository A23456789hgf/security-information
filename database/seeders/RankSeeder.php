<?php

namespace Database\Seeders;

use App\Models\Rank;
use Illuminate\Database\Seeder;

class RankSeeder extends Seeder
{
    public function run(): void
    {
        $ranks = [
            'فريق أول',
            'فريق',
            'لواء',
            'عميد',
            'عقيد',
            'مقدم',
            'رائد',
            'نقيب',
            'ملازم أول',
            'ملازم ثاني',
            'مساعد أول',
            'مساعد',
            'رقيب أول',
            'رقيب',
            'عريف',
            'جندي',
            'مدني',
        ];

        foreach ($ranks as $rank) {
            Rank::updateOrCreate(['rank' => $rank]);
        }
    }
}
