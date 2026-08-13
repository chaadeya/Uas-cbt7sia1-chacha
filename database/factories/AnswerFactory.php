<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Answer>
 */
class AnswerFactory extends Factory
{
    protected $model = Answer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question_id' => Question::factory(),
            'option'      => fake()->randomElement(['A', 'B', 'C', 'D']),
            'text'        => fake()->sentence(rand(3, 8)),
            'is_correct'  => false,
            'is_active'   => true,
        ];
    }
}