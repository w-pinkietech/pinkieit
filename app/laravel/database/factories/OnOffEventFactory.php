<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OnOffEvent>
 */
class OnOffEventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'process_id' => 1,
            'on_off_id' => 1,
            'event_name' => $this->faker->words(2, true),
            'message' => $this->faker->optional()->sentence(3),
            'on_off' => $this->faker->boolean(),
            'pin_number' => $this->faker->numberBetween(2, 27),
            'at' => $this->faker->dateTimeThisYear(),
        ];
    }
}