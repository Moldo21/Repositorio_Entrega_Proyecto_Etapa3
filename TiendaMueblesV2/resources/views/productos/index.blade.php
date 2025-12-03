@extends('cabecera')

@section('contenido')

    @php
        $tema = request()->cookie('tema', 'claro');
    @endphp

    <style>
        body { background-color: {{ $tema == 'oscuro' ? '#121212' : '#fff' }}; color: {{ $tema == 'oscuro' ? '#e0e0e0' : '#000' }}; }
        .card { @if($tema == 'oscuro') background-color: #1f1f1f; color: #e0e0e0; border-color: #333; @endif }
        .card-title { @if($tema == 'oscuro') color: #e0e0e0; @endif }
        .text-muted { @if($tema == 'oscuro') color: #aaa !important; @endif }
        .btn-primary { @if($tema == 'oscuro') background-color: #0d6efd; color: #fff; @endif }
        .btn-secondary, .btn-outline-secondary { @if($tema == 'oscuro') background-color: #444; color: #fff; border-color: #666; @endif }
        .alert { @if($tema == 'oscuro') background-color: #2a2a2a; color: #e0e0e0; border-color: #555; @endif }
        hr { @if($tema == 'oscuro') border-color: #555; @endif }
        .form-control, .form-select { @if($tema == 'oscuro') background-color: #2c2c2c; color: #e0e0e0; border-color: #444; @endif }
        label, .form-label { @if($tema == 'oscuro') color: #e0e0e0; @endif }
        .pagination .page-link { background-color: {{ $tema == 'oscuro' ? 'transparent' : '#fff' }}; color: {{ $tema == 'oscuro' ? '#fff' : '#000' }}; border: 2px solid {{ $tema == 'oscuro' ? '#fff' : '#000' }}; font-weight: 600; border-radius: 8px; padding: 0.6rem 1rem; margin: 0 0.25rem; }
        .pagination .page-link:hover { background-color: {{ $tema == 'oscuro' ? '#fff' : '#000' }}; color: {{ $tema == 'oscuro' ? '#000' : '#fff' }}; }
        .pagination .page-item.active .page-link { background-color: {{ $tema == 'oscuro' ? '#fff' : '#000' }}; color: {{ $tema == 'oscuro' ? '#000' : '#fff' }}; border-color: {{ $tema == 'oscuro' ? '#fff' : '#000' }}; }
        .pagination .page-item.disabled .page-link { @if($tema == 'oscuro') background-color: transparent; color: #666; border-color: #666; @else background-color: #f8f9fa; color: #6c757d; border-color: #dee2e6; @endif }
    </style>
    <h2 class="mb-4">Catálogo de Productos</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="GET" action="{{ route('productos.index') }}" class="row g-3 mb-4">
        <div class="col-md-2">
            <label for="categoria" class="form-label">Categoría</label>
            <select name="categoria" id="categoria" class="form-select">
                <option value="">Todas</option>
                @foreach ($categorias as $c)
                    <option value="{{ $c->id }}" {{ request('categoria') == $c->id ? 'selected' : '' }}>
                        {{ $c->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label for="min" class="form-label">Precio min</label>
            <input type="number" name="min" id="min" class="form-control" step="0.01"
                value="{{ request('min') }}" placeholder="0">
        </div>

        <div class="col-md-2">
            <label for="max" class="form-label">Precio max</label>
            <input type="number" name="max" id="max" class="form-control" step="0.01"
                value="{{ request('max') }}" placeholder="1000">
        </div>

        <div class="col-md-2">
            <label for="color" class="form-label">Color</label>
            <input type="text" name="color" id="color" class="form-control" value="{{ request('color') }}"
                placeholder="Ej. Gris">
        </div>

        <div class="col-md-2">
            <label for="busqueda" class="form-label">Buscar</label>
            <input type="text" name="busqueda" id="busqueda" class="form-control" value="{{ request('busqueda') }}"
                placeholder="Nombre o descripción">
        </div>

        <div class="col-12 mt-2">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="{{ route('productos.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="mb-3">
        <strong>Ordenar por:</strong>
        @php
            $dir = request('dir', 'asc') === 'asc' ? 'desc' : 'asc';
        @endphp
        <a href="{{ route('productos.index', array_merge(request()->query(), ['orden' => 'precio', 'dir' => $dir])) }}">
            Precio @if (request('orden') === 'precio')
                {{ request('dir') === 'asc' ? '↑' : '↓' }}
            @endif
        </a> |
        <a href="{{ route('productos.index', array_merge(request()->query(), ['orden' => 'nombre', 'dir' => $dir])) }}">
            Nombre @if (request('orden') === 'nombre')
                {{ request('dir') === 'asc' ? '↑' : '↓' }}
            @endif
        </a> |
        <a href="{{ route('productos.index', array_merge(request()->query(), ['orden' => 'novedad', 'dir' => $dir])) }}">
            Novedad @if (request('orden') === 'novedad')
                {{ request('dir') === 'asc' ? '↑' : '↓' }}
            @endif
        </a>
    </div>

    @php
        $moneda = Cookie::get('moneda', 'EUR');
        $simbolo = $moneda === 'USD' ? '$' : ($moneda === 'GBP' ? '£' : '€');
        $simboloDerecha = $moneda === 'EUR';
    @endphp

    <div class="row">
        @forelse ($productos as $producto)
            <div class="col-md-3 mb-3">
                <div class="card h-100">

                    @if ($producto->imagen_principal)
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
                        <p class="mb-3"><strong>{{ $simboloDerecha ? number_format($producto->precio, 2) . ' ' . $simbolo : $simbolo . number_format($producto->precio, 2) }}</strong>
                        </p>

                        <div class="d-grid gap-2">
                            @if (auth()->check())
                                <form method="POST" action="{{ route('carrito.add', $producto->id) }}">
                                    @csrf
                                    <button class="btn btn-primary w-100" type="submit">
                                        Añadir al Carrito
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary">
                                    Añadir al Carrito
                                </a>
                            @endif

                            <a href="{{ route('productos.show', $producto->id) }}" class="btn btn-outline-secondary">
                                Ver detalles
                            </a>

                            @if (auth()->check() && auth()->user()->rol_id === 1)
                                <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-warning btn-sm">
                                    Editar
                                </a>
                                <form action="{{ route('productos.destroy', $producto->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm w-100"
                                        onclick="return confirm('¿Seguro que deseas eliminar este producto?');">
                                        Eliminar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">No se encontraron productos que coincidan con los filtros.</p>
        @endforelse
    </div>

    @if ($productos->hasPages())
        <div class="mt-4">
            <nav>
                <ul class="pagination justify-content-center">

                    @if ($productos->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">« Anterior</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $productos->previousPageUrl() }}"
                                rel="prev">« Anterior</a></li>
                    @endif

                    @foreach ($productos->getUrlRange(1, $productos->lastPage()) as $page => $url)
                        @if ($page == $productos->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link"
                                    href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach

                    @if ($productos->hasMorePages())
                        <li class="page-item"><a class="page-link" href="{{ $productos->nextPageUrl() }}"
                                rel="next">Siguiente »</a></li>
                    @else
                        <li class="page-item disabled"><span class="page-link">Siguiente »</span></li>
                    @endif
                </ul>
            </nav>
        </div>
    @endif

    <hr>
    <div class="text-center mt-3">
        <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary">
            ← Volver a Categorías
        </a>
    </div>
@endsection
