<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['id' => 1, 'nombre' => 'Sillas', 'descripcion' => 'Comodidad y estilo para tu hogar'],
            ['id' => 2, 'nombre' => 'Mesas', 'descripcion' => 'Funcionales y elegantes para cualquier espacio'],
            ['id' => 3, 'nombre' => 'Camas', 'descripcion' => 'Descanso y diseño en un solo lugar'],
            ['id' => 4, 'nombre' => 'Estanterías', 'descripcion' => 'Organiza con estilo y practicidad'],
            ['id' => 5, 'nombre' => 'Sofás', 'descripcion' => 'Confort y diseño para tu sala de estar'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
