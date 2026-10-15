<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ExamResultDetail;
use App\Models\Question;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ExamResultSeeder extends Seeder
{
    public function run(): void
    {
        $student = Student::first();
        if (! $student) {
            return;
        }

        // Clean previous results
        Schema::disableForeignKeyConstraints();
        ExamResultDetail::truncate();
        ExamResult::truncate();
        DB::table('exam_subject')->truncate();
        Schema::enableForeignKeyConstraints();

        $matematika = Subject::where('name', 'like', '%Matematika%')->first();
        $bIndo = Subject::where('name', 'like', '%Bahasa Indonesia%')->first();
        $ipa = Subject::where('name', 'like', '%IPA%')->first();

        $exam1 = Exam::find(1);
        if ($exam1 && $bIndo) {
            $exam1->subjects()->syncWithoutDetaching([
                $bIndo->id => ['qty' => 5],
            ]);
        }

        $exam2 = Exam::find(2);
        if ($exam2 && $matematika) {
            $exam2->subjects()->syncWithoutDetaching([
                $matematika->id => ['qty' => 10],
            ]);
        }

        // Create third exam for IPA if not exists
        $exam3 = Exam::updateOrCreate(
            ['title' => 'Simulasi Ujian IPA Terpadu'],
            [
                'duration' => 60,
                'threshold' => 60.00,
                'exact_time' => false,
                'started_at' => Carbon::now()->subDays(5),
                'expired_at' => Carbon::now()->addDays(20),
                'is_available' => true,
            ]
        );

        if ($ipa) {
            $exam3->subjects()->syncWithoutDetaching([
                $ipa->id => ['qty' => 10],
            ]);
        }

        $exams = Exam::with('subjects')->get();

        foreach ($exams as $exam) {
            foreach ($exam->subjects as $subject) {
                $questions = Question::where('subject_id', $subject->id)
                    ->where('is_active', true)
                    ->with('answers')
                    ->take($subject->pivot->qty ?? 5)
                    ->get();

                if ($questions->isEmpty()) {
                    continue;
                }

                $totalQuestions = $questions->count();
                $correctAnswers = 0;
                $wrongAnswers = 0;
                $unanswered = 0;
                $totalScoreEarned = 0;
                $maxPossibleScore = 0;

                $detailsData = [];

                foreach ($questions as $index => $question) {
                    $questionWeight = (float) ($question->score ?? 1);
                    $maxPossibleScore += $questionWeight;

                    $answers = $question->answers;
                    $correctAnswer = $answers->firstWhere('is_correct', true);

                    // Varied scores for different exams
                    $shouldBeCorrect = true;
                    if ($exam->id == 1) {
                        // 4 correct, 1 wrong (80%)
                        $shouldBeCorrect = ($index !== 1);
                    } elseif ($exam->id == 2) {
                        // 8 correct, 2 wrong (80%)
                        $shouldBeCorrect = ($index % 5 !== 0);
                    } else {
                        // 5 correct, 4 wrong, 1 unanswered (50% remedial)
                        if ($index === 9) {
                            $unanswered++;
                            $detailsData[] = [
                                'question_id' => $question->id,
                                'selected_answer_id' => null,
                                'correct_answer_id' => $correctAnswer?->id,
                                'student_answer_text' => null,
                                'correct_answer_text' => $correctAnswer?->text,
                                'is_correct' => false,
                                'score' => 0,
                            ];
                            continue;
                        }
                        $shouldBeCorrect = ($index % 2 === 0);
                    }

                    if ($shouldBeCorrect && $correctAnswer) {
                        $selectedAnswer = $correctAnswer;
                        $isCorrect = true;
                        $scoreGained = $questionWeight;
                        $correctAnswers++;
                        $totalScoreEarned += $questionWeight;
                    } else {
                        $wrongChoice = $answers->firstWhere('is_correct', false);
                        $selectedAnswer = $wrongChoice ?? $answers->first();
                        $isCorrect = false;
                        $scoreGained = 0;
                        $wrongAnswers++;
                    }

                    $detailsData[] = [
                        'question_id' => $question->id,
                        'selected_answer_id' => $selectedAnswer?->id,
                        'correct_answer_id' => $correctAnswer?->id,
                        'student_answer_text' => $selectedAnswer?->text,
                        'correct_answer_text' => $correctAnswer?->text,
                        'is_correct' => $isCorrect,
                        'score' => $scoreGained,
                    ];
                }

                $finalScore = $maxPossibleScore > 0 
                    ? round(($totalScoreEarned / $maxPossibleScore) * 100, 2) 
                    : 0;

                $isPassed = $finalScore >= (float) ($exam->threshold ?? 50);

                $examDate = Carbon::now()->subDays(rand(1, 10))->subHours(rand(1, 4));
                $startedAt = (clone $examDate)->subMinutes($exam->duration ?? 45);

                $result = ExamResult::create([
                    'student_id' => $student->id,
                    'exam_id' => $exam->id,
                    'subject_id' => $subject->id,
                    'exam_date' => $examDate,
                    'started_at' => $startedAt,
                    'finished_at' => $examDate,
                    'duration_spent_minutes' => min($exam->duration ?? 45, 30),
                    'total_questions' => $totalQuestions,
                    'correct_answers' => $correctAnswers,
                    'wrong_answers' => $wrongAnswers,
                    'unanswered' => $unanswered,
                    'score' => $finalScore,
                    'is_passed' => $isPassed,
                    'status' => 'completed',
                ]);

                foreach ($detailsData as $detail) {
                    $detail['exam_result_id'] = $result->id;
                    ExamResultDetail::create($detail);
                }
            }
        }
    }
}
