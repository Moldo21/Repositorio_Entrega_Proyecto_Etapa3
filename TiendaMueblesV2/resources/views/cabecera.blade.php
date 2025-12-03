<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tienda de Muebles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #fff;
            color: #000;
        }

        .navbar {
            background: #000 !important;
            padding: 1.5rem 0 !important;
        }

        .navbar-brand {
            color: #fff !important;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        .nav-link:hover {
            color: #fff !important;
        }

        .card {
            border: 2px solid #000;
            border-radius: 10px;
        }

        footer {
            background: #000;
            color: #fff;
            padding: 2rem 0;
            margin-top: 3rem;
            text-align: center;
        }

        .btn-action {
            font-weight: 600;
            border-radius: 8px;
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
        }

        .btn-outline {
            background: #fff;
            color: #000;
            border: 2px solid #000;
        }

        .btn-outline:hover {
            background: #000;
            color: #fff;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a href="{{ route('principal') }}" class="navbar-brand">Kctta</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('principal') }}">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('categorias.index') }}">Categorías</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('productos.index') }}">Productos</a></li>
                    @if (auth()->check())
                        <li class="nav-item"><a class="nav-link" href="{{ route('carrito.show') }}">Carrito</a></li>
                        <li class="nav-item">
                            <a href="{{ route('preferencias.edit') }}" class="nav-link">Preferencias</a>
                        </li>
                        @if (auth()->user()->rol_id === 1)
                            <li class="nav-item"><a class="nav-link" href="{{ route('productos.create') }}">Crear
                                    Producto</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('categorias.create') }}">Crear
                                    Categoría</a></li>
                        @endif
                        <li class="nav-item"><span class="nav-link text-white">Bienvenido,
                                {{ auth()->user()->nombre }}</span></li>

                        <li class="nav-item">
                            <form action="{{ route('login.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-outline-light btn-sm">Cerrar sesión</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a href="{{ route('login') }}"
                                class="btn btn-action btn-outline ms-2">Iniciar sesión</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('contenido')
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2025 Kctta - Tienda de Muebles</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
