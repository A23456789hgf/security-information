<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldResponse extends Model
{
    use HasFactory;

    protected $table = 'field_responses';

    protected $fillable = [
        'personal_data_id',
        'field_response',
        'response_date',
        'source',
        'source_job',
        'notes',
        'internal_entity_id',
        'created_by',
    ];

    protected $casts = [
        'response_date' => 'date',
    ];

    public function personalData()
    {
        return $this->belongsTo(PersonalData::class, 'personal_data_id');
    }

    public function entity()
    {
        return $this->belongsTo(InternalEntity::class, 'internal_entity_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
