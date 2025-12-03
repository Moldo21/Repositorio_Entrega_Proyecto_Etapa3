@extends('cabecera')

@section('contenido')
    @php
        $tema = request()->cookie('tema', 'claro');
    @endphp

    <style>
        body {
            @if ($tema == 'oscuro')
                background-color: #121212;
                color: #e0e0e0;
            @else
                background-color: #fff;
                color: #000;
            @endif
        }

        .card {
            @if ($tema == 'oscuro')
                background-color: #1f1f1f;
                color: #e0e0e0;
                border-color: #333;
            @endif
        }

        .card-title {
            @if ($tema == 'oscuro')
                color: #e0e0e0;
            @endif
        }

        .text-muted {
            @if ($tema == 'oscuro')
                color: #aaa !important;
            @endif
        }
        .btn-primary {
            @if ($tema == 'oscuro')
                background-color: #0d6efd;
                color: #fff;
            @endif
        }

        .btn-warning {
            @if ($tema == 'oscuro')
                background-color: #f59e0b;
                color: #000;
            @endif
        }

        .btn-danger {
            @if ($tema == 'oscuro')
                background-color: #dc3545;
                color: #fff;
            @endif
        }

        .btn-outline-secondary {
            @if ($tema == 'oscuro')
                background-color: #444;
                color: #fff;
                border-color: #666;
            @endif
        }

        hr {
            @if ($tema == 'oscuro')
                border-color: #555;
            @endif
        }
    </style>

    <div class="container mt-4">
        <h2 class="text-center mb-4">Elige una categoría</h2>

        @if ($categorias->isEmpty())
            <div class="text-center mt-4">
                <p>No hay categorías disponibles.</p>
            </div>
        @else
            <div class="row justify-content-center">
                @foreach ($categorias as $categoria)
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $categoria->nombre }}</h5>
                                <p class="card-text text-muted">{{ $categoria->descripcion }}</p>

                                <a href="{{ route('categorias.show', $categoria->id) }}" class="btn btn-primary w-100 mb-2"
                                    title="Ver productos de {{ $categoria->nombre }}">
                                    Ver productos
                                </a>

                                @if (auth()->check() && auth()->user()->rol_id === 1)
                                    <a href="{{ route('categorias.edit', $categoria->id) }}"
                                        class="btn btn-warning w-100 mb-2"
                                        title="Editar categoría {{ $categoria->nombre }}">
                                        Editar
                                    </a>

                                    <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST"
                                        onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100">
                                            Eliminar
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <hr>
        <div class="text-center mt-3">
            <a href="{{ route('principal') }}" class="btn btn-outline-secondary me-2">
                ← Volver al inicio
            </a>
            <a href="{{ route('productos.index') }}" class="btn btn-primary">
                Ver todos los productos
            </a>
        </div>
    </div>
@endsection
