<?php

namespace Database\Factories;

use App\Models\BarcodeHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarcodeHistoryFactory extends Factory
{
    protected $model = BarcodeHistory::class;

    public function definition()
    {
        return [

            'barcode' => $this->faker->ean13,
            'ip_address' => $this->faker->ipv4,
            'mac_address' => $this->faker->macAddress,

        ];
    }
}
