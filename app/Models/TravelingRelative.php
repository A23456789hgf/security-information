<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelingRelative extends Model
{
    use HasFactory;

    protected $table = 'traveling_relatives';

    protected $fillable = [
        'personal_data_id',
        'full_name',
        'age',
        'kinship_id',
        'travel_reason',
        'current_job',
        'workplace',
        'affiliated_entity',
        'country_id',
        'political_affiliation_id',
        'ideological_affiliation_id',
        'march_impression',
    ];

    protected $casts = [
        'age' => 'integer',
    ];

    public function personalData()
    {
        return $this->belongsTo(PersonalData::class, 'personal_data_id');
    }

    public function kinship()
    {
        return $this->belongsTo(Kinship::class, 'kinship_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function politicalAffiliation()
    {
        return $this->belongsTo(PoliticalAffiliation::class, 'political_affiliation_id');
    }

    public function ideologicalAffiliation()
    {
        return $this->belongsTo(IdeologicalAffiliation::class, 'ideological_affiliation_id');
    }
}
