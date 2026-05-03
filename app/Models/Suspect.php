<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suspect extends Model
{
    use HasFactory;

    protected $fillable = [
        'personal_data_id',
        'suspect_number',
        'suspicion_date',
        'suspicion_type',
        'suspicion_location',
        'suspicion_level',
        'information_source',
        'is_arrested',
        'suspicion_details',
        'monitoring_results',
        'suspicion_follower',
        'suspicion_report',
        'observations',
        'attachments',
    ];

    protected $casts = [
        'suspicion_date' => 'date',
        'is_arrested' => 'boolean',
        'attachments' => 'array',
    ];

    public function personalData()
    {
        return $this->belongsTo(PersonalData::class);
    }
}
