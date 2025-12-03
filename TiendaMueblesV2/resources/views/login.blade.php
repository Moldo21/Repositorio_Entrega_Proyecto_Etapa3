<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Kctta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f5f5; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 0; }
        .login-container { width: 100%; max-width: 480px; padding: 0 1rem; }
        .login-card { background: #fff; border: 2px solid #000; border-radius: 10px; padding: 2.25rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .brand-name { font-size: 1.5rem; font-weight: 700; color: #000; text-decoration: none; }
        .brand-name:hover { color: #333; }
        .login-title { font-size: 1.75rem; font-weight: 700; margin-bottom: 1.25rem; text-align: center; }
        .form-group { margin-bottom: 1rem; }
        .form-label { font-weight: 600; margin-bottom: 0.5rem; }
        .form-control { padding: 0.75rem; border: 2px solid #ddd; border-radius: 8px; }
        .form-control:focus { border-color: #000; box-shadow: 0 0 0 0.2rem rgba(0,0,0,0.1); }
        .btn-login { background: #000; color: #fff; border: 2px solid #000; padding: 0.75rem; border-radius: 8px; font-weight: 600; width: 100%; }
        .btn-login:hover { background: #fff; color: #000; }
        .register-link { text-align: center; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #ddd; }
        .register-link a { color: #000; font-weight: 600; text-decoration: none; border-bottom: 2px solid #000; }
        .register-link a:hover { opacity: 0.7; }
        .alert { border-radius: 8px; padding: 0.75rem; margin-bottom: 1rem; border: 2px solid; }
        .alert-success { background: #d4edda; border-color: #28a745; color: #155724; }
        .alert-danger { background: #f8d7da; border-color: #dc3545; color: #721c24; }
        .alert ul { margin: 0; padding-left: 1.25rem; }
        .back-link { text-align: center; margin-top: 1rem; }
        .back-link a { color: #666; text-decoration: none; font-size: 0.95rem; }
        .back-link a:hover { color: #000; }
        .brand-link { text-align: center; margin-bottom: 0.75rem; }
        @media (max-width: 576px) { .login-card { padding: 1.5rem; } .login-title { font-size: 1.5rem; } .brand-name { font-size: 1.25rem; } }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="brand-link">
                <a href="{{ route('principal') }}" class="brand-name">Kctta</a>
            </div>

            <h1 class="login-title">Iniciar sesión</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" name="email" id="email" class="form-control"
                       value="{{ old('email') }}" placeholder="ejemplo@gmail.com" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control"
                       placeholder="Introduce tu contraseña" required>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="recuerdame" value="1" id="recuerdame" class="form-check-input">
                <label for="recuerdame" class="form-check-label">Recordarme</label>
            </div>

            <button type="submit" class="btn btn-login">Iniciar sesión</button>

            <div class="register-link">
                <span class="text-muted">¿No tienes cuenta?</span>
                <a href="{{ route('register.show') }}">Regístrate aquí</a>
            </div>
        </form>

        <div class="back-link">
            <a href="{{ route('principal') }}">← Volver a la página principal</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
