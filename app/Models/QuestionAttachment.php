<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionAttachment extends Model
{
    protected $fillable = [
        'question_id',
        'type',
        'path',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
