<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Rol;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // Usuarios hardcodeados
        $usuariosHardcoded = [
            [
                'id' => 1,
                'email' => 'admin@correo.com',
                'nombre' => 'juan',
                'apellidos' => 'Perez',
                'rol_id' => Rol::where('nombre', 'Administrador')->first()->id,
                'password' => bcrypt('1234')
            ],
            [
                'id' => 2,
                'email' => 'paconi@correo.com',
                'nombre' => 'paconi',
                'apellidos' => 'Gomez',
                'rol_id' => Rol::where('nombre', 'Cliente')->first()->id,
                'password' => bcrypt('1234')
            ],
            [
                'id' => 3,
                'email' => 'gestor@correo.com',
                'nombre' => 'Marlon',
                'apellidos' => 'Smith',
                'rol_id' => Rol::where('nombre', 'Gestor')->first()->id,
                'password' => bcrypt('1234')
            ],
        ];

        foreach ($usuariosHardcoded as $user) {
            Usuario::create($user);
        }

        // Usuarios generados mediante la factoría
        Usuario::factory(5)->create();
    }
}
