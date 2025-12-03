<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Galeria;
use Illuminate\Http\Request;

class GaleriaController extends Controller
{

    public function store(Request $request, $productoId)
    {
        if (!auth()->check() || auth()->user()->rol_id !== 1) {
            return back()->with('error', 'No tienes permiso.');
        }

        $request->validate([
            'imagenes.*' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $producto = Producto::findOrFail($productoId);
        $ultimoOrden = $producto->galerias()->max('orden') ?? 0;

        foreach ($request->file('imagenes') as $img) {
            $nombre = 'galeria_' . time() . '_' . rand(1000, 9999) . '.' . $img->getClientOriginalExtension();
            $img->move(public_path('imagenes'), $nombre);

            $ultimoOrden++;
            Galeria::create([
                'producto_id' => $producto->id,
                'ruta' => $nombre,
                'es_principal' => false,
                'orden' => $ultimoOrden
            ]);
        }

        return back()->with('success', 'Imágenes añadidas a la galería.');
    }

    public function destroy($productoId, $galeriaId)
    {
        if (!auth()->check() || auth()->user()->rol_id !== 1) {
            return back()->with('error', 'No tienes permiso.');
        }

        $producto = Producto::findOrFail($productoId);

        if ($producto->galerias()->count() <= 1) {
            return back()->with('error', 'El producto debe tener al menos una imagen.');
        }

        $galeria = Galeria::where('producto_id', $productoId)
            ->where('id', $galeriaId)
            ->firstOrFail();

        if (file_exists(public_path('imagenes/' . $galeria->ruta))) {
            unlink(public_path('imagenes/' . $galeria->ruta));
        }

        $eraPrincipal = $galeria->es_principal;
        $galeria->delete();

        if ($eraPrincipal) {
            $nuevaPrincipal = $producto->galerias()->orderBy('orden')->first();
            if ($nuevaPrincipal) {
                $nuevaPrincipal->update(['es_principal' => true]);
                $producto->update(['imagen_principal' => $nuevaPrincipal->ruta]);
            }
        }

        return back()->with('success', 'Imagen eliminada correctamente.');
    }

    public function setPrincipal($productoId, $galeriaId)
    {
        if (!auth()->check() || auth()->user()->rol_id !== 1) {
            return back()->with('error', 'No tienes permiso.');
        }

        $producto = Producto::findOrFail($productoId);
        $galeria = Galeria::where('producto_id', $productoId)
            ->where('id', $galeriaId)
            ->firstOrFail();

        Galeria::where('producto_id', $productoId)->update(['es_principal' => false]);

        $galeria->update(['es_principal' => true]);

        $producto->update(['imagen_principal' => $galeria->ruta]);

        return back()->with('success', 'Imagen principal actualizada.');
    }

    public function reordenar(Request $request, $productoId)
    {
        if (!auth()->check() || auth()->user()->rol_id !== 1) {
            return response()->json(['error' => 'No tienes permiso'], 403);
        }

        $request->validate([
            'orden' => 'required|array',
            'orden.*' => 'integer|exists:galerias,id'
        ]);

        foreach ($request->orden as $indice => $galeriaId) {
            Galeria::where('id', $galeriaId)
                ->where('producto_id', $productoId)
                ->update(['orden' => $indice + 1]);
        }

        return response()->json(['success' => true]);
    }
}
