<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $fillable = ['military_district_id', 'name'];

    public function district()
    {
        return $this->belongsTo(MilitaryDistrict::class, 'military_district_id');
    }
}
