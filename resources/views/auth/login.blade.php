<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión Escolar - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f8fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            max-width: 400px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo h1 {
            color: #3490dc;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #3490dc;
            border-color: #3490dc;
            width: 100%;
            padding: 10px;
        }
        .alert {
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>Gestión Escolar</h1>
            <p>Accede a tu cuenta para continuar</p>
        </div>

        @if(session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login.authenticate') }}">
            @csrf
            <div class="mb-3">
                <label for="identifier" class="form-label">Email o Nombre de Usuario</label>
                <input type="text" class="form-control @error('identifier') is-invalid @enderror" 
                       id="identifier" name="identifier" value="{{ old('identifier') }}" required autofocus>
                @error('identifier')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       id="password" name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Recordarme</label>
            </div>

            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>