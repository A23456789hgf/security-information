<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Governorate;
use App\Models\Directorate;
use App\Models\SubArea;
use App\Models\Village;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // Countries
        $countries = ['اليمن', 'السعودية', 'مصر', 'الأردن', 'عمان', 'الإمارات', 'الكويت', 'قطر', 'البحرين', 'العراق', 'سوريا', 'فلسطين', 'لبنان', 'تركيا'];
        foreach ($countries as $name) {
            Country::updateOrCreate(['name' => $name]);
        }

        // Map of Governorates and their key Directorates
        $govData = [
            'أمانة العاصمة' => ['السبعين', 'الثورة', 'التحرير', 'معين', 'الصافية', 'شعوب', 'الأزرقين', 'الوحدة', 'القديمة'],
            'صنعاء' => ['سنحان', 'بني حشيش', 'همدان', 'بني مطر', 'أرحب', 'خولان', 'مناخة', 'الحيمة الداخلية', 'الحيمة الخارجية'],
            'عدن' => ['المنصورة', 'الشيخ عثمان', 'خورمكسر', 'كريتر', 'المعلا', 'التواهي', 'دار سعد', 'البريقة'],
            'تعز' => ['القاهرة', 'المظفر', 'صالة', 'التعزية', 'شرعب الرونة', 'شرعب السلام', 'ماوية', 'المسراخ', 'جبل حبشي'],
            'إب' => ['الظهار', 'المشنة', 'جبلة', 'السبرة', 'الرضمة', 'يريم', 'القفر', 'العدين', 'ذي السفال'],
            'الحديدة' => ['الحوك', 'المينا', 'الحالي', 'الزيدية', 'بيت الفقيه', 'باجل', 'الزهرة', 'اللحية'],
            'حضرموت' => ['المكلا', 'سيئون', 'الشحر', 'تريم', 'شبام', 'دوعن', 'القطن'],
            'البيضاء' => ['البيضاء', 'رداع', 'مكيراس', 'الزاهر', 'الذيعات'],
            'شبوة' => ['عتق', 'حبان', 'بيحان', 'ميفعة'],
            'الجوف' => ['الحزم', 'المتون', 'الزاهر', 'خب والشعف'],
            'مأرب' => ['مأرب', 'صرواح', 'الجوبة', 'حريب'],
            'حجة' => ['حجة', 'عبس', 'كحلان عفار', 'المحابشة', 'حرض'],
            'صعدة' => ['صعدة', 'سحار', 'مجز', 'الصفراء', 'حيدان'],
            'المحويت' => ['المحويت', 'شبام كوكبان', 'الطويلة', 'ملحان'],
            'ذمار' => ['ذمار', 'عنس', 'الحداء', 'جهران', 'ميفعة عنس'],
            'عمران' => ['عمران', 'خمر', 'ريدة', 'حرف سفيان'],
            'المهرة' => ['الغيضة', 'شحن', 'حوف'],
            'سقطرى' => ['حديبو', 'قلنسية'],
            'ريمة' => ['الجبين', 'كسمة', 'السلفية'],
            'الضالع' => ['الضالع', 'قعطبة', 'الشعيب'],
            'لحج' => ['الحوطة', 'تبن', 'ردفان', 'يافع'],
            'أبين' => ['زنجبار', 'خنفر', 'لودر', 'مودية'],
        ];

        foreach ($govData as $govName => $directorates) {
            $gov = Governorate::updateOrCreate(['name' => $govName]);

            foreach ($directorates as $dirName) {
                $dir = Directorate::updateOrCreate([
                    'name' => $dirName,
                    'governorate_id' => $gov->id,
                ]);

                // Create default sub-areas for each directorate
                $subAreas = ['العزلة الأولى', 'المركز الرئيسي', 'العزلة الشرقية'];
                foreach ($subAreas as $saName) {
                    $sa = SubArea::updateOrCreate([
                        'name' => $saName,
                        'directorate_id' => $dir->id,
                        'governorate_id' => $gov->id,
                    ]);

                    // Create default village for each sub-area
                    Village::updateOrCreate([
                        'name' => 'قرية ' . $saName,
                        'sub_area_id' => $sa->id,
                        'directorate_id' => $dir->id,
                        'governorate_id' => $gov->id,
                    ]);
                }
            }
        }
    }
}
