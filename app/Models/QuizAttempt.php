<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'quiz_id',
        'score',
        'total_questions',
        'status',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public $appends = ['time_taken'];

    /**
     * Override update to prevent started_at from being modified after creation
     */
    public function update(array $attributes = [], array $options = [])
    {
        // Remove started_at from update to prevent accidental overwrites
        unset($attributes['started_at']);
        return parent::update($attributes, $options);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(AttemptAnswer::class, 'attempt_id');
    }

    public function getTimeTakenAttribute(): ?string
    {
        if ($this->started_at && $this->completed_at) {
            $seconds = (int) $this->started_at->diffInSeconds($this->completed_at);

            $minutes = floor($seconds / 60);
            $remainingSeconds = $seconds % 60;

            return "{$minutes}m {$remainingSeconds}s";
        }

        return null;
    }
}
