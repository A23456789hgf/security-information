<?php

namespace Modules\Differences\Models;

use Illuminate\Database\Eloquent\Model;

class DifferenceAnalysisSetting extends Model
{
    protected $table = 'difference_analysis_settings';

    protected $fillable = [
        'similarity_threshold',
        'matching_rules',
        'excluded_words',
        'selected_strategy'
    ];

    protected $casts = [
        'matching_rules' => 'array',
        'excluded_words' => 'array'
    ];
}
