<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Choice>
 */
class ChoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order' => fake()->numberBetween(1, 10),
            'text' => fake()->sentence(6),
            'value' => fake()->word(),
            'element_id' => fake()->numberBetween(1, 800),
        ];
    }
}
