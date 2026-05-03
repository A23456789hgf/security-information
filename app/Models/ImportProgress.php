<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportProgress extends Model
{
    protected $table = 'import_progress';
    protected $fillable = [
        'session_id',
        'type',
        'total_rows',
        'processed_rows',
        'created_count',
        'updated_count',
        'failed_count',
        'status',
        'errors'
    ];

    protected $casts = [
        'errors' => 'array',
    ];

    public function getProgressPercentageAttribute()
    {
        if ($this->total_rows === 0) return 0;
        return round(($this->processed_rows / $this->total_rows) * 100, 2);
    }
}
