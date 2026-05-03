<?php

// app/Models/Village.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    protected $fillable = ['governorate_id', 'directorate_id', 'sub_area_id', 'name'];

    public function governorate() {
        return $this->belongsTo(Governorate::class);
    }

    public function directorate() {
        return $this->belongsTo(Directorate::class);
    }

    public function subArea() {
        return $this->belongsTo(SubArea::class);
    }
}
