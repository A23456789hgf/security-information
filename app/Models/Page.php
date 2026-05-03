<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class Page extends Model
{
    use HasFactory;

    protected $table = 'dynamic_pages';

    protected $fillable = ['name', 'slug', 'section_id', 'is_active', 'is_unique_id'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_unique_id' => 'boolean',
    ];

    protected static function booted()
    {
        static::created(function ($page) {
            foreach (['view', 'create', 'edit', 'delete'] as $op) {
                Permission::firstOrCreate(['name' => "{$op}_{$page->slug}", 'guard_name' => 'web']);
            }
        });

        static::updated(function ($page) {
            if ($page->isDirty('slug')) {
                $oldSlug = $page->getOriginal('slug');
                foreach (['view', 'create', 'edit', 'delete'] as $op) {
                    $permission = Permission::where('name', "{$op}_{$oldSlug}")->first();
                    if ($permission) {
                        $permission->update(['name' => "{$op}_{$page->slug}"]);
                    }
                }
            }
        });

        static::deleted(function ($page) {
            foreach (['view', 'create', 'edit', 'delete'] as $op) {
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
