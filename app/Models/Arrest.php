<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arrest extends Model
{
    use HasFactory;

    protected $fillable = [
        'personal_data_id',
        'arresting_authority_id',
        'case_type_id',
        'arrest_date',
        'location',
        'arrest_location',
        'case_details',
        'seized_items',
        'case_follow_up_results',
        'related_information',
        'status',
        'duration_period',
        'conclusion',
        'investigation_reports',
        'attachments',
        'release_date',
        'release_reason',
        'notes',
    ];

    protected $casts = [
        'arrest_date' => 'date',
        'release_date' => 'date',
        'attachments' => 'array',
    ];

    public function personalData()
    {
        return $this->belongsTo(PersonalData::class);
    }

    public function arrestingAuthority()
    {
        return $this->belongsTo(ArrestingAuthority::class);
    }

    public function caseType()
    {
        return $this->belongsTo(CaseType::class);
    }
}
