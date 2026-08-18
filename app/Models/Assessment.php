<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject_id',
        'title',
        'type',
        'status',
        'assessment_date',
        'start_time',
        'room',
        'total_items',
        'score'
    ];

    // An assessment belongs to a specific subject
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
