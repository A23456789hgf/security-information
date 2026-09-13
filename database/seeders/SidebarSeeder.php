<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\Page;
use Illuminate\Database\Seeder;

class SidebarSeeder extends Seeder
{
    public function run()
    {
        $sections = [
            [
                'name' => 'إدارة الأفراد',
                'slug' => 'personnel-management',
                'icon' => 'fa-users',
                'order_column' => 1,
                'pages' => [
                    ['name' => 'البيانات الشخصية', 'route_name' => 'system.personal-data.index', 'icon' => 'fa-user'],
                    ['name' => 'البيانات الثانوية', 'route_name' => 'system.secondary-data.index', 'icon' => 'fa-id-card'],
                    ['name' => 'بيانات الزوجات', 'route_name' => 'system.wives.index', 'icon' => 'fa-female'],
                    ['name' => 'الأهل والأقارب', 'route_name' => 'system.relatives.index', 'icon' => 'fa-users'],
                    ['name' => 'الملف الأمني', 'route_name' => 'system.security-files.index', 'icon' => 'fa-shield-alt'],
                    ['name' => 'الدورات التدريبية', 'route_name' => 'system.courses.index', 'icon' => 'fa-graduation-cap'],
                ]
            ],
            [
                'name' => 'السجلات الأمنية',
                'slug' => 'security-records',
                'icon' => 'fa-folder-open',
                'order_column' => 2,
                'pages' => [
                    ['name' => 'سجلات الضبط', 'route_name' => 'system.arrests.index', 'icon' => 'fa-gavel'],
                    ['name' => 'المشتبه بهم', 'route_name' => 'system.suspicious.index', 'icon' => 'fa-user-secret'],
                    ['name' => 'البلاغات الأمنية', 'route_name' => 'system.security-reports.index', 'icon' => 'fa-bullhorn'],
                ]
            ],
            [
                'name' => 'أدوات البيانات',
                'slug' => 'data-tools',
                'icon' => 'fa-tools',
                'order_column' => 3,
                'pages' => [
                    ['name' => 'إدارة الأقسام', 'route_name' => 'system.sections.index', 'icon' => 'fa-layer-group'],
                    ['name' => 'الصفحات الديناميكية', 'route_name' => 'system.pages.index', 'icon' => 'fa-file-alt'],
                    ['name' => 'استيراد البيانات', 'route_name' => 'data.import.index', 'icon' => 'fa-file-import'],
                    ['name' => 'مقارنة وتمايز البيانات', 'route_name' => 'data.differentiation.index', 'icon' => 'fa-code-branch'],
                ]
            ],
            [
                'name' => 'إدارة المنظومة والترميزات',
                'slug' => 'system-configurations',
                'icon' => 'fa-cogs',
                'order_column' => 4,
                'pages' => [
                    // الجغرافية
                    ['name' => 'الدول', 'route_name' => 'configuration.countries.index', 'icon' => 'fa-globe'],
                    ['name' => 'المحافظات', 'route_name' => 'configuration.governorates.index', 'icon' => 'fa-map-marker-alt'],
                    ['name' => 'المديريات', 'route_name' => 'configuration.directorates.index', 'icon' => 'fa-map-pin'],
                    ['name' => 'العزل والمناطق', 'route_name' => 'configuration.sub-areas.index', 'icon' => 'fa-map-signs'],
                    ['name' => 'القرى والمحلات', 'route_name' => 'configuration.villages.index', 'icon' => 'fa-home'],
                    
                    // الحالة
                    ['name' => 'فصائل الدم', 'route_name' => 'configuration.blood-types.index', 'icon' => 'fa-tint'],
                    ['name' => 'الحالات الصحية', 'route_name' => 'configuration.health-statuses.index', 'icon' => 'fa-heartbeat'],
                    ['name' => 'الحالات الاجتماعية', 'route_name' => 'configuration.marital-statuses.index', 'icon' => 'fa-user-friends'], // maybe user-friends
                    ['name' => 'المؤهلات الدراسية', 'route_name' => 'configuration.qualifications.index', 'icon' => 'fa-certificate'],
                    ['name' => 'صلات القرابة', 'route_name' => 'configuration.kinships.index', 'icon' => 'fa-people-arrows'],
                    ['name' => 'الانتماءات العقائدية', 'route_name' => 'configuration.ideological-affiliations.index', 'icon' => 'fa-pray'],
                    ['name' => 'الانتماءات السياسية', 'route_name' => 'configuration.political-affiliations.index', 'icon' => 'fa-flag'],
                    
                    // العسكرية والمهنية
                    ['name' => 'الرتب العسكرية', 'route_name' => 'configuration.ranks.index', 'icon' => 'fa-star'],
                    ['name' => 'الحالات الوظيفية', 'route_name' => 'configuration.job-statuses.index', 'icon' => 'fa-briefcase'],
                    ['name' => 'المناطق العسكرية', 'route_name' => 'configuration.military-districts.index', 'icon' => 'fa-shield-alt'],
                    ['name' => 'الشعب والوحدات', 'route_name' => 'configuration.divisions.index', 'icon' => 'fa-sitemap'],
                    ['name' => 'المحاور الجبهوية', 'route_name' => 'configuration.axes.index', 'icon' => 'fa-crosshairs'],
                    ['name' => 'جهات الضبط', 'route_name' => 'configuration.arresting-authorities.index', 'icon' => 'fa-building'],
                    ['name' => 'أنواع القضايا', 'route_name' => 'configuration.case-types.index', 'icon' => 'fa-balance-scale'],
                ]
            ],
            [
                'name' => 'الإدارة والصلاحيات',
                'slug' => 'administration-permissions',
                'icon' => 'fa-user-shield',
                'order_column' => 5,
                'pages' => [
                    ['name' => 'إدارة المستخدمين', 'route_name' => 'system.users.index', 'icon' => 'fa-users-cog'],
                    ['name' => 'الأدوار والصلاحيات', 'route_name' => 'system.roles.index', 'icon' => 'fa-key'],
                    ['name' => 'الجهات والأقسام', 'route_name' => 'system.entities.index', 'icon' => 'fa-sitemap'],
                ]
            ],
        ];

        foreach ($sections as $secData) {
            $pagesData = $secData['pages'];
            unset($secData['pages']);

            // Create or update section
            $section = Section::updateOrCreate(
                ['slug' => $secData['slug']],
                $secData
            );

            foreach ($pagesData as $index => $pageData) {
                // Ensure slug is unique, we use the route name for slug usually, or a slugified name
                $slug = str_replace('.', '-', $pageData['route_name']);
                
                Page::updateOrCreate(
                    ['route_name' => $pageData['route_name']],
                    [
                        'name' => $pageData['name'],
                        'slug' => $slug,
                        'section_id' => $section->id,
                        'icon' => $pageData['icon'],
                        'order_column' => $index + 1,
                        'is_active' => true,
                        'show_in_sidebar' => true,
                        // Built-in pages do not necessarily need operations via dynamic_pages table,
                        // but we provide the basic ones to prevent errors.
                        'operations' => ['view', 'create', 'edit', 'delete'],
                    ]
                );
            }
        }
    }
}
