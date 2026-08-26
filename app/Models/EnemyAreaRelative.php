<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnemyAreaRelative extends Model
{
    use HasFactory;

    protected $table = 'enemy_area_relatives';

    protected $fillable = [
        'personal_data_id',
        'full_name',
        'kinship_id',
        'residence_place',
        'job',
        'political_affiliation_id',
        'ideological_affiliation_id',
        'communication_method',
        'relationship',
        'financial_status_id',
    ];

    public function personalData()
    {
        return $this->belongsTo(PersonalData::class, 'personal_data_id');
    }

    public function kinship()
    {
        return $this->belongsTo(Kinship::class, 'kinship_id');
    }

    public function politicalAffiliation()
    {
        return $this->belongsTo(PoliticalAffiliation::class, 'political_affiliation_id');
    }

    public function ideologicalAffiliation()
    {
        return $this->belongsTo(IdeologicalAffiliation::class, 'ideological_affiliation_id');
    }

    public function financialStatus()
    {
        return $this->belongsTo(FinancialStatus::class, 'financial_status_id');
    }
}
