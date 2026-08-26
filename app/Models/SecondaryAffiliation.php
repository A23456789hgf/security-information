<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondaryAffiliation extends Model
{
    use HasFactory;

    protected $table = 'secondary_affiliations';

    protected $fillable = [
        'secondary_data_id',
        'party',
        'ideological_affiliation_id',
        'political_affiliation_id',
        'affiliation_date',
        'duration',
        'workplace',
        'job_type',
        'direct_supervisor',
        'leaving_date',
        'leaving_reason',
    ];

    protected $casts = [
        'affiliation_date' => 'date',
        'leaving_date' => 'date',
    ];

    public function secondaryData()
    {
        return $this->belongsTo(SecondaryData::class);
    }

    public function ideologicalAffiliation()
    {
        return $this->belongsTo(IdeologicalAffiliation::class, 'ideological_affiliation_id');
    }

    public function politicalAffiliation()
    {
        return $this->belongsTo(PoliticalAffiliation::class, 'political_affiliation_id');
    }
}
