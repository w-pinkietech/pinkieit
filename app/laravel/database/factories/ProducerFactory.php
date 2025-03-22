<?php

namespace Database\Factories;

use App\Models\Producer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProducerFactory extends Factory
{
    protected $model = Producer::class;

    public function definition()
    {
        return [
            'worker_id' => 1,
            'production_line_id' => 1,
            'identification_number' => $this->faker->unique()->numerify('ID-####'),
            'worker_name' => $this->faker->name,
            'start' => now(),
            'stop' => null,
        ];
    }
}
