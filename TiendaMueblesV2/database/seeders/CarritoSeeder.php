<?php

namespace Database\Seeders;

use App\Models\Carrito;
use App\Models\CarritoItem;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class CarritoSeeder extends Seeder
{

    public function run(): void
    {
        $carrito1 = $this->crearCarrito(1, 'sesion_1');
        $carrito2 = $this->crearCarrito(2, 'sesion_2');
    }


    private function crearCarrito(int $usuarioId, string $sesionId): Carrito
    {
        $carrito = Carrito::create([
            'usuario_id' => $usuarioId,
            'sesionId' => $sesionId,
        ]);

        $productos = Producto::inRandomOrder()->take(3)->get();

        foreach ($productos as $producto) {
            $maxCantidad = max(1, $producto->stock);
            $cantidad = rand(1, $maxCantidad);

            CarritoItem::create([
                'carrito_id' => $carrito->id,
                'producto_id' => $producto->id,
                'cantidad' => $cantidad,
                'precio_unitario' => $producto->precio,
            ]);
        }

        return $carrito;
    }
}
