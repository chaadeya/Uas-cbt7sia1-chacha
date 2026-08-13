<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Question>
 */
class QuestionFactory extends Factory
{
    protected $model = Question::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subject_id'  => Subject::factory(),
            'payload'     => fake()->sentence(rand(8, 15)) . '?',
            'score'       => fake()->numberBetween(1, 5),
            'description' => null,
            'is_active'   => true,
        ];
    }

    /**
     * Setelah soal dibuat, otomatis buatkan 4 pilihan jawaban
     * (A, B, C, D) dengan salah satu ditandai benar secara acak.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Question $question) {
            $options = ['A', 'B', 'C', 'D'];
            $correctIndex = array_rand($options);

            foreach ($options as $index => $option) {
                Answer::factory()->create([
                    'question_id' => $question->id,
                    'option'      => $option,
                    'is_correct'  => $index === $correctIndex,
                ]);
            }
        });
    }
}