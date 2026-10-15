<?php

namespace App\Filament\Test\Pages;

use App\Models\ExamResult;
use App\Models\Subject;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Livewire\Attributes\Url;

class StudentSubjectHistory extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'Histori Pelajaran';

    protected static ?string $title = 'Histori Pelajaran';

    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.test.pages.student-subject-history';

    #[Url]
    public ?int $selectedSubjectId = null;

    public function mount(): void
    {
        if (! $this->selectedSubjectId) {
            $firstSubject = Subject::first();
            $this->selectedSubjectId = $firstSubject?->id;
        }
    }

    public function selectSubject(?int $subjectId): void
    {
        $this->selectedSubjectId = $subjectId;
    }

    public function getViewData(): array
    {
        $studentId = auth()->user()?->student?->id;

        $subjects = Subject::withCount(['questions', 'exams'])->get();

        $selectedSubject = $this->selectedSubjectId 
            ? Subject::with(['questions'])->find($this->selectedSubjectId) 
            : null;

        $resultsQuery = ExamResult::with(['exam', 'subject'])
            ->where('student_id', $studentId);

        if ($this->selectedSubjectId) {
            $resultsQuery->where('subject_id', $this->selectedSubjectId);
        }

        $results = $resultsQuery->orderBy('exam_date', 'desc')->get();

        // Calculate student's personal statistics on this subject
        $totalExamsTaken = $results->count();
        $averageScore = $totalExamsTaken > 0 ? round($results->avg('score'), 1) : 0;
        $highestScore = $totalExamsTaken > 0 ? $results->max('score') : 0;
        $lowestScore = $totalExamsTaken > 0 ? $results->min('score') : 0;
        $passedCount = $results->where('is_passed', true)->count();
        $passRate = $totalExamsTaken > 0 ? round(($passedCount / $totalExamsTaken) * 100, 1) : 0;

        return [
            'subjects' => $subjects,
            'selectedSubject' => $selectedSubject,
            'results' => $results,
            'stats' => [
                'totalExamsTaken' => $totalExamsTaken,
                'averageScore' => $averageScore,
                'highestScore' => $highestScore,
                'lowestScore' => $lowestScore,
                'passedCount' => $passedCount,
                'passRate' => $passRate,
            ],
        ];
    }
}
