@extends('cabecera')

@section('contenido')
    @php
        $moneda = Cookie::get('moneda', 'EUR');
        $simbolo = $moneda === 'USD' ? '$' : ($moneda === 'GBP' ? '£' : '€');
        $simboloDerecha = $moneda === 'EUR';
        $tema = request()->cookie('tema', 'claro');
    @endphp

    <style>
        body { background-color: {{ $tema == 'oscuro' ? '#121212' : '#fff' }}; color: {{ $tema == 'oscuro' ? '#e0e0e0' : '#000' }}; }
        .carrito-container { min-height: 60vh; }
        .carrito-container h2 { font-size: 2rem; font-weight: 700; margin-bottom: 2rem; border-bottom: 2px solid {{ $tema == 'oscuro' ? '#fff' : '#000' }}; padding-bottom: 1rem; }
        .table { @if($tema == 'oscuro') color: #e0e0e0; @endif }
        .table thead th { background: {{ $tema == 'oscuro' ? '#1f1f1f' : '#000' }}; color: #fff; font-weight: 600; border: none; }
        .table tbody tr { border-bottom: 1px solid {{ $tema == 'oscuro' ? '#333' : '#dee2e6' }}; }
        .resumen-total { background: {{ $tema == 'oscuro' ? '#1f1f1f' : '#f8f9fa' }}; padding: 1.5rem; border-radius: 8px; margin: 2rem 0; @if($tema == 'oscuro') border: 1px solid #333; @endif }
        .resumen-total h4 { margin: 0.5rem 0; font-weight: 600; font-size: 1.1rem; }
        .resumen-total h4:last-child { font-size: 1.3rem; color: {{ $tema == 'oscuro' ? '#fff' : '#000' }}; border-top: 2px solid {{ $tema == 'oscuro' ? '#fff' : '#000' }}; padding-top: 1rem; margin-top: 1rem; }
        .btn-action { font-weight: 600; border-radius: 8px; padding: 0.6rem 1.5rem; }
        .btn-comprar { background: {{ $tema == 'oscuro' ? '#1f1f1f' : '#000' }}; color: #fff; border: 2px solid {{ $tema == 'oscuro' ? '#fff' : '#000' }}; }
        .btn-comprar:hover { background: {{ $tema == 'oscuro' ? '#fff' : '#333' }}; color: {{ $tema == 'oscuro' ? '#000' : '#fff' }}; }
        .btn-vaciar { background: {{ $tema == 'oscuro' ? 'transparent' : '#fff' }}; color: {{ $tema == 'oscuro' ? '#fff' : '#000' }}; border: 2px solid {{ $tema == 'oscuro' ? '#fff' : '#000' }}; }
        .btn-vaciar:hover { background: {{ $tema == 'oscuro' ? '#fff' : '#000' }}; color: {{ $tema == 'oscuro' ? '#000' : '#fff' }}; }
        .alert { @if($tema == 'oscuro') background-color: #2a2a2a; color: #e0e0e0; border-color: #555; @endif }
    </style>

    <div class="carrito-container">
        <h2>Tu carrito</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($items->isEmpty())
            <p>No tienes productos en el carrito.</p>
            <a href="{{ route('productos.index') }}" class="btn btn-action btn-comprar">Ver productos</a>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item->producto->nombre }}</td>
                            <td>
                                @if ($simboloDerecha)
                                    {{ number_format($item->precio_unitario, 2) }} {{ $simbolo }}
                                @else
                                    {{ $simbolo }} {{ number_format($item->precio_unitario, 2) }}
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <form method="POST" action="{{ route('carrito.disminuir', $item->id) }}" class="me-1">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-secondary">-</button>
                                    </form>
                                    <span class="px-2">{{ $item->cantidad }}</span>
                                    <form method="POST" action="{{ route('carrito.aumentar', $item->id) }}" class="ms-1">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary"
                                            @if ($item->cantidad >= $item->producto->stock) disabled @endif>+</button>
                                    </form>
                                </div>
                            </td>
                            <td>
                                @if ($simboloDerecha)
                                    {{ number_format($item->cantidad * $item->precio_unitario, 2) }} {{ $simbolo }}
                                @else
                                    {{ $simbolo }} {{ number_format($item->cantidad * $item->precio_unitario, 2) }}
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('carrito.remove', $item->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="resumen-total">
                <h4>Subtotal:
                    @if ($simboloDerecha)
                        {{ number_format($subtotal, 2) }} {{ $simbolo }}
                    @else
                        {{ $simbolo }} {{ number_format($subtotal, 2) }}
                    @endif
                </h4>
                <h4>Impuestos (10%):
                    @if ($simboloDerecha)
                        {{ number_format($impuestos, 2) }} {{ $simbolo }}
                    @else
                        {{ $simbolo }} {{ number_format($impuestos, 2) }}
                    @endif
                </h4>
                <h4>Total:
                    @if ($simboloDerecha)
                        {{ number_format($total, 2) }} {{ $simbolo }}
                    @else
                        {{ $simbolo }} {{ number_format($total, 2) }}
                    @endif
                </h4>
            </div>

            <div class="mt-3 d-flex gap-2">
                <form method="POST" action="{{ route('carrito.comprar') }}">
                    @csrf
                    <button type="submit" class="btn btn-action btn-comprar">Comprar</button>
                </form>

                <form method="POST" action="{{ route('carrito.clear') }}">
                    @csrf
                    <button type="submit" class="btn btn-action btn-vaciar">Vaciar carrito</button>
                </form>

                <a href="{{ route('productos.index') }}" class="btn btn-action btn-vaciar">Seguir comprando</a>
            </div>
        @endif
    </div>
@endsection
