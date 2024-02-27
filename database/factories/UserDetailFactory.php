<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserDetail>
 */
class UserDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_number' => fake()->unique()->randomNumber(8),
            'phone_number' => fake()->phoneNumber(),
            'company' => fake()->company(),
            'position' => fake()->jobTitle(),
            'date_of_birth' => fake()->date(),
            'user_id' => fake()->numberBetween(1, 10),
        ];
    }
}
