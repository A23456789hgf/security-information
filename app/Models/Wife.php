<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wife extends Model
{
    use HasFactory;

    protected $fillable = [
        'personal_data_id',
        'name',
        'status',
        'wife_id_number',
        'wife_birth_date',
        'wife_phone',
        'wife_address',
        'governorate_id',
        'directorate_id',
        'sub_area_id',
        'village_id',
    ];

    public function personalData()
    {
        return $this->belongsTo(PersonalData::class);
    }

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    public function directorate()
    {
        return $this->belongsTo(Directorate::class);
    }

    public function subArea()
    {
        return $this->belongsTo(SubArea::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }
}
