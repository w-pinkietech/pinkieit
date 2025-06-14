<?php

namespace Database\Factories;

use App\Models\AndonConfig;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AndonConfig>
 */
class AndonConfigFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AndonConfig::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'font_size_percentage' => $this->faker->numberBetween(80, 120),
            'refresh_sec' => $this->faker->numberBetween(5, 60),
            'clock' => $this->faker->randomElement(['hidden', 'show']),
            'page' => $this->faker->randomElement(['hidden', 'show']),
            'goal' => $this->faker->randomElement(['hidden', 'show']),
            'pace' => $this->faker->randomElement(['hidden', 'show']),
            'changeover_time' => $this->faker->randomElement(['hidden', 'show']),
            'downtime' => $this->faker->randomElement(['hidden', 'show']),
            'sensor_display' => $this->faker->randomElement(['hidden', 'show']),
            'all_production_view' => $this->faker->boolean(),
            'auto_next_page_enable' => $this->faker->boolean(),
            'auto_next_page_duration' => $this->faker->numberBetween(10, 300),
            'updated_by' => null,
        ];
    }
}