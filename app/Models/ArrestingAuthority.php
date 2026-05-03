<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArrestingAuthority extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function arrests()
    {
        return $this->hasMany(Arrest::class);
    }
}
