<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'materiales',
        'dimensiones',
        'color_principal',
        'destacado',
        'imagen_principal',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'destacado' => 'boolean',
    ];

    // Relación: un producto pertenece a una categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // Relación: un producto puede estar en muchos carritos
    public function carritos()
    {
        return $this->belongsToMany(Carrito::class, 'carrito_items')
            ->withPivot('cantidad', 'precio_unitario')
            ->withTimestamps();
    }

    // Relación: un producto tiene varias imágenes
    public function galerias()
    {
        return $this->hasMany(Galeria::class);
    }
}
