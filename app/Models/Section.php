<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'order_column', 'is_active'];

    public function pages()
    {
        return $this->hasMany(Page::class, 'section_id');
    }
}
