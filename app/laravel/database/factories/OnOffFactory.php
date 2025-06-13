<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OnOff>
 */
class OnOffFactory extends Factory
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
            'raspberry_pi_id' => 1,
            'event_name' => $this->faker->words(2, true),
            'on_message' => $this->faker->sentence(3),
            'off_message' => $this->faker->optional()->sentence(3),
            'pin_number' => $this->faker->numberBetween(2, 27),
        ];
    }
}