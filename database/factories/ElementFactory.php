<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Element>
 */
class ElementFactory extends Factory
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
            'order' => fake()->numberBetween(0, 10),
            'value' => fake()->word(),
            'visible' => fake()->boolean(),
            'required' => fake()->boolean(),
            'multiple_choice' => fake()->boolean(),
            'page_id' => fake()->numberBetween(1, 500),
            'element_type_id' => fake()->numberBetween(1, 10),
        ];
    }
}
