<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubArea extends Model
{
    protected $fillable = ['governorate_id', 'directorate_id', 'name'];

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    public function directorate()
    {
        return $this->belongsTo(Directorate::class);
    }
}
