<?php

namespace Modules\Differences\Models;

use Illuminate\Database\Eloquent\Model;

class DifferenceSearchTarget extends Model
{
    protected $table = 'difference_search_targets';

    protected $fillable = [
        'name',
        'type',
        'source_identifier',
        'columns',
        'is_active'
    ];

    protected $casts = [
        'columns' => 'array',
        'is_active' => 'boolean'
    ];
}
