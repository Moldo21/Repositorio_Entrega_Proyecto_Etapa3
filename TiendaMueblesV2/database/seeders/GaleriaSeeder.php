<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Galeria;
use App\Models\Producto;

class GaleriaSeeder extends Seeder
{
    public function run(): void
    {
        // Imágenes hardcodeadas de los productos harcodeados
        $imagenesHardcoded = [
            1 => ['silla1_1.jpg', 'silla1_2.jpg', 'silla1_3.jpg'],
            2 => ['silla2_1.jpg', 'silla2_2.jpg', 'silla2_3.jpg'],
            3 => ['silla3_1.jpg', 'silla3_2.jpg', 'silla3_3.jpg'],
            4 => ['mesa1_1.jpg', 'mesa1_2.jpg', 'mesa1_3.jpg'],
            5 => ['mesa2_1.jpg', 'mesa2_2.jpg', 'mesa2_3.jpg'],
            6 => ['mesa3_1.jpg', 'mesa3_2.jpg', 'mesa3_3.jpg'],
            7 => ['cama1_1.jpg', 'cama1_2.jpg', 'cama1_3.jpg'],
            8 => ['cama2_1.jpg', 'cama2_2.jpg', 'cama2_3.jpg'],
            9 => ['cama3_1.jpg', 'cama3_2.jpg', 'cama3_3.jpg'],
            10 => ['estanteria1_1.jpg', 'estanteria1_2.jpg', 'estanteria1_3.jpg'],
            11 => ['estanteria2_1.jpg', 'estanteria2_2.jpg', 'estanteria2_3.jpg'],
            12 => ['estanteria3_1.jpg', 'estanteria3_2.jpg', 'estanteria3_3.jpg'],
        ];

        foreach ($imagenesHardcoded as $productoId => $imagenes) {
            foreach ($imagenes as $index => $ruta) {
                Galeria::create([
                    'producto_id' => $productoId,
                    'ruta' => $ruta,
                    'es_principal' => $index === 0,
                    'orden' => $index + 1,
                ]);
            }
        }

        // Los productos generados mediante factorías usarán la imagen default
        $productosAleatorios = Producto::where('id', '>', 12)->get();

        foreach ($productosAleatorios as $producto) {

            for ($i = 1; $i <= 3; $i++) {
                Galeria::create([
                    'producto_id' => $producto->id,
                    'ruta' => 'imagen_default.jpg',
                    'es_principal' => $i === 1,
                    'orden' => $i,
                ]);
            }
        }
    }
}
