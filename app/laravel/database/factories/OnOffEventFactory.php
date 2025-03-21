<?php

namespace Database\Factories;

use App\Models\OnOffEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

class OnOffEventFactory extends Factory
{
    protected $model = OnOffEvent::class;

    public function definition()
    {
        return [
            'process_id' => 1,
            'is_on' => $this->faker->boolean,
            'at' => now(),
        ];
    }
}
