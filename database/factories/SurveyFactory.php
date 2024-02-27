<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Survey>
 */
class SurveyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'start_date' => fake()->dateTime(),
            'end_date' => fake()->dateTime(),
            'slug' => fake()->unique()->slug(),
            'survey_status_id' => fake()->numberBetween(1, 3),
            'owner_id' => fake()->numberBetween(1, 10),
        ];
    }
}
