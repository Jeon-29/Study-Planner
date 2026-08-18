<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'code', 'name', 'color_theme', 'semester', 'is_archived', 'instructor_name', 'instructor_email', 'consultation_hours'];

    protected $casts = ['is_archived' => 'boolean',];

    /**
     * Get all assignments tied to this specific subject code.
     */
    public function todos(): HasMany
    {
        // 2nd param: The column name on the 'todos' table (subject string)
        // 3rd param: The column name on the 'subjects' table (code string)
        return $this->hasMany(Todo::class, 'subject', 'code');
    }

    /**
     * The student profile owner.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }
}
