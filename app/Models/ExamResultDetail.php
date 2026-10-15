<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamResultDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_correct' => 'boolean',
        'score' => 'decimal:2',
    ];

    public function examResult(): BelongsTo
    {
        return $this->belongsTo(ExamResult::class, 'exam_result_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function selectedAnswer(): BelongsTo
    {
        return $this->belongsTo(Answer::class, 'selected_answer_id');
    }

    public function correctAnswer(): BelongsTo
    {
        return $this->belongsTo(Answer::class, 'correct_answer_id');
    }
}
