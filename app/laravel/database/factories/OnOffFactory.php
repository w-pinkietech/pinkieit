<?php

namespace Database\Factories;

use App\Models\OnOff;
use Illuminate\Database\Eloquent\Factories\Factory;

class OnOffFactory extends Factory
{
    protected $model = OnOff::class;

    public function definition()
    {
        return [
            'process_id' => 1,
            'is_on' => $this->faker->boolean,
            'started_at' => now(),
        ];
    }
}
