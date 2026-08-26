<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PersonalData;
use App\Models\SecondaryData;
use App\Models\Wife;
use App\Models\Course;
use App\Models\Arrest;
use App\Models\Suspect;
use App\Models\SecurityReport;
use App\Models\Page;
use App\Models\Field;
use App\Models\Record;
use App\Models\RecordValue;
use App\Models\Section;
use App\Models\User;
use App\Models\InternalEntity;
use App\Models\ArrestingAuthority;
use App\Models\CaseType;
use App\Models\CourseType;
use App\Models\BloodType;
use App\Models\MaritalStatus;
use App\Models\Qualification;
use App\Models\NumberClassification;
use App\Models\JobStatus;
use App\Models\Rank;
use App\Models\MilitaryDistrict;
use App\Models\Division;
use App\Models\FinancialStatus;
use App\Models\Governorate;
use App\Models\Directorate;
use App\Models\SubArea;
use App\Models\Village;
use App\Models\Country;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Core realistic Arabic male names
        $maleNames = [
            'محمد أحمد علي الجابري', 'عبد الرحمن حسين محمود', 'أحمد سعيد صالح اليافعي',
            'خالد عبدالله عمر باوزير', 'ياسر علي ناصر اليافعي', 'صالح محمد قاسم العمري',
            'عبد العزيز مانع حسين', 'عمر فاروق عثمان بن عفيف', 'سالم علي مسعد العامري',
            'حسن عبد الله حسن علوي', 'حسين محمد علي فضل', 'فهد صالح مسعد مقبل',
            'عبد اللطيف أحمد قاسم', 'سعيد علي ناجي الحارثي', 'جمال عبد الناصر حسين',
            'ماجد محمد ناصر شاهر', 'طارق أحمد عبدالله الكبسي', 'مروان علي صالح عياش',
            'نبيل محمد علي عبيد', 'رائد عبدالله مسعد ناصر', 'وليد خالد عمر باوزير',
            'سليمان داود سليمان الجابري', 'أمين صالح عبده مقبل', 'منير حسين ناصر العامري',
            'صلاح الدين يوسف علي', 'سامر أحمد ناصر فضل', 'يوسف محمد يوسف الجابري',
            'وجدي علي حسين ناصر', 'رياض عبدالله عمر باوزير', 'غسان صالح مسعد شاهر',
            'بشار خالد محمد ناصر', 'مختار أحمد حسين علوي', 'زكريا يحيى يحيى الجابري',
            'علاء محمد سعيد صالح', 'عادل علي عبدالله باوزير', 'عاصم محمد قاسم العمري',
            'حاتم ناصر حسين فضل', 'شريف أحمد عبده شاهر', 'أيمن صالح مسعد ناصر',
            'إبراهيم خليل إبراهيم', 'مصطفى كامل مصطفى الجابري', 'شادي علي ناصر العامري',
            'حمزة جعفر محمد فضل', 'توفيق يحيى يحيى الجابري', 'ماهر أحمد ناصر العمري',
            'وائل خالد عمر باوزير', 'فارس صالح مسعد شاهر', 'سليمان علي حسين ناصر',
            'أسامة عبدالله عمر الجابري', 'جهاد محمد قاسم العمري'
        ];

        // 2. Core realistic Arabic female names for wives
        $femaleNames = [
            'فاطمة أحمد علي الجابري', 'عائشة حسين محمود اليافعي', 'زينب سعيد صالح باوزير',
            'مريم عبدالله عمر العامري', 'خديجة علي ناصر فضل', 'سارة محمد قاسم العمري',
            'أروى مانع حسين شاهر', 'بلقيس عمر فاروق علوي', 'منى علي مسعد الحارثي',
            'هناء عبد الله حسن باوزير', 'سلوى محمد علي عياش', 'ندى صالح مسعد مقبل',
            'نهى أحمد قاسم شاهر', 'شيماء علي ناجي الحارثي', 'رنا عبد الناصر حسين',
            'ياسمين ماجد محمد ناصر', 'مروة طارق أحمد الكبسي', 'عبير مروان علي صالح',
            'غادة نبيل محمد عبيد', 'أميرة رائد عبدالله ناصر', 'ريما وليد خالد باوزير',
            'منى سليمان داود الجابري', 'وفاء أمين صالح مقبل', 'سحر منير حسين العامري',
            'ريهام صلاح الدين يوسف', 'سعاد سامر أحمد فضل', 'يسرى يوسف محمد الجابري',
            'رحاب وجدي علي ناصر', 'إيمان رياض عبدالله باوزير', 'خلود غسان صالح شاهر',
            'هدى بشار خالد ناصر', 'صفاء مختار أحمد علوي', 'مروة زكريا يحيى الجابري',
            'هالة علاء محمد سعيد', 'يسرى عادل علي باوزير', 'فوزية عاصم محمد العمري',
            'منى حاتم ناصر فضل', 'رانية شريف أحمد شاهر', 'سماح أيمن صالح ناصر',
            'هديل إبراهيم خليل', 'رولا مصطفى كامل الجابري', 'دينا شادي علي العامري',
            'عفاف حمزة جعفر فضل', 'نادية توفيق يحيى الجابري', 'نجلاء ماهر أحمد العمري',
            'إلهام وائل خالد باوزير', 'وسام فارس صالح شاهر', 'أمل سليمان علي ناصر',
            'سهام أسامة عبدالله الجابري', 'هنادي جهاد محمد العمري'
        ];

        $supervisorNames = [
            'صالح أحمد محسن', 'علوى محمد فضل', 'عبدالكريم قاسم ناصر', 'ماجد علي شاهر', 'رائد عبدالله مسعد'
        ];

        $jihadistActivities = [
            'الرباط في جبهة مأرب الشمالية', 'إدارة العمليات التكتيكية في الساحل الغربي', 
            'عضو في اللجنة الإعلامية المركزية', 'التنسيق والاتصال والإسناد اللوجستي', 
            'التدريب العسكري التخصصي للمستجدين'
        ];

        // 3. Clear existing dynamic & core dummy records first (dependent tables first to avoid FK constraint issues)
        RecordValue::query()->delete();
        Record::query()->delete();
        Suspect::query()->delete();
        Arrest::query()->delete();
        Course::query()->delete();
        Wife::query()->delete();
        SecondaryData::query()->delete();
        SecurityReport::query()->delete();
        PersonalData::query()->delete();

        // 4. Ensure basic lookup / configuration records exist so seeders don't fail
        $bloodTypeIds = BloodType::pluck('id')->toArray();
        if (empty($bloodTypeIds)) {
            foreach (['O+', 'A+', 'B+', 'O-', 'A-', 'B-', 'AB+', 'AB-'] as $bt) {
                BloodType::create(['name' => $bt]);
            }
            $bloodTypeIds = BloodType::pluck('id')->toArray();
        }

        $maritalStatusIds = MaritalStatus::pluck('id')->toArray();
        if (empty($maritalStatusIds)) {
            foreach (['أعزب', 'متزوج', 'مطلق', 'أرمل'] as $ms) {
                MaritalStatus::create(['name' => $ms]);
            }
            $maritalStatusIds = MaritalStatus::pluck('id')->toArray();
        }

        $qualificationIds = Qualification::pluck('id')->toArray();
        if (empty($qualificationIds)) {
            foreach (['بدون مؤهل', 'ابتدائية', 'إعدادية', 'ثانوية', 'دبلوم', 'بكالوريوس', 'ماجستير', 'دكتوراه'] as $q) {
                Qualification::create(['name' => $q]);
            }
            $qualificationIds = Qualification::pluck('id')->toArray();
        }

        $jobStatusIds = JobStatus::pluck('id')->toArray();
        if (empty($jobStatusIds)) {
            foreach (['نشط', 'متقاعد', 'موقوف مؤقتاً', 'مفصول'] as $js) {
                JobStatus::create(['name' => $js]);
            }
            $jobStatusIds = JobStatus::pluck('id')->toArray();
        }

        $rankIds = Rank::pluck('id')->toArray();
        if (empty($rankIds)) {
            foreach (['جندي', 'عريف', 'رقيب', 'ملازم أول', 'نقيب', 'رائد', 'مقدم', 'عقيد'] as $r) {
                Rank::create(['name' => $r]);
            }
            $rankIds = Rank::pluck('id')->toArray();
        }

        $financialStatusIds = FinancialStatus::pluck('id')->toArray();
        if (empty($financialStatusIds)) {
            foreach (['معتمد', 'غير معتمد', 'تحت المراجعة', 'موقوف'] as $fs) {
                FinancialStatus::create(['name' => $fs]);
            }
            $financialStatusIds = FinancialStatus::pluck('id')->toArray();
        }

        $militaryDistrictIds = MilitaryDistrict::pluck('id')->toArray();
        if (empty($militaryDistrictIds)) {
            foreach (['المنطقة الأولى', 'المنطقة الثانية', 'المنطقة الثالثة', 'المنطقة الرابعة'] as $md) {
                MilitaryDistrict::create(['name' => $md]);
            }
            $militaryDistrictIds = MilitaryDistrict::pluck('id')->toArray();
        }

        $divisionIds = Division::pluck('id')->toArray();
        if (empty($divisionIds)) {
            $districtId = !empty($militaryDistrictIds) ? $militaryDistrictIds[0] : 1;
            foreach (['الشعبة الأولى أفراد', 'الشعبة الثانية عمليات', 'شعبة التدريب'] as $d) {
                Division::create(['name' => $d, 'military_district_id' => $districtId]);
            }
            $divisionIds = Division::pluck('id')->toArray();
        }

        $countryIds = Country::pluck('id')->toArray();
        if (empty($countryIds)) {
            Country::create(['name' => 'اليمن']);
            $countryIds = Country::pluck('id')->toArray();
        }

        $governorateIds = Governorate::pluck('id')->toArray();
        if (empty($governorateIds)) {
            foreach (['عدن', 'صنعاء', 'تعز', 'حضرموت', 'لحج', 'أبين'] as $gov) {
                Governorate::create(['name' => $gov]);
            }
            $governorateIds = Governorate::pluck('id')->toArray();
        }

        $directorateIds = Directorate::pluck('id')->toArray();
        if (empty($directorateIds)) {
            $govId = !empty($governorateIds) ? $governorateIds[0] : 1;
            foreach (['المنصورة', 'الشيخ عثمان', 'خورمكسر'] as $dir) {
                Directorate::create(['name' => $dir, 'governorate_id' => $govId]);
            }
            $directorateIds = Directorate::pluck('id')->toArray();
        }

        $subAreaIds = SubArea::pluck('id')->toArray();
        if (empty($subAreaIds)) {
            $dirId = !empty($directorateIds) ? $directorateIds[0] : 1;
            $dir = Directorate::find($dirId);
            $govId = $dir ? $dir->governorate_id : (!empty($governorateIds) ? $governorateIds[0] : 1);
            foreach (['العزلة الأولى', 'المنطقة السكنية أ', 'المنطقة السكنية ب'] as $sa) {
                SubArea::create([
                    'name' => $sa,
                    'directorate_id' => $dirId,
                    'governorate_id' => $govId
                ]);
            }
            $subAreaIds = SubArea::pluck('id')->toArray();
        }

        $villageIds = Village::pluck('id')->toArray();
        if (empty($villageIds)) {
            $saId = !empty($subAreaIds) ? $subAreaIds[0] : 1;
            $sa = SubArea::find($saId);
            $dirId = $sa ? $sa->directorate_id : (!empty($directorateIds) ? $directorateIds[0] : 1);
            $dir = Directorate::find($dirId);
            $govId = $dir ? $dir->governorate_id : (!empty($governorateIds) ? $governorateIds[0] : 1);
            foreach (['قرية السلام', 'حي النصر', 'حي الرشيد'] as $vil) {
                Village::create([
                    'name' => $vil,
                    'sub_area_id' => $saId,
                    'directorate_id' => $dirId,
                    'governorate_id' => $govId
                ]);
            }
            $villageIds = Village::pluck('id')->toArray();
        }

        $classificationIds = NumberClassification::pluck('id')->toArray();
        if (empty($classificationIds)) {
            foreach (['رقم عسكري', 'رقم مدني', 'رقم جهادي'] as $c) {
                NumberClassification::create(['classification' => $c]);
            }
            $classificationIds = NumberClassification::pluck('id')->toArray();
        }

        $courseTypeIds = CourseType::pluck('id')->toArray();
        if (empty($courseTypeIds)) {
            foreach (['أمنية', 'عسكرية', 'ثقافية', 'تخصصية'] as $ct) {
                CourseType::create(['name' => $ct]);
            }
            $courseTypeIds = CourseType::pluck('id')->toArray();
        }

        // 5. Seed Arrest configuration (Arresting Authority)
        $arrestingAuthorityIds = ArrestingAuthority::pluck('id')->toArray();
        if (empty($arrestingAuthorityIds)) {
            foreach (['إدارة البحث الجنائي', 'جهاز الأمن والمخابرات', 'الشرطة العسكرية', 'إدارة أمن المحافظة'] as $aa) {
                ArrestingAuthority::create(['name' => $aa]);
            }
            $arrestingAuthorityIds = ArrestingAuthority::pluck('id')->toArray();
        }

        // 6. Case types for Arrests
        $caseTypeIds = CaseType::pluck('id')->toArray();
        if (empty($caseTypeIds)) {
            foreach (['جنائية', 'عسكرية', 'سياسية', 'إرهاب', 'مخدرات'] as $ct) {
                CaseType::create(['name' => $ct]);
            }
            $caseTypeIds = CaseType::pluck('id')->toArray();
        }

        // 7. Internal Entities
        $internalEntities = InternalEntity::pluck('id')->toArray();
        if (empty($internalEntities)) {
            $govId = !empty($governorateIds) ? $governorateIds[0] : null;
            $dirId = !empty($directorateIds) ? $directorateIds[0] : null;
            foreach (['الشعبة الأولى أفراد', 'الشعبة الثانية عمليات', 'شعبة التدريب الفني'] as $ieName) {
                InternalEntity::create([
                    'name' => $ieName,
                    'governorate_id' => $govId,
                    'directorate_id' => $dirId,
                ]);
            }
            $internalEntities = InternalEntity::pluck('id')->toArray();
        }

        // 8. Seed 50 PersonalData, and corresponding Wives, Secondary Data, Courses, Arrests, Suspects
        $people = [];
        for ($i = 0; $i < 50; $i++) {
            $nationalId = '10' . str_pad($i + 1, 8, '0', STR_PAD_LEFT);
            $phone = '77' . str_pad($i + 1, 7, '0', STR_PAD_LEFT);
            $militaryNum = 'M-' . (1000 + $i);
            $jihadistNum = 'J-' . (9000 + $i);

            $person = PersonalData::create([
                'name' => $maleNames[$i],
                'jihadi_name' => 'أبو ' . explode(' ', $maleNames[$i])[1],
                'phone_1' => $phone,
                'phone_2' => '73' . str_pad($i + 1, 7, '0', STR_PAD_LEFT),
                'phone_3' => '71' . str_pad($i + 1, 7, '0', STR_PAD_LEFT),
                'national_id_number' => $nationalId,
                'military_number' => $militaryNum,
                'date_of_birth_gregorian' => now()->subYears(20 + $i % 30)->format('Y-m-d'),
                'date_of_birth_hijri' => (1400 + $i % 30) . '/01/01',
                'blood_type_id' => $bloodTypeIds[$i % count($bloodTypeIds)],
                'marital_status_id' => $maritalStatusIds[$i % count($maritalStatusIds)],
                'qualification_id' => $qualificationIds[$i % count($qualificationIds)],
                'number_classification_id' => $classificationIds[$i % count($classificationIds)],
                'job_status_id' => $jobStatusIds[$i % count($jobStatusIds)],
                'rank_id' => $rankIds[$i % count($rankIds)],
                'military_district_id' => $militaryDistrictIds[$i % count($militaryDistrictIds)],
                'division_id' => $divisionIds[$i % count($divisionIds)],
                'financial_status_id' => $financialStatusIds[$i % count($financialStatusIds)],
                'place_of_birth_country_id' => $countryIds[0],
                'place_of_birth_governorate_id' => $governorateIds[$i % count($governorateIds)],
                'place_of_birth_directorate_id' => $directorateIds[$i % count($directorateIds)],
                'place_of_birth_sub_area_id' => $subAreaIds[$i % count($subAreaIds)],
                'place_of_birth_village_id' => $villageIds[$i % count($villageIds)],
                'country_id' => $countryIds[0],
                'governorate_id' => $governorateIds[$i % count($governorateIds)],
                'directorate_id' => $directorateIds[$i % count($directorateIds)],
                'sub_area_id' => $subAreaIds[$i % count($subAreaIds)],
                'village_id' => $villageIds[$i % count($villageIds)],
                'internal_entity_id' => $internalEntities[$i % count($internalEntities)],
                'unit' => 'الكتيبة ' . ($i % 10 + 1) . ' - السرية ' . ($i % 3 + 1),
                'job' => 'قائد فصيل ' . ($i % 5 + 1),
                'workplace' => 'محافظة عدن - جبهة ' . ($i % 3 === 0 ? 'شمال عدن' : ($i % 3 === 1 ? 'البريقة' : 'المنصورة')),
                'job_specialty' => $i % 2 === 0 ? 'مخابرات وأمن' : 'مدفعية وإسناد ناري',
                'jihadist_number' => $jihadistNum,
            ]);

            $people[] = $person;

            // Seed corresponding Wife
            Wife::create([
                'personal_data_id' => $person->id,
                'name' => $femaleNames[$i],
                'wife_id_number' => '20' . str_pad($i + 1, 8, '0', STR_PAD_LEFT),
                'wife_phone' => '70' . str_pad($i + 1, 7, '0', STR_PAD_LEFT),
                'wife_address' => 'محافظة ' . ($i % 2 === 0 ? 'عدن' : 'صنعاء') . ' - السكن ' . ($i % 4 + 1),
                'status' => 'على ذمته'
            ]);

            // Seed corresponding SecondaryData
            SecondaryData::create([
                'personal_data_id' => $person->id,
                'start_date' => now()->subYears(5 + $i % 10)->format('Y-m-d'),
                'previous_jihadist_activity' => $jihadistActivities[($i + 2) % count($jihadistActivities)] . ' (سابقاً)',
                'current_jihadist_activity' => $jihadistActivities[$i % count($jihadistActivities)],
                'direct_supervisor' => $supervisorNames[$i % count($supervisorNames)]
            ]);

            // Seed corresponding Course
            Course::create([
                'personal_data_id' => $person->id,
                'national_id_number' => $person->national_id_number,
                'birth_date' => $person->date_of_birth_gregorian,
                'phone' => $person->phone_1,
                'address' => $person->workplace,
                'governorate_id' => $person->governorate_id,
                'directorate_id' => $person->directorate_id,
                'sub_area_id' => $person->sub_area_id,
                'village_id' => $person->village_id,
                'course_type_id' => $courseTypeIds[$i % count($courseTypeIds)],
                'course_date_gregorian' => now()->subMonths(12 + $i)->format('Y-m-d'),
                'course_date_hijri' => (1445 - $i % 3) . '/05/15',
                'course_duration' => ($i % 3 + 1) . ' أشهر',
                'sending_entity_id' => $internalEntities[$i % count($internalEntities)],
                'course_administrator' => 'القائد ' . $supervisorNames[($i + 1) % count($supervisorNames)],
                'notes' => 'أتم الدورة التدريبية بنجاح وبدرجة ممتاز'
            ]);

            // Seed corresponding Arrest
            Arrest::create([
                'personal_data_id' => $person->id,
                'arresting_authority_id' => $arrestingAuthorityIds[$i % count($arrestingAuthorityIds)],
                'case_type_id' => $caseTypeIds[$i % count($caseTypeIds)],
                'arrest_date' => now()->subDays(100 + $i)->format('Y-m-d'),
                'location' => 'محافظة عدن - نقطة ' . ($i % 3 + 1),
                'arrest_location' => 'نقطة العلم العسكرية',
                'case_details' => 'تم ضبطه للاشتباه في وجود بيانات مكررة أو مستندات غير مكتملة، وتمت مراجعة حالته أصولاً.',
                'seized_items' => 'جهاز محمول، هاتف ذكي، بطاقة عسكرية منتهية الصلاحية',
                'case_follow_up_results' => 'تم استيفاء البيانات المطلوبة والتحقق من المرجعية العسكرية.',
                'related_information' => 'يتبع للواء الاستخبارات العسكرية الفرقة الأولى.',
                'status' => $i % 2 === 0 ? 'مفرج عنه' : 'قيد التحقيق المالي',
                'duration_period' => '5 أيام',
                'conclusion' => 'لا توجد سوابق أمنية خطيرة، يوصى بنقل خدماته.',
                'investigation_reports' => 'تقرير أولي رقم ' . ($i + 500) . ' صادر عن إدارة التحريات.',
                'attachments' => json_encode(['attachments/dummy_report.pdf']),
                'release_date' => now()->subDays(90 + $i)->format('Y-m-d'),
                'release_reason' => 'ثبوت سلامة الموقف الأمني بعد تقديم الضمانات',
                'notes' => 'سلوك المنضبط أثناء فترة التواجد والمراجعة.'
            ]);

            // Seed corresponding Suspect
            Suspect::create([
                'personal_data_id' => $person->id,
                'suspect_number' => 'S-' . (7000 + $i),
                'suspicion_date' => now()->subDays(50 + $i)->format('Y-m-d'),
                'suspicion_type' => $i % 2 === 0 ? 'اشتباه أمني وعسكري' : 'اشتباه مالي وإداري',
                'suspicion_location' => 'قطاع بئر أحمد العسكري',
                'suspicion_level' => $i % 3 === 0 ? 'مرتفع' : ($i % 3 === 1 ? 'متوسط' : 'منخفض'),
                'information_source' => 'بلاغات استخباراتية واردة من نقطة ' . ($i % 2 === 0 ? 'الرباط' : 'العلم'),
                'is_arrested' => $i % 3 === 0,
                'suspicion_details' => 'تكرار الاتصال بجهات غير معروفة أو تحركات غير مصرح بها خارج أوقات الخدمة الرسمية.',
                'monitoring_results' => 'الرقابة الميدانية كشفت عن انتظام سلوكه وتواجده في النقاط المقررة في معظم الأوقات.',
                'suspicion_follower' => 'الضابط المسؤول ' . $supervisorNames[($i + 3) % count($supervisorNames)],
                'suspicion_report' => 'تقرير الاشتباه الأمني الدوري رقم ' . ($i + 300),
                'observations' => 'ملتزم في الدوام العسكري بالزي الرسمي، والملاحظات قيد الاستقصاء والمتابعة المستمرة.',
                'attachments' => json_encode(['attachments/suspect_log.pdf'])
            ]);
        }

        // 9. Seed standalone 50 SecurityReports
        for ($i = 0; $i < 50; $i++) {
            $nationalId = '30' . str_pad($i + 1, 8, '0', STR_PAD_LEFT);
            $phone = '71' . str_pad($i + 1, 7, '0', STR_PAD_LEFT);
            $nameIdx = ($i + 17) % 50;

            SecurityReport::create([
                'full_name' => $maleNames[$nameIdx],
                'id_number' => $nationalId,
                'phone' => $phone,
                'report_type' => $i % 3 === 0 ? 'شكوى جنائية' : ($i % 3 === 1 ? 'اشتباه أمني' : 'بلاغ إداري'),
                'area' => 'مديرية المنصورة - مربع ' . ($i % 6 + 1),
                'military_number' => 'MR-' . (8000 + $i),
                'residence_village' => 'قرية الروضة - السكن رقم ' . ($i % 3 + 1),
                'notes' => 'بلاغ وارد من قيادة القطاع الشمالي رقم ' . ($i + 1000) . ' بخصوص مراجعة السيرة والسلوك.',
                'report_date' => now()->subDays($i)->format('Y-m-d'),
                'classification' => $i % 2 === 0 ? 'نشط' : 'قيد المتابعة والبحث الأولي',
                'internal_entity_id' => $internalEntities[$i % count($internalEntities)]
            ]);
        }

        // 10. Seed Dynamic Pages & Dynamic Records & Dynamic Record Values
        // Fetch first active user to assign as record creator (default superadmin is created in RoleSeeder)
        $creator = User::first();
        if (!$creator) {
            $creator = User::create([
                'username' => 'superadmin',
                'name' => 'المسؤول العام',
                'email' => 'admin@system.com',
                'password' => bcrypt('password'),
                'is_active' => true,
            ]);
        }

        // Ensure at least one dynamic page exists in the database
        $dynamicPages = Page::all();
        if ($dynamicPages->isEmpty()) {
            // Create default Section "نماذج إضافية"
            $section = Section::firstOrCreate(
                ['slug' => 'extra-forms'],
                [
                    'name' => 'نماذج إضافية',
                    'icon' => 'fa-folder',
                    'is_active' => true
                ]
            );

            // Create default dynamic Page
            $defaultPage = Page::create([
                'name' => 'سجل العتاد والعهدة العسكرية',
                'slug' => 'military-equipment',
                'section_id' => $section->id,
                'is_active' => true,
                'is_unique_id' => false
            ]);

            // Add fields to dynamic Page
            $fieldsToCreate = [
                [
                    'label' => 'نوع السلاح والعتاد',
                    'name' => 'weapon_type',
                    'type' => 'select',
                    'options' => ['كلاشينكوف روسي', 'مسدس كلوك 19', 'قناصة دراغونوف', 'جعبة عسكرية متكاملة'],
                    'is_required' => true,
                    'order_column' => 0
                ],
                [
                    'label' => 'الرقم التسلسلي للقطعة',
                    'name' => 'serial_number',
                    'type' => 'text',
                    'is_required' => true,
                    'order_column' => 1
                ],
                [
                    'label' => 'تاريخ استلام العهدة',
                    'name' => 'received_date',
                    'type' => 'date',
                    'is_required' => true,
                    'order_column' => 2
                ],
                [
                    'label' => 'مستوى الجودة والصلاحية',
                    'name' => 'quality_level',
                    'type' => 'select',
                    'options' => ['جديد كلياً', 'ممتاز', 'متوسط الصلاحية', 'يحتاج صيانة طفيفة'],
                    'is_required' => false,
                    'order_column' => 3
                ],
                [
                    'label' => 'ملاحظات الضابط المستلم',
                    'name' => 'officer_notes',
                    'type' => 'textarea',
                    'is_required' => false,
                    'order_column' => 4
                ],
            ];

            foreach ($fieldsToCreate as $fData) {
                $defaultPage->fields()->create($fData);
            }

            $dynamicPages = Page::where('id', $defaultPage->id)->get();
        }

        // Loop over all pages in dynamic_pages table, and generate exactly 50 records for each
        foreach ($dynamicPages as $dPage) {
            $dPageFields = $dPage->fields()->get();
            if ($dPageFields->isEmpty()) {
                continue;
            }

            for ($k = 0; $k < 50; $k++) {
                // Create a dynamic record
                $record = Record::create([
                    'page_id' => $dPage->id,
                    'created_by' => $creator->id,
                    'created_by_entity_id' => $internalEntities[$k % count($internalEntities)]
                ]);

                // Create a value for each field of this page
                foreach ($dPageFields as $field) {
                    $seededValue = '';

                    switch ($field->type) {
                        case 'select':
                            if (!empty($field->options) && is_array($field->options)) {
                                $seededValue = $field->options[array_rand($field->options)];
                            } else {
                                $seededValue = 'الخيار الأول';
                            }
                            break;

                        case 'number':
                            $seededValue = (string) rand(100, 99999);
                            break;

                        case 'date':
                        case 'datetime':
                            $seededValue = now()->subDays(rand(1, 180))->format('Y-m-d');
                            break;

                        case 'checkbox':
                            $seededValue = (string) rand(0, 1);
                            break;

                        case 'link':
                            $seededValue = 'https://system-portal.gov/records/' . rand(100, 999);
                            break;

                        case 'file':
                        case 'image':
                            $seededValue = 'dynamic_files/dummy_doc_' . rand(1, 5) . '.png';
                            break;

                        case 'textarea':
                            $seededValue = 'شرح تفصيلي وملاحظات عينية مسجلة في النظام بخصوص الحقل ' . $field->label . ' رقم ' . ($k + 1) . '، تم التحقق والتأكيد بواسطة الإدارة العامة للعتاد الحربي.';
                            break;

                        case 'text':
                        default:
                            if ($field->name === 'serial_number') {
                                $seededValue = 'SN-' . rand(100000, 999999);
                            } else {
                                $seededValue = 'بيان افتراضي للحقل ' . $field->label . ' رقم ' . ($k + 1);
                            }
                            break;
                    }

                    RecordValue::create([
                        'record_id' => $record->id,
                        'field_id' => $field->id,
                        'value' => $seededValue
                    ]);
                }
            }
        }
    }
}
