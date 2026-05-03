<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rank;

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
            'مدني'
        ];

        foreach ($ranks as $rank) {
            Rank::updateOrCreate(['rank' => $rank]);
        }
    }
}
