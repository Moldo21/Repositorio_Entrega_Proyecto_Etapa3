@extends('cabecera')

@section('contenido')
@php
    $tema = request()->cookie('tema', 'claro');
    $moneda = request()->cookie('moneda', 'EUR');

    $simbolos = [
        'EUR' => '€',
        'USD' => '$',
        'GBP' => '£'
    ];
    $simboloMoneda = $simbolos[$moneda] ?? '€';
    $simboloDerecha = in_array($moneda, ['EUR']);
@endphp

<style>
    body { background: {{ $tema == 'oscuro' ? '#121212' : '#fff' }}; color: {{ $tema == 'oscuro' ? '#e0e0e0' : '#000' }}; }
    .hero { background: {{ $tema == 'oscuro' ? '#1a1a1a' : '#000' }}; color: #fff; padding: 5rem 0; text-align: center; margin: 0 0 3rem 0; }
    .hero h1 { font-size: 3rem; font-weight: 700; margin-bottom: 1rem; }
    .hero p { font-size: 1.2rem; margin-bottom: 2rem; opacity: 0.95; }
    .btn-light-outline { background: transparent; color: #fff; border: 2px solid #fff; }
    .btn-light-outline:hover { background: #fff; color: #000; }
    .section-title { font-size: 2rem; font-weight: 700; margin: 3rem 0 2rem; text-align: center; border-bottom: 2px solid {{ $tema == 'oscuro' ? '#fff' : '#000' }}; padding-bottom: 1rem; color: {{ $tema == 'oscuro' ? '#e0e0e0' : '#000' }}; }
    .btn-action { font-weight: 600; border-radius: 8px; padding: 0.6rem 1rem; font-size: 0.9rem; }
    .btn-dark-fill { background: #000; color: #fff; }
    .btn-dark-fill:hover { background: #333; color: #fff; }
    .btn-outline { background: {{ $tema == 'oscuro' ? 'transparent' : '#fff' }}; color: {{ $tema == 'oscuro' ? '#fff' : '#000' }}; border: 2px solid {{ $tema == 'oscuro' ? '#fff' : '#000' }}; }
    .btn-outline:hover { background: {{ $tema == 'oscuro' ? '#fff' : '#000' }}; color: {{ $tema == 'oscuro' ? '#000' : '#fff' }}; }
    .card { background: {{ $tema == 'oscuro' ? '#1f1f1f' : '#fff' }}; color: {{ $tema == 'oscuro' ? '#e0e0e0' : '#000' }}; border: 2px solid {{ $tema == 'oscuro' ? '#333' : '#dee2e6' }}; }
    .text-muted { color: {{ $tema == 'oscuro' ? '#aaa !important' : '#6c757d !important' }}; }
    @media (max-width: 768px) { .hero h1 { font-size: 2rem; } .hero p { font-size: 1rem; } .hero { padding: 3rem 0; } }
</style>

    <section class="hero">
        <div class="container">
            <h1>Bienvenido a Kctta</h1>
            <p>Descubre nuestra colección de muebles de alta calidad</p>
            <a href="#categorias" class="btn btn-action btn-outline me-2">Explorar Categorías</a>
            <a href="#destacados" class="btn btn-action btn-light-outline">Ver Destacados</a>
        </div>
    </section>

    <section id="categorias" class="py-5">
        <div class="container">
            <h2 class="section-title">Explora Nuestras Categorías</h2>
            @if($categorias->count() > 0)
                <p class="text-center text-muted mb-4">
                    Estas son algunas de nuestras categorías, pero si deseas ver más pulsa el botón de ver más categorías.
                </p>
                <div class="row g-4 mb-4">
                    @foreach($categorias as $categoria)
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $categoria->nombre }}</h5>
                                    <p class="card-text text-muted">{{ $categoria->descripcion }}</p>
                                    <a href="{{ route('categorias.show', $categoria->id) }}" class="btn btn-primary w-100 mb-2">Ver productos</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center">
                    <a href="{{ route('categorias.index') }}" class="btn btn-action btn-outline">Ver más categorías →</a>
                </div>
            @else
                <div class="card p-4 text-center">
                    <p class="text-muted">No hay categorías disponibles en este momento.</p>
                </div>
            @endif
        </div>
    </section>

    <section id="destacados" class="py-5">
        <div class="container">
            <h2 class="section-title">Productos Destacados</h2>
            @if($productos->count() > 0)
                <div class="row g-4">
                    @foreach($productos as $producto)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100">
                                @if($producto->imagen_principal)
                                    <img src="{{ asset('imagenes/' . $producto->imagen_principal) }}" class="card-img-top"
                                        alt="{{ $producto->nombre }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                        style="height: 200px;">
                                        <span class="text-muted">Sin imagen</span>
                                    </div>
                                @endif

                                <div class="card-body text-center d-flex flex-column">
                                    <h5 class="card-title">{{ $producto->nombre }}</h5>
                                    <p class="text-muted small flex-grow-1">{{ Str::limit($producto->descripcion, 60) }}</p>
                                    <p class="mb-3"><strong>
                                        @if($simboloDerecha)
                                            {{ number_format($producto->precio, 2) }} {{ $simboloMoneda }}
                                        @else
                                            {{ $simboloMoneda }} {{ number_format($producto->precio, 2) }}
                                        @endif
                                    </strong></p>

                                    <div class="d-grid gap-2">
                                        @if(auth()->check())
                                            <form method="POST" action="{{ route('carrito.add', $producto->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-primary w-100">Añadir al Carrito</button>
                                            </form>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-primary">Añadir al Carrito</a>
                                        @endif
                                        <a href="{{ route('productos.show', $producto->id) }}" class="btn btn-outline-secondary">Ver detalles</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card p-4 text-center">
                    <p class="text-muted">No hay productos destacados en este momento. ¡Visita nuestras categorías!</p>
                </div>
            @endif

            <div class="text-center mt-5 pt-4">
                <p class="h5 mb-3">¿Deseas ver más de nuestros muebles?</p>
                <p class="text-muted mb-4">Explora nuestro catálogo completo con todos los productos disponibles</p>
                <a href="{{ route('productos.index') }}" class="btn btn-action btn-outline">Explorar Muebles →</a>
            </div>
        </div>
    </section>

@endsection
