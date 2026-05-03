<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalDataPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['personal_data_id', 'photo_path'];

    public function personalData()
    {
        return $this->belongsTo(PersonalData::class);
    }
}
