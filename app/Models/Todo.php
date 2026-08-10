<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Todo extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'subject',
        'description',
        'due_date',
        'due_time',
        'priority',
        'is_completed',
        'status',
    ];

    // Establish relationship back to the User model
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subjectDetails()
    {
        // 2nd param: The foreign key column string on the 'todos' table
        // 3rd param: The owner key column string on the 'subjects' table
        return $this->belongsTo(Subject::class, 'subject', 'code');
    }

}
