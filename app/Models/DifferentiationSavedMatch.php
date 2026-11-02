<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DifferentiationSavedMatch extends Model
{
    use HasFactory;

    protected $table = 'differentiation_saved_matches';

    protected $fillable = [
        'user_id',
        'title',
        'primary_source_type',
        'primary_source_id',
        'secondary_table_id',
        'key_fields',
        'field_mappings',
        'summary',
        'results_data',
    ];

    protected $casts = [
        'key_fields' => 'array',
        'field_mappings' => 'array',
        'summary' => 'array',
        'results_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function secondaryTable()
    {
        return $this->belongsTo(DataTable::class, 'secondary_table_id');
    }
}

