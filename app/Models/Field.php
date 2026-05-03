<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $table = 'dynamic_fields';

    protected $fillable = [
        'page_id', 
        'name', 
        'label', 
        'type', 
        'is_required', 
        'options', 
        'metadata',
        'order_column'
    ];

    protected $casts = [
        'options' => 'array',
        'metadata' => 'json',
        'is_required' => 'boolean',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    public function values()
    {
        return $this->hasMany(RecordValue::class, 'field_id');
    }
}
