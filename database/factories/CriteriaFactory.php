<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Criteria>
 */
class CriteriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'order' => $this->faker->unique()->numberBetween(1, 1000),
            'type' => $this->faker->randomElement(['percentage', 'number']),

            'evaluation_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}
