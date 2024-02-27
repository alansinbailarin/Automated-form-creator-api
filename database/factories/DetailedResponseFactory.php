<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetailedResponse>
 */
class DetailedResponseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'answer' => fake()->sentence(6),
            'survey_id' => fake()->numberBetween(1, 200),
            'page_id' => fake()->numberBetween(1, 500),
            'element_id' => fake()->numberBetween(1, 800),
            'choice_id' => fake()->numberBetween(1, 1000),
            'response_id' => fake()->numberBetween(1, 1000),
        ];
    }
}
