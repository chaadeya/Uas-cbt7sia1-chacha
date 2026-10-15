<?php

use App\Models\Exam;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Student::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Exam::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Subject::class)->nullable()->constrained()->nullOnDelete();
            $table->dateTime('exam_date');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->unsignedInteger('duration_spent_minutes')->default(0);
            $table->unsignedInteger('total_questions')->default(0);
            $table->unsignedInteger('correct_answers')->default(0);
            $table->unsignedInteger('wrong_answers')->default(0);
            $table->unsignedInteger('unanswered')->default(0);
            $table->decimal('score', 5, 2)->default(0);
            $table->boolean('is_passed')->default(false);
            $table->string('status', 30)->default('completed');
            $table->timestamps();

            $table->index(['student_id', 'exam_id']);
            $table->index(['student_id', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};
