<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Producto;

class PrincipalController extends Controller
{
    public function index()
    {
        $categorias = Categoria::take(2)->get();
        $productos = Producto::where('destacado', true)->take(6)->get();
        return view('principal', compact('categorias', 'productos'));
    }
}
