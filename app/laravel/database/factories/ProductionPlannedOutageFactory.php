<?php

namespace Database\Factories;

use App\Models\ProductionPlannedOutage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductionPlannedOutageFactory extends Factory
{
    protected $model = ProductionPlannedOutage::class;

    public function definition()
    {
        return [
            'production_history_id' => 1,
            'planned_outage_name' => $this->faker->word,
            'start_time' => now(),
            'end_time' => now()->addHour(),
        ];
    }
}
