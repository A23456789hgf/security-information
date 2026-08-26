<?php

namespace Modules\Differences\Models;

use Illuminate\Database\Eloquent\Model;

class DifferenceAnalysis extends Model
{
    protected $table = 'difference_analyses';

    protected $fillable = [
        'filename',
        'status',
        'progress',
        'total_rows',
        'processed_rows',
        'stats',
        'settings_snapshot'
    ];

    protected $casts = [
        'stats' => 'array',
        'settings_snapshot' => 'array'
    ];

    public function results()
    {
        return $this->hasMany(DifferenceAnalysisResult::class, 'analysis_id');
    }

    public function logs()
    {
        return $this->hasMany(DifferenceAnalysisLog::class, 'analysis_id');
    }
}
