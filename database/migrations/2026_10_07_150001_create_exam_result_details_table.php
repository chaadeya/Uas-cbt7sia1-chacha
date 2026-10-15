<?php

use App\Models\Answer;
use App\Models\Question;
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
        Schema::create('exam_result_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_result_id')->constrained('exam_results')->cascadeOnDelete();
            $table->foreignIdFor(Question::class)->constrained()->cascadeOnDelete();
            $table->foreignId('selected_answer_id')->nullable()->constrained('answers')->nullOnDelete();
            $table->foreignId('correct_answer_id')->nullable()->constrained('answers')->nullOnDelete();
            $table->text('student_answer_text')->nullable();
            $table->text('correct_answer_text')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->decimal('score', 5, 2)->default(0);
            $table->timestamps();

            $table->index(['exam_result_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_result_details');
    }
};
