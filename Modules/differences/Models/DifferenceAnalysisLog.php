<?php

namespace Modules\Differences\Models;

use Illuminate\Database\Eloquent\Model;

class DifferenceAnalysisLog extends Model
{
    protected $table = 'difference_analysis_logs';

    protected $fillable = [
        'analysis_id',
        'level',
        'message',
        'details'
    ];

    public function analysis()
    {
        return $this->belongsTo(DifferenceAnalysis::class, 'analysis_id');
    }
}
