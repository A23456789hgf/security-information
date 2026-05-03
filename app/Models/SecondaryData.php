<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondaryData extends Model
{
    use HasFactory;

    protected $table = 'secondary_data';

    protected $fillable = [
        'personal_data_id',
        'start_date',
        'previous_jihadist_activity',
        'current_jihadist_activity',
        'direct_supervisor',
    ];

    protected $casts = [
        'start_date' => 'date',
    ];

    public function personalData()
    {
        return $this->belongsTo(PersonalData::class);
    }

    public function weapons()
    {
        return $this->hasMany(Weapon::class);
    }
}
