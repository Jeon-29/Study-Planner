<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    protected $fillable = [
        'user_id',
        'subject_id',
        'type',
        'room',
        'day_of_week',
        'start_time',
        'end_time',
        'is_recurring'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
