<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\CarritoItem;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CarritoController extends Controller
{

    private function obtenerCarrito()
    {
        if (!auth()->check()) {
            return null;
        }

        $sesionId = Session::get('sesionId');

        $carrito = Carrito::where('usuario_id', auth()->id())
            ->where('sesionId', $sesionId)
            ->first();

        if (!$carrito) {
            $carrito = Carrito::where('usuario_id', auth()->id())
                ->latest()
                ->first();

            if ($carrito) {
                $carrito->sesionId = $sesionId;
                $carrito->save();
            } else {

                $carrito = Carrito::create([
                    'usuario_id' => auth()->id(),
                    'sesionId' => $sesionId
                ]);
            }
        }

        return $carrito;
    }

    public function show(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver el carrito');
        }

        $carrito = $this->obtenerCarrito();

        $items = CarritoItem::where('carrito_id', $carrito->id)
            ->with('producto')
            ->get();

        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item->cantidad * $item->precio_unitario;
        }

        $impuestos = $subtotal * 0.10;
        $total = $subtotal + $impuestos;

        return view('carrito.index', compact('carrito', 'items', 'subtotal', 'impuestos', 'total'));
    }

    public function add(Request $request, $productoId)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión');
        }

        $producto = Producto::findOrFail($productoId);
        $carrito = $this->obtenerCarrito();

        $item = CarritoItem::where('carrito_id', $carrito->id)
            ->where('producto_id', $productoId)
            ->first();

        if ($item) {

            if ($item->cantidad + 1 > $producto->stock) {
                return redirect()->back()
                    ->with('error', "No hay suficiente stock de '{$producto->nombre}'. Disponible: {$producto->stock}");
            }

            $item->cantidad += 1;
            $item->save();
        } else {

            if ($producto->stock < 1) {
                return redirect()->back()
                    ->with('error', "No hay stock disponible de '{$producto->nombre}'");
            }

            CarritoItem::create([
                'carrito_id' => $carrito->id,
                'producto_id' => $productoId,
                'cantidad' => 1,
                'precio_unitario' => $producto->precio
            ]);
        }

        return redirect()->back()
            ->with('success', "'{$producto->nombre}' añadido al carrito");
    }

    public function aumentar($itemId)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión');
        }

        $item = CarritoItem::findOrFail($itemId);

        if ($item->carrito->usuario_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Acción no permitida');
        }

        if ($item->cantidad + 1 > $item->producto->stock) {
            return redirect()->back()
                ->with('error', "No hay suficiente stock de '{$item->producto->nombre}'");
        }

        $item->cantidad += 1;
        $item->save();

        return redirect()->back()->with('success', 'Cantidad actualizada');
    }

    public function disminuir($itemId)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión');
        }

        $item = CarritoItem::findOrFail($itemId);

        if ($item->carrito->usuario_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Acción no permitida');
        }

        if ($item->cantidad > 1) {
            $item->cantidad -= 1;
            $item->save();
        } else {

            $item->delete();
        }

        return redirect()->back()->with('success', 'Cantidad actualizada');
    }


    public function remove($itemId)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión');
        }

        $item = CarritoItem::findOrFail($itemId);

        if ($item->carrito->usuario_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Acción no permitida');
        }

        $nombreProducto = $item->producto->nombre;
        $item->delete();

        return redirect()->route('carrito.show')
            ->with('success', "'{$nombreProducto}' eliminado del carrito");
    }

    public function clear()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión');
        }

        $carrito = $this->obtenerCarrito();

        CarritoItem::where('carrito_id', $carrito->id)->delete();

        return redirect()->route('carrito.show')
            ->with('success', 'Carrito vaciado correctamente');
    }


    public function comprar()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión');
        }

        $carrito = $this->obtenerCarrito();
        $items = CarritoItem::where('carrito_id', $carrito->id)->get();

        if ($items->isEmpty()) {
            return redirect()->back()->with('error', 'El carrito está vacío');
        }

        foreach ($items as $item) {
            if ($item->cantidad > $item->producto->stock) {
                return redirect()->back()
                    ->with('error', "Stock insuficiente para '{$item->producto->nombre}'");
            }
        }

        // NO eliminamos el carrito, solo vaciamos los items
        CarritoItem::where('carrito_id', $carrito->id)->delete();

        return redirect()->route('principal')
            ->with('success', 'Compra realizada con éxito!');
    }
}
