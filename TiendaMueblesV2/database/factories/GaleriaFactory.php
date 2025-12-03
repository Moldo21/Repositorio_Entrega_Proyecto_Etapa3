<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Galeria;
use App\Models\Producto;

class GaleriaFactory extends Factory
{
    protected $model = Galeria::class;

    public function definition()
    {
        return [
            'ruta' => $this->faker->imageUrl(640, 480, 'furniture'),
            'es_principal' => false,
            'orden' => 1,
        ];
    }

    public function principal()
    {
        return $this->state(function () {
            return [
                'es_principal' => true,
                'orden' => 0,
            ];
        });
    }
}
