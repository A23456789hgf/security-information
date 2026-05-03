<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalEntity extends Model
{
    use HasFactory;

    protected $table = 'internal_entities';

    protected $fillable = ['name', 'governorate_id', 'directorate_id', 'parent_id'];

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    public function directorate()
    {
        return $this->belongsTo(Directorate::class);
    }

    public function parent()
    {
        return $this->belongsTo(InternalEntity::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(InternalEntity::class, 'parent_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
