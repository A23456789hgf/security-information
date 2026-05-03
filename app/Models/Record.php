<?php

namespace App\Models;

use App\Traits\HasEntityVisibility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory, HasEntityVisibility;

    protected $table = 'dynamic_records';

    protected $fillable = ['page_id', 'created_by', 'created_by_entity_id'];

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function values()
    {
        return $this->hasMany(RecordValue::class, 'record_id');
    }

    public function entity()
    {
        return $this->belongsTo(InternalEntity::class, 'created_by_entity_id');
    }
}
