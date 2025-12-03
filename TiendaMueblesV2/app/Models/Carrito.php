<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    use HasFactory;

    protected $table = 'carritos';

    protected $fillable = [
        'usuario_id',
        'sesionId',
    ];

    // Relación: un carrito pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    // Relación: un carrito puede contener muchos productos
    public function items()
    {
        return $this->belongsToMany(Producto::class, 'carrito_items')
            ->withPivot('cantidad', 'precio_unitario')
            ->withTimestamps();
    }
}
