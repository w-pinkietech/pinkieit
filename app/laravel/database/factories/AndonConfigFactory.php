<?php

namespace Database\Factories;

use App\Models\AndonConfig;
use App\Enums\AndonColumnSize;
use Illuminate\Database\Eloquent\Factories\Factory;

class AndonConfigFactory extends Factory
{
    protected $model = AndonConfig::class;

    public function definition()
    {
        return [
            'user_id' => 1,
            'row_count' => $this->faker->numberBetween(1, 5),
            'column_count' => $this->faker->numberBetween(1, 12),
            'auto_play' => $this->faker->boolean,
            'auto_play_speed' => $this->faker->numberBetween(1000, 5000),
            'slide_speed' => $this->faker->numberBetween(100, 1000),
            'easing' => 'linear',
            'fade' => $this->faker->boolean,
        ];
    }
}
