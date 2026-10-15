<?php

namespace App\Filament\Pages;

use App\Models\ExamResult;
use App\Models\Student;
use App\Models\Subject;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Livewire\Attributes\Url;
use UnitEnum;

class SubjectHistory extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Ujian';

    protected static ?string $navigationLabel = 'Histori Pelajaran';

    protected static ?string $title = 'Histori Pelajaran';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.subject-history';

    #[Url]
    public ?int $selectedSubjectId = null;

    #[Url]
    public ?int $selectedStudentId = null;

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

    public function selectStudent(?int $studentId): void
    {
        $this->selectedStudentId = $studentId;
    }

    public function getViewData(): array
    {
        $subjects = Subject::withCount(['questions', 'exams'])->get();

        $selectedSubject = $this->selectedSubjectId 
            ? Subject::with(['questions'])->find($this->selectedSubjectId) 
            : null;

        $students = Student::orderBy('name')->get();

        $query = ExamResult::with(['student', 'exam', 'subject']);

        if ($this->selectedSubjectId) {
            $query->where('subject_id', $this->selectedSubjectId);
        }

        if ($this->selectedStudentId) {
            $query->where('student_id', $this->selectedStudentId);
        }

        $results = $query->orderBy('exam_date', 'desc')->get();

        // Calculate statistics for the selected subject
        $totalExamsTaken = $results->count();
        $averageScore = $totalExamsTaken > 0 ? round($results->avg('score'), 1) : 0;
        $highestScore = $totalExamsTaken > 0 ? $results->max('score') : 0;
        $lowestScore = $totalExamsTaken > 0 ? $results->min('score') : 0;
        $passedCount = $results->where('is_passed', true)->count();
        $passRate = $totalExamsTaken > 0 ? round(($passedCount / $totalExamsTaken) * 100, 1) : 0;

        return [
            'subjects' => $subjects,
            'selectedSubject' => $selectedSubject,
            'students' => $students,
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
