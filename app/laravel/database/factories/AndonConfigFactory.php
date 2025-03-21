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
            'process_id' => 1,
            'column_size' => AndonColumnSize::ONE,
            'indicator_id' => $this->faker->uuid,
            'active' => true,
        ];
    }
}
