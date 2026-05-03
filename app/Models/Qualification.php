<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
    use HasFactory;

    // اسم الجدول (لو حابب تحدده صراحةً، لكن مش ضروري هنا)
    protected $table = 'qualifications';

    // الحقول التي يمكن تعبئتها بشكل جماعي (mass assignable)
    protected $fillable = [
        'name',
    ];
}
