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
        'health_status_id',
        'male_children_count',
        'female_children_count',
        'total_children_count',
        'start_date',
        'previous_jihadist_activity',
        'current_jihadist_activity',
        'direct_supervisor',
    ];

    protected $casts = [
        'start_date' => 'date',
        'male_children_count' => 'integer',
        'female_children_count' => 'integer',
        'total_children_count' => 'integer',
    ];

    public function personalData()
    {
        return $this->belongsTo(PersonalData::class);
    }

    public function healthStatus()
    {
        return $this->belongsTo(HealthStatus::class, 'health_status_id');
    }

    public function weapons()
    {
        return $this->hasMany(Weapon::class, 'secondary_data_id');
    }

    public function affiliations()
    {
        return $this->hasMany(SecondaryAffiliation::class, 'secondary_data_id');
    }

    public function previousJihadistActions()
    {
        return $this->hasMany(PreviousJihadistAction::class, 'secondary_data_id');
    }
}
