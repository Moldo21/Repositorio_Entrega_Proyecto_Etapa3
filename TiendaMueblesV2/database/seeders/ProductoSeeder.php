<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        // Productos hardcodeados
        $productosHardcoded = [
            // Sillas
            [
                'id' => 1,
                'categoria_id' => 1,
                'nombre' => 'Silla de madera clásica',
                'descripcion' => 'Silla robusta de roble.',
                'precio' => 50.50,
                'stock' => 10,
                'materiales' => 'Madera de roble',
                'dimensiones' => '45x45x90 cm',
                'color_principal' => 'Marrón',
                'destacado' => true,
                'imagen_principal' => 'silla1_1.jpg',
            ],
            [
                'id' => 2,
                'categoria_id' => 1,
                'nombre' => 'Silla tapizada moderna',
                'descripcion' => 'Tapizado gris y patas metálicas.',
                'precio' => 60.50,
                'stock' => 8,
                'materiales' => 'Metal y tela',
                'dimensiones' => '48x50x88 cm',
                'color_principal' => 'Gris',
                'destacado' => false,
                'imagen_principal' => 'silla2_1.jpg',
            ],
            [
                'id' => 3,
                'categoria_id' => 1,
                'nombre' => 'Silla plegable',
                'descripcion' => 'Práctica y ligera, ideal para exteriores.',
                'precio' => 39.90,
                'stock' => 15,
                'materiales' => 'Aluminio y plástico',
                'dimensiones' => '45x44x85 cm',
                'color_principal' => 'Blanco',
                'destacado' => false,
                'imagen_principal' => 'silla3_1.jpg',
            ],

            // Mesas
            [
                'id' => 4,
                'categoria_id' => 2,
                'nombre' => 'Mesa comedor extensible',
                'descripcion' => 'Perfecta para cenas en familia.',
                'precio' => 249.99,
                'stock' => 5,
                'materiales' => 'Madera y metal',
                'dimensiones' => '160x90x75 cm',
                'color_principal' => 'Marrón',
                'destacado' => true,
                'imagen_principal' => 'mesa1_1.jpg',
            ],
            [
                'id' => 5,
                'categoria_id' => 2,
                'nombre' => 'Mesa auxiliar redonda',
                'descripcion' => 'Compacta y elegante.',
                'precio' => 59.95,
                'stock' => 12,
                'materiales' => 'Madera MDF',
                'dimensiones' => '50x50x50 cm',
                'color_principal' => 'Blanca',
                'destacado' => false,
                'imagen_principal' => 'mesa2_1.jpg',
            ],
            [
                'id' => 6,
                'categoria_id' => 2,
                'nombre' => 'Mesa de centro industrial',
                'descripcion' => 'Estilo moderno con base metálica.',
                'precio' => 119.90,
                'stock' => 6,
                'materiales' => 'Hierro y cristal',
                'dimensiones' => '100x60x40 cm',
                'color_principal' => 'Negro',
                'destacado' => false,
                'imagen_principal' => 'mesa3_1.jpg',
            ],

            // Camas
            [
                'id' => 7,
                'categoria_id' => 3,
                'nombre' => 'Cama matrimonial de pino',
                'descripcion' => 'Estructura firme y natural.',
                'precio' => 299.90,
                'stock' => 4,
                'materiales' => 'Madera de pino',
                'dimensiones' => '160x200 cm',
                'color_principal' => 'Blanco',
                'destacado' => true,
                'imagen_principal' => 'cama1_1.jpg',
            ],
            [
                'id' => 8,
                'categoria_id' => 3,
                'nombre' => 'Cama individual moderna',
                'descripcion' => 'Diseño minimalista con cabecero tapizado.',
                'precio' => 199.99,
                'stock' => 9,
                'materiales' => 'Madera MDF',
                'dimensiones' => '90x190 cm',
                'color_principal' => 'Gris',
                'destacado' => false,
                'imagen_principal' => 'cama2_1.jpg',
            ],
            [
                'id' => 9,
                'categoria_id' => 3,
                'nombre' => 'Cama con cajones',
                'descripcion' => 'Incluye almacenamiento inferior.',
                'precio' => 349.99,
                'stock' => 3,
                'materiales' => 'Madera laminada',
                'dimensiones' => '150x200 cm',
                'color_principal' => 'Blanco',
                'destacado' => true,
                'imagen_principal' => 'cama3_1.jpg',
            ],

            // Estanterías
            [
                'id' => 10,
                'categoria_id' => 4,
                'nombre' => 'Estantería modular moderna',
                'descripcion' => 'Compartimentos ajustables.',
                'precio' => 129.99,
                'stock' => 7,
                'materiales' => 'Metal y melamina',
                'dimensiones' => '120x30x180 cm',
                'color_principal' => 'Negro',
                'destacado' => false,
                'imagen_principal' => 'estanteria1_1.jpg',
            ],
            [
                'id' => 11,
                'categoria_id' => 4,
                'nombre' => 'Estantería de roble',
                'descripcion' => 'Ideal para libros o decoración.',
                'precio' => 179.95,
                'stock' => 5,
                'materiales' => 'Madera de roble',
                'dimensiones' => '100x35x160 cm',
                'color_principal' => 'Marrón',
                'destacado' => true,
                'imagen_principal' => 'estanteria2_1.jpg',
            ],
            [
                'id' => 12,
                'categoria_id' => 4,
                'nombre' => 'Estantería flotante',
                'descripcion' => 'Minimalista y resistente.',
                'precio' => 69.90,
                'stock' => 20,
                'materiales' => 'MDF',
                'dimensiones' => '80x25x20 cm',
                'color_principal' => 'Blanco',
                'destacado' => false,
                'imagen_principal' => 'estanteria3_1.jpg',
            ],
        ];

        foreach ($productosHardcoded as $prod) {
            Producto::create($prod);
        }

        // Productos generados mediante la factoría
        Producto::factory(15)->create();
    }
}
