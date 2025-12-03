<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Carrito;
use App\Models\Usuario;

class CarritoFactory extends Factory
{
    protected $model = Carrito::class;

    public function definition()
    {
        return [
            'usuario_id' => Usuario::factory(),
            'sesionId' => $this->faker->uuid,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
