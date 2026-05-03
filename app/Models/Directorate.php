<?php

// app/Models/Directorate.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Directorate extends Model
{
    use HasFactory;

    protected $fillable = ['governorate_id', 'name'];

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }
}
