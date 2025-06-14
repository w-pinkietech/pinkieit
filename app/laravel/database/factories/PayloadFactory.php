<?php

namespace Database\Factories;

use App\Models\Payload;
use App\Models\ProductionLine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payload>
 */
class PayloadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payload::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'production_line_id' => ProductionLine::factory(),
            'payload' => [
                'production_count' => $this->faker->numberBetween(0, 1000),
                'defective_count' => $this->faker->numberBetween(0, 50),
                'cycle_time' => $this->faker->numberBetween(10, 60),
                'efficiency' => $this->faker->randomFloat(2, 0, 100),
                'downtime' => $this->faker->numberBetween(0, 3600),
                'changeover_time' => $this->faker->numberBetween(0, 1800),
            ],
        ];
    }
}