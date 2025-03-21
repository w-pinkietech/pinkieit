<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductionLine>
 */
class ProductionLineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'line_id' => 1,
            'production_id' => 1,
            'order' => $this->faker->numberBetween(1, 10),
            'status' => 'active',
        ];
    }
}
