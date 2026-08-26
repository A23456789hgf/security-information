<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relative extends Model
{
    use HasFactory;

    protected $table = 'relatives';

    protected $fillable = [
        'personal_data_id',
        'full_name',
        'age',
        'kinship_id',
        'current_job',
        'ideological_affiliation_id',
        'political_affiliation_id',
        'notes',
    ];

    protected $casts = [
        'age' => 'integer',
    ];

    /**
     * Relationship to the main person (PersonalData)
     */
    public function personalData()
    {
        return $this->belongsTo(PersonalData::class, 'personal_data_id');
    }

    /**
     * Relationship to Kinship
     */
    public function kinship()
    {
        return $this->belongsTo(Kinship::class, 'kinship_id');
    }

    /**
     * Relationship to Ideological Affiliation
     */
    public function ideologicalAffiliation()
    {
        return $this->belongsTo(IdeologicalAffiliation::class, 'ideological_affiliation_id');
    }

    /**
     * Relationship to Political Affiliation
     */
    public function politicalAffiliation()
    {
        return $this->belongsTo(PoliticalAffiliation::class, 'political_affiliation_id');
    }
}
