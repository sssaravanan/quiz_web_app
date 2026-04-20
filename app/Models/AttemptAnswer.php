<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttemptAnswer extends Model
{
    protected $fillable = [
        'attempt_id',
        'question_id',
        'selected_option_id',
        'is_flagged',
    ];

    protected $casts = [
        'is_flagged' => 'boolean',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class, 'attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function selectedOption(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'selected_option_id');
    }

    public function getIsCorrectAttribute(): bool
    {
        // If no option was selected, it's incorrect
        if (!$this->selectedOption) {
            return false;
        }
        
        // Check if the selected option is marked as correct
        return $this->selectedOption->is_correct ?? false;
    }
}
