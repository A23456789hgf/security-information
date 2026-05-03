<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    use HasFactory;

    protected $table = 'ranks'; // اسم جدول قاعدة البيانات

    protected $fillable = [
        'rank',  // حقل الرتبة
    ];

    // إذا تريد تعيين المفتاح الأساسي غير id (عادة id تلقائي)
    // protected $primaryKey = 'id';

    // تعطيل الطابع الزمني إذا لم تستخدم created_at و updated_at
    // public $timestamps = false;
}
