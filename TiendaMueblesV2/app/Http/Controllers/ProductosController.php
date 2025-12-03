<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Galeria;
use Illuminate\Http\Request;

class ProductosController extends Controller
{

    public function index(Request $request)
    {

        $query = Producto::with(['categoria', 'galerias']);

        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }

        if ($request->filled('min') && $request->filled('max')) {
            $min = (float) $request->min;
            $max = (float) $request->max;

            if ($min > $max) {
                return redirect()->route('productos.index', $request->except(['min', 'max']))
                    ->withErrors(['rango_precio' => 'El precio mínimo no puede ser mayor que el precio máximo.']);
            }

            $query->whereBetween('precio', [$min, $max]);
        }

        if ($request->filled('color')) {
            $query->where('color_principal', 'LIKE', '%' . $request->color . '%');
        }

        if ($request->filled('busqueda')) {
            $busqueda = $request->busqueda;
            $query->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'LIKE', '%' . $busqueda . '%')
                    ->orWhere('descripcion', 'LIKE', '%' . $busqueda . '%');
            });
        }

        $orden = $request->query('orden', 'created_at');
        $direccion = $request->query('dir', 'desc');

        switch ($orden) {
            case 'precio':
                $query->orderBy('precio', $direccion);
                break;
            case 'nombre':
                $query->orderBy('nombre', $direccion);
                break;
            case 'novedad':
                $query->orderBy('destacado', $direccion)->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', $direccion);
                break;
        }

        $porPagina = (int) $request->cookie('paginacion', 6);
        if ($request->has('paginacion')) {
            $porPagina = (int) $request->paginacion;
            cookie()->queue('paginacion', $porPagina, 60 * 24 * 30);
        }

        $productos = $query->paginate($porPagina);
        $categorias = Categoria::all();

        return view('productos.index', compact('productos', 'categorias'));
    }

    public function show($id)
    {
        $producto = Producto::with(['categoria', 'galerias'])
            ->findOrFail($id);

        return view('productos.show', compact('producto'));
    }

    public function create(Request $request)
    {
        if (!auth()->check() || auth()->user()->rol_id !== 1) {
            return redirect()->route('productos.index')->with('error', 'No tienes permiso.');
        }

        $categorias = Categoria::all();
        return view('productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        if (!auth()->check() || auth()->user()->rol_id !== 1) {
            return redirect()->route('productos.index')->with('error', 'No tienes permiso.');
        }

        $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'nombre' => 'required|string|max:80',
            'descripcion' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'materiales' => 'required|string|max:255',
            'dimensiones' => 'required|string|max:255',
            'color_principal' => 'required|string|max:50',
            'destacado' => 'nullable|boolean',
            'imagen' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $nombreImagen = null;
        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $nombreImagen = 'producto_' . time() . '.' . $archivo->getClientOriginalExtension();
            $archivo->move(public_path('imagenes'), $nombreImagen);
        }

        $producto = Producto::create([
            'categoria_id' => $request->categoria_id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'materiales' => $request->materiales,
            'dimensiones' => $request->dimensiones,
            'color_principal' => $request->color_principal,
            'destacado' => $request->has('destacado'),
            'imagen_principal' => $nombreImagen
        ]);

        if ($nombreImagen) {
            Galeria::create([
                'producto_id' => $producto->id,
                'ruta' => $nombreImagen,
                'es_principal' => true,
                'orden' => 1
            ]);
        }

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function edit($id)
    {
        if (!auth()->check() || auth()->user()->rol_id !== 1) {
            return redirect()->route('productos.index')->with('error', 'No tienes permiso.');
        }

        $producto = Producto::findOrFail($id);
        $categorias = Categoria::all();

        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->rol_id !== 1) {
            return redirect()->route('productos.index')->with('error', 'No tienes permiso.');
        }

        $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'nombre' => 'required|string|max:80',
            'descripcion' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'materiales' => 'required|string|max:255',
            'dimensiones' => 'required|string|max:255',
            'color_principal' => 'required|string|max:50',
            'destacado' => 'nullable|boolean'
        ]);

        $producto = Producto::findOrFail($id);

        $producto->update([
            'categoria_id' => $request->categoria_id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'materiales' => $request->materiales,
            'dimensiones' => $request->dimensiones,
            'color_principal' => $request->color_principal,
            'destacado' => $request->has('destacado')
        ]);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy($id)
    {
        if (!auth()->check() || auth()->user()->rol_id !== 1) {
            return redirect()->route('productos.index')->with('error', 'No tienes permiso.');
        }

        $producto = Producto::findOrFail($id);

        if ($producto->imagen_principal && file_exists(public_path('imagenes/' . $producto->imagen_principal))) {
            unlink(public_path('imagenes/' . $producto->imagen_principal));
        }

        foreach ($producto->galerias as $galeria) {
            if (file_exists(public_path('imagenes/' . $galeria->ruta))) {
                unlink(public_path('imagenes/' . $galeria->ruta));
            }
        }

        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}
