<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'schedule';

    protected $fillable = [
        'classroom_id',
        'from_time',
        'to_time',
        'cabinet_id',
        'subject_id',
        'day_of_week'
    ];
}
