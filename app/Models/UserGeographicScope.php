<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGeographicScope extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'governorate_id',
        'directorate_id',
        'scope_type', // 'specific', 'all', 'none'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    public function directorate()
    {
        return $this->belongsTo(Directorate::class);
    }
}

