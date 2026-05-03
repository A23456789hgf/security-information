<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaritalStatus extends Model
{
    use HasFactory;

    // اسم الجدول (اختياري هنا لأن لارافل تستنتج الاسم تلقائياً)
    protected $table = 'marital_statuses';

    // الحقول القابلة للتعبئة (Mass assignable)
    protected $fillable = ['status'];
}
