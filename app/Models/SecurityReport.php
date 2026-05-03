<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_type',
        'area',
        'military_number',
        'full_name',
        'sub_unit',
        'detailed_unit',
        'salary',
        'notes',
        'date_of_birth',
        'original_governorate',
        'original_directorate',
        'original_village',
        'residence_governorate',
        'residence_directorate',
        'residence_village',
        'phone',
        'affiliation',
        'work',
        'unit',
        'procedures',
        'qualification',
        'marital_status',
        'number_of_children',
        'id_number',
        'police_station',
        'report_name',
        'report_date',
        'age',
        'gender',
        'educational_status',
        'arrest_status',
        'job_title',
        'positional_assets',
        'current_activity',
        'broker_name',
        'broker_number',
        'front_of_operation',
        'position_with_enemy',
        'classification',
        'status',
        'case_fate',
        'source_of_release',
        'date_of_release',
        'rank',
        'designation',
        'charge',
        'deposit_date',
        'detention_source',
        'arrest_date',
        'referral_authority',
        'case_classification',
        'additional_notes',
        'internal_entity_id',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'report_date' => 'date',
        'date_of_release' => 'date',
        'deposit_date' => 'date',
        'arrest_date' => 'date',
    ];
    public function entity()
    {
        return $this->belongsTo(InternalEntity::class, 'internal_entity_id');
    }
}
