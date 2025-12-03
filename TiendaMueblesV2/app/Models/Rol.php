<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = ['nombre'];

    // Relación: un rol tiene muchos usuarios
    public function usuarios()
    {
        return $this->hasMany(Usuario::class);
    }
}
