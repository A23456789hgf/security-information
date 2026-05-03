<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordValue extends Model
{
    use HasFactory;

    protected $table = 'dynamic_record_values';

    protected $fillable = ['record_id', 'field_id', 'value'];

    public function record()
    {
        return $this->belongsTo(Record::class, 'record_id');
    }

    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }
}
