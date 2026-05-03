<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'personal_data_id',
        'national_id_number',
        'birth_date',
        'phone',
        'address',
        'governorate_id',
        'directorate_id',
        'sub_area_id',
        'village_id',
        'course_type_id',
        'course_date_gregorian',
        'course_date_hijri',
        'course_duration',
        'sending_entity_id',
        'course_administrator',
        'notes',
    ];

    protected $casts = [
        'course_date_gregorian' => 'date',
        'birth_date' => 'date',
    ];

    public function personalData()
    {
        return $this->belongsTo(PersonalData::class);
    }

    public function courseType()
    {
        return $this->belongsTo(CourseType::class);
    }

    public function sendingEntity()
    {
        return $this->belongsTo(FillingAgency::class, 'sending_entity_id');
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
