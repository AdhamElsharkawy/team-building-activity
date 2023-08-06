<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeamLevel>
 */
class TeamLevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => $this->faker->numberBetween(1, 10),
            'level_id' => $this->faker->numberBetween(1, 10),
            // 'score' => $this->faker->numberBetween(0, 100),
        ];
    }
}
