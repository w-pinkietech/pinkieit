<?php

namespace Database\Factories;

use App\Models\DefectiveProduction;
use Illuminate\Database\Eloquent\Factories\Factory;

class DefectiveProductionFactory extends Factory
{
    protected $model = DefectiveProduction::class;

    public function definition()
    {
        return [
            'production_line_id' => 1,
            'count' => $this->faker->numberBetween(1, 10),
            'at' => now(),
        ];
    }
}
