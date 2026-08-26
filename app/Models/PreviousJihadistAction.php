<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreviousJihadistAction extends Model
{
    use HasFactory;

    protected $table = 'previous_jihadist_actions';

    protected $fillable = [
        'secondary_data_id',
        'activity',
        'activity_date',
        'duration',
        'location',
        'direct_supervisor',
        'leaving_reason',
    ];

    protected $casts = [
        'activity_date' => 'date',
    ];

    public function secondaryData()
    {
        return $this->belongsTo(SecondaryData::class);
    }
}
