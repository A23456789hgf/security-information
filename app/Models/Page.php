<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class Page extends Model
{
    use HasFactory;

    protected $table = 'dynamic_pages';

    public const AVAILABLE_OPERATIONS = [
        'view' => 'عرض',
        'create' => 'إضافة',
        'edit' => 'تعديل',
        'delete' => 'حذف',
        'restore' => 'استعادة',
        'force_delete' => 'حذف نهائي',
        'search' => 'بحث',
        'filter' => 'فلترة',
        'export' => 'تصدير',
        'import' => 'استيراد',
        'print' => 'طباعة',
        'download' => 'تحميل',
        'upload' => 'رفع ملفات',
        'approve' => 'اعتماد',
        'reject' => 'رفض',
        'send' => 'إرسال',
        'cancel' => 'إلغاء',
        'review' => 'مراجعة',
        'reopen' => 'إعادة فتح',
        'archive' => 'أرشفة',
        'change_status' => 'تغيير الحالة',
        'sync' => 'مزامنة',
        'execute' => 'تنفيذ إجراء',
        'view_log' => 'عرض السجل',
    ];

    protected $fillable = ['name', 'slug', 'section_id', 'is_active', 'is_unique_id', 'show_in_sidebar', 'operations', 'order_column', 'route_name', 'icon'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_unique_id' => 'boolean',
        'show_in_sidebar' => 'boolean',
        'operations' => 'array',
    ];

    protected static function booted()
    {
        static::created(function ($page) {
            \Illuminate\Support\Facades\Cache::forget('sidebar_dynamic_sections');
            \Illuminate\Support\Facades\Cache::forget('permissions_grouped');
            $operations = $page->operations ?? ['view', 'create', 'edit', 'delete'];
            foreach ($operations as $op) {
                Permission::firstOrCreate(['name' => "{$op}_{$page->slug}", 'guard_name' => 'web']);
            }
        });

        static::updated(function ($page) {
            \Illuminate\Support\Facades\Cache::forget('sidebar_dynamic_sections');
            \Illuminate\Support\Facades\Cache::forget('permissions_grouped');
            
            $operations = $page->operations ?? ['view', 'create', 'edit', 'delete'];
            
            // Ensure current operations exist
            foreach ($operations as $op) {
                Permission::firstOrCreate(['name' => "{$op}_{$page->slug}", 'guard_name' => 'web']);
            }

            if ($page->isDirty('slug')) {
                $oldSlug = $page->getOriginal('slug');
                // We should rename all possible available operations from old slug to new slug
                foreach (array_keys(self::AVAILABLE_OPERATIONS) as $op) {
                    $permission = Permission::where('name', "{$op}_{$oldSlug}")->first();
                    if ($permission) {
                        $permission->update(['name' => "{$op}_{$page->slug}"]);
                    }
                }
            }
        });

        static::deleted(function ($page) {
            \Illuminate\Support\Facades\Cache::forget('sidebar_dynamic_sections');
            \Illuminate\Support\Facades\Cache::forget('permissions_grouped');
            foreach (array_keys(self::AVAILABLE_OPERATIONS) as $op) {
                Permission::where('name', "{$op}_{$page->slug}")->delete();
            }
        });
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function fields()
    {
        return $this->hasMany(Field::class, 'page_id')->orderBy('order_column');
    }

    public function records()
    {
        return $this->hasMany(Record::class, 'page_id');
    }
}
