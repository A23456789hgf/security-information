<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataTable extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'source',
        'description',
        'column_headers',
        'field_mappings',
        'records_count',
        'parent_table_id',
        'differentiation_results'
    ];

    protected $casts = [
        'column_headers' => 'array',
        'field_mappings' => 'array',
        'differentiation_results' => 'array',
    ];

    public function records()
    {
        return $this->hasMany(DataRecord::class, 'data_table_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function savedMatches()
    {
        return $this->hasMany(DifferentiationSavedMatch::class, 'secondary_table_id');
    }
}
