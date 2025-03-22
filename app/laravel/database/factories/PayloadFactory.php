<?php

namespace Database\Factories;

use App\Models\Payload;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayloadFactory extends Factory
{
    protected $model = Payload::class;

    public function definition()
    {
        return [
            'production_line_id' => 1,
            'topic' => $this->faker->word,
            'message' => json_encode(['data' => $this->faker->sentence]),
        ];
    }
}
