<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolSeeder::class);

        $this->call(UsuarioSeeder::class);

        $this->call(CategoriaSeeder::class);

        $this->call(ProductoSeeder::class);

        $this->call(GaleriaSeeder::class);

        $this->call(CarritoSeeder::class);
    }
}
