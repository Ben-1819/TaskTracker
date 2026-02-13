<?php

namespace Database\Factories;

use App\Models\User;
use Date;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->name(),
            'description' => $this->faker->words(10, true),
            'category' => $this->faker->words(1, true),
            'date_set' => Date::today(),
            'date_due' => Date::tomorrow(),
            'complete' => false,
        ];
    }
}
