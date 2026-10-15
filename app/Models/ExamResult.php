<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamResult extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'exam_date' => 'datetime',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'score' => 'decimal:2',
        'is_passed' => 'boolean',
        'duration_spent_minutes' => 'integer',
        'total_questions' => 'integer',
        'correct_answers' => 'integer',
        'wrong_answers' => 'integer',
        'unanswered' => 'integer',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(ExamResultDetail::class, 'exam_result_id');
    }
}
