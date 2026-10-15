<?php

namespace App\Filament\Test\Resources\Exams\Pages;

use App\Filament\Test\Resources\ExamHistories\ExamHistoryResource;
use App\Filament\Test\Resources\Exams\ExamResource;
use App\Models\Answer;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ExamResultDetail;
use App\Models\Question;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StartingExam extends Page
{
    protected static string $resource = ExamResource::class;

    protected string $view = 'filament.test.resources.exams.pages.starting-exam';

    public ?Exam $exam = null;

    public array $collections = [];

    public array $userAnswers = [];

    public ?string $startedAt = null;

    public int $totalQuestions = 0;

    public function mount(Exam $exam): void
    {
        $this->exam = $exam;
        $this->startedAt = now()->toDateTimeString();

        $pelajarans = $exam->subjects;

        foreach ($pelajarans as $mapel) {
            $soals = $mapel->questions()
                ->where('is_active', true)
                ->with(['answers' => fn ($query) => $query->where('is_active', true)->inRandomOrder()])
                ->inRandomOrder()
                ->limit($mapel->pivot->qty ?? 10)
                ->get()
                ->toArray();

            $this->totalQuestions += count($soals);

            $this->collections[] = [
                'id' => $mapel->id,
                'name' => $mapel->name,
                'soals' => $soals,
            ];
        }
    }

    public function submitExam(): void
    {
        $student = auth()->user()?->student;

        if (! $student) {
            Notification::make()
                ->title('Gagal!')
                ->body('Data profil siswa tidak ditemukan.')
                ->danger()
                ->send();

            return;
        }

        DB::transaction(function () use ($student) {
            $totalQuestions = 0;
            $correctAnswers = 0;
            $wrongAnswers = 0;
            $unanswered = 0;
            $totalScoreEarned = 0;
            $maxPossibleScore = 0;

            $detailsToInsert = [];

            // Primary subject
            $primarySubjectId = $this->collections[0]['id'] ?? null;

            foreach ($this->collections as $pelajaran) {
                foreach ($pelajaran['soals'] as $pertanyaan) {
                    $totalQuestions++;
                    $questionId = $pertanyaan['id'];
                    $selectedAnswerId = $this->userAnswers[$questionId] ?? null;

                    // Question score weight
                    $questionWeight = (float) ($pertanyaan['score'] ?? 1);
                    $maxPossibleScore += $questionWeight;

                    // Find correct answer from options
                    $correctAnswer = null;
                    $selectedAnswerText = null;
                    $correctAnswerText = null;

                    foreach ($pertanyaan['answers'] as $ans) {
                        if ($ans['is_correct']) {
                            $correctAnswer = $ans;
                            $correctAnswerText = $ans['text'];
                        }
                        if ($selectedAnswerId && $ans['id'] == $selectedAnswerId) {
                            $selectedAnswerText = $ans['text'];
                        }
                    }

                    $isCorrect = false;
                    $scoreGained = 0;

                    if ($selectedAnswerId) {
                        if ($correctAnswer && $selectedAnswerId == $correctAnswer['id']) {
                            $isCorrect = true;
                            $scoreGained = $questionWeight;
                            $correctAnswers++;
                            $totalScoreEarned += $questionWeight;
                        } else {
                            $wrongAnswers++;
                        }
                    } else {
                        $unanswered++;
                    }

                    $detailsToInsert[] = [
                        'question_id' => $questionId,
                        'selected_answer_id' => $selectedAnswerId,
                        'correct_answer_id' => $correctAnswer['id'] ?? null,
                        'student_answer_text' => $selectedAnswerText,
                        'correct_answer_text' => $correctAnswerText,
                        'is_correct' => $isCorrect,
                        'score' => $scoreGained,
                    ];
                }
            }

            // Calculate percentage score (0 - 100)
            $finalScore = $maxPossibleScore > 0 
                ? round(($totalScoreEarned / $maxPossibleScore) * 100, 2) 
                : 0;

            $isPassed = $finalScore >= (float) ($this->exam->threshold ?? 50);

            $started = Carbon::parse($this->startedAt);
            $finished = now();
            $durationMinutes = max(1, $started->diffInMinutes($finished));

            $examResult = ExamResult::create([
                'student_id' => $student->id,
                'exam_id' => $this->exam->id,
                'subject_id' => $primarySubjectId,
                'exam_date' => $finished,
                'started_at' => $started,
                'finished_at' => $finished,
                'duration_spent_minutes' => $durationMinutes,
                'total_questions' => $totalQuestions,
                'correct_answers' => $correctAnswers,
                'wrong_answers' => $wrongAnswers,
                'unanswered' => $unanswered,
                'score' => $finalScore,
                'is_passed' => $isPassed,
                'status' => 'completed',
            ]);

            foreach ($detailsToInsert as $detail) {
                $detail['exam_result_id'] = $examResult->id;
                ExamResultDetail::create($detail);
            }

            Notification::make()
                ->title('Ujian Berhasil Diselesaikan!')
                ->body("Skor Anda: {$finalScore} (" . ($isPassed ? 'LULUS' : 'REMEDIAL') . ')')
                ->success()
                ->send();

            $this->redirect(ExamHistoryResource::getUrl('view', ['record' => $examResult->id]));
        });
    }
}
