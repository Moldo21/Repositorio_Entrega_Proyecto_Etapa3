<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarritoItem extends Model
{
    use HasFactory;

    protected $table = 'carrito_items';

    protected $fillable = [
        'carrito_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
    ];

    // Relación: un item pertenece a un carrito
    public function carrito()
    {
        return $this->belongsTo(Carrito::class);
    }

    // Relación: un item tiene un producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
