<?php

namespace Database\Factories;

use App\Models\SensorEvent;
use App\Enums\SensorType;
use Illuminate\Database\Eloquent\Factories\Factory;

class SensorEventFactory extends Factory
{
    protected $model = SensorEvent::class;

    public function definition()
    {
        return [
            'sensor_id' => 1,
            'type' => SensorType::GPIO_INPUT,
            'value' => $this->faker->numberBetween(1, 100),
            'at' => now(),
        ];
    }
}
