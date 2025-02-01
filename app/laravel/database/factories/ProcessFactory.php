<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Process>
 */
class ProcessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'process_name' => __('pinkieit.process') . "：{$this->faker->unique()->realText(10)}",
            'plan_color' => '#FFFFFF',
            'remark' => $this->faker->realText(256),
        ];
    }
}
