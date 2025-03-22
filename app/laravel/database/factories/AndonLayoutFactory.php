<?php

namespace Database\Factories;

use App\Models\AndonLayout;
use Illuminate\Database\Eloquent\Factories\Factory;

class AndonLayoutFactory extends Factory
{
    protected $model = AndonLayout::class;

    public function definition()
    {
        return [
            'user_id' => 1,
            'process_id' => 1,
            'layout' => json_encode([
                'position' => ['x' => 0, 'y' => 0],
                'size' => ['width' => 100, 'height' => 100]
            ]),
            'active' => true,
        ];
    }
}
