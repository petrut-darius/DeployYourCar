<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "user_id" => User::factory(),
            "manufacture" => fake()->title(),
            "model" => fake()->title(),
            "displacement" => fake()->randomFloat(3, 1.0, 9.9),
            "engine_code" => strtoupper(fake()->bothify("??##")),
            "whp" => fake()->numberBetween(60, 1000),
            "color" => fake()->safeColorName(), 
        ];
    }
}
