<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataTable extends Model
{
    protected $fillable = ['name', 'type', 'column_headers', 'parent_table_id', 'differentiation_results'];

    protected $casts = [
        'column_headers' => 'array',
        'differentiation_results' => 'array',
    ];

    public function records()
    {
        return $this->hasMany(DataRecord::class);
    }
}
