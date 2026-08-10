<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'title',
        'category',
        'path',
        'filename',
    ];

    /**
     * Get the subject that owns the file.
     */
    function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
