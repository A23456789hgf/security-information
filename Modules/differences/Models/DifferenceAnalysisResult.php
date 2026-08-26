<?php

namespace Modules\Differences\Models;

use Illuminate\Database\Eloquent\Model;

class DifferenceAnalysisResult extends Model
{
    protected $table = 'difference_analysis_results';

    protected $fillable = [
        'analysis_id',
        'external_name',
        'external_row_data',
        'status',
        'similarity_percentage',
        'matching_name',
        'source_target_id',
        'source_target_name',
        'source_table',
        'source_page',
        'source_page_type',
        'occurrences_count',
        'record_status',
        'original_record_id',
        'original_record_data',
        'all_matches',
        'matching_details'
    ];

    protected $casts = [
        'external_row_data' => 'array',
        'original_record_data' => 'array',
        'all_matches' => 'array',
        'matching_details' => 'array',
        'similarity_percentage' => 'float'
    ];

    public function analysis()
    {
        return $this->belongsTo(DifferenceAnalysis::class, 'analysis_id');
    }
}
