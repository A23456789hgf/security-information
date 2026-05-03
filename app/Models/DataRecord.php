<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataRecord extends Model
{
    protected $fillable = ['data_table_id', 'data', 'sequence'];

    protected $casts = [
        'data' => 'array',
    ];

    public function dataTable()
    {
        return $this->belongsTo(DataTable::class);
    }
}
