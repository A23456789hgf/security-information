<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DifferentiationLog extends Model
{
    use HasFactory;

    protected $table = 'differentiation_logs';

    protected $fillable = [
        'user_id',
        'action',
        'dataset_name',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

