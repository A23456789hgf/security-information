<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'order_column', 'is_active'];

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('sidebar_dynamic_sections');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('sidebar_dynamic_sections');
        });
    }

    public function pages()
    {
        return $this->hasMany(Page::class, 'section_id');
    }
}
