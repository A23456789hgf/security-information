<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weapon extends Model
{
    use HasFactory;

    protected $fillable = [
        'secondary_data_id',
        'weapon_type',
        'number',
        'ownership',
        'tracking',
    ];

    public function secondaryData()
    {
        return $this->belongsTo(SecondaryData::class);
    }
}
