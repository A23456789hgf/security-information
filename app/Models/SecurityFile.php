<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityFile extends Model
{
    use HasFactory;

    protected $table = 'security_files';

    protected $fillable = [
        'personal_data_id',
        'file_number',
        'file_date_gregorian',
        'file_date_hijri',
        'preparing_entity_id',
        'file_preparer',
        'highlight_summary',
        'jihadist_status',
        'summary_preparer',
        'summary_date_gregorian',
        'summary_date_hijri',
        'file_attachments',
        'summary_attachments',
    ];

    protected $casts = [
        'file_attachments' => 'array',
        'summary_attachments' => 'array',
        'file_date_gregorian' => 'date',
        'summary_date_gregorian' => 'date',
    ];

    public function personalData()
    {
        return $this->belongsTo(PersonalData::class, 'personal_data_id');
    }

    public function preparingEntity()
    {
        return $this->belongsTo(InternalEntity::class, 'preparing_entity_id');
    }
}
