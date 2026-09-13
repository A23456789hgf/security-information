<?php

namespace App\Helpers;

use App\Models\Page;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;

class PermissionHelper
{
    public static function getGrouped()
    {
        return Cache::remember('permissions_grouped', 3600, function () {
            // 1. Dynamic Domains Grouping
            $domains = [
                'system' => 'إدارة النظام',
                'configuration' => 'الإعدادات العامة (التكويد)',
            ];
            $grouped = [];
            foreach ($domains as $dir => $label) {
                $grouped[$label] = self::scanDirectory($dir);
            }

            // 2. Add Dynamic Page Permissions (from database)
            $dynamicPages = Page::all();
            $dynamicGroupLabel = 'صفحات مخصصة (ديناميكية)';
            if ($dynamicPages->isNotEmpty()) {
                foreach ($dynamicPages as $page) {
                    $pagePermissions = [];
                    $operations = $page->operations ?? ['view', 'create', 'edit', 'delete']; // Fallback for old pages
                    
                    foreach ($operations as $op) {
                        $permName = "{$op}_{$page->slug}";
                        $permission = Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
                        $pagePermissions[$op] = $permission;
                    }
                    $grouped[$dynamicGroupLabel][] = [
                        'name' => $page->name,
                        'slug' => $page->slug,
                        'permissions' => $pagePermissions,
                    ];
                }
            }

            return $grouped;
        });
    }

    private static function scanDirectory($directory)
    {
        $viewPath = resource_path('views/'.$directory);
        if (! is_dir($viewPath)) {
            return [];
        }

        $translations = [
            // Configuration
            'arresting_authorities' => 'جهات الضبط',
            'blood_types' => 'فصائل الدم',
            'case_types' => 'أنواع القضايا',
            'countries' => 'الدول',
            'course_types' => 'أنواع الدورات',
            'department' => 'الإدارات',
            'directorates' => 'المديريات',
            'divisions' => 'الأقسام',
            'electronic_inputs' => 'المدخلات الإلكترونية',
            'filling_agencies' => 'جهات الملء',
            'financial_status' => 'الحالة المالية',
            'governorates' => 'المحافظات',
            'job_status' => 'الحالة الوظيفية',
            'marital_statuses' => 'الحالة الاجتماعية',
            'military_districts' => 'المناطق العسكرية',
            'number_classification' => 'تصنيف الأرقام',
            'qualifications' => 'المؤهلات',
            'ranks' => 'الرتب',
            'sub_areas' => 'المناطق الفرعية',
            'village' => 'القرى',

            // System
            'cases' => 'القضايا والبلاغات',
            'courses' => 'الدورات والتدريب',
            'dynamic_data' => 'البيانات الديناميكية',
            'entities' => 'جهات العمل / الكيانات',
            'pages' => 'إدارة الصفحات',
            'personal_data' => 'بيانات الأفراد',
            'profile' => 'الملف الشخصي',
            'reports' => 'التقارير والإحصائيات',
            'roles' => 'الأدوار والصلاحيات',
            'sections' => 'الأقسام والوحدات',
            'suspicious' => 'بيانات المشبوهين',
            'users' => 'إدارة المستخدمين',
            'wives' => 'بيانات العائلات / الزوجات',
        ];

        $items = [];
        $dirs = array_filter(glob($viewPath.'/*'), 'is_dir');

        foreach ($dirs as $dir) {
            $folderName = basename($dir);
            if ($folderName === 'partials') {
                continue;
            }

            $slug = $directory.'_'.$folderName;
            $displayName = $translations[$folderName] ?? $folderName;

            $pagePermissions = [];
            foreach (['view', 'create', 'edit', 'delete'] as $op) {
                $permName = "{$op}_{$slug}";
                $permission = Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
                $pagePermissions[$op] = $permission;
            }

            $items[] = [
                'name' => $displayName,
                'slug' => $slug,
                'permissions' => $pagePermissions,
            ];
        }

        return $items;
    }
}
