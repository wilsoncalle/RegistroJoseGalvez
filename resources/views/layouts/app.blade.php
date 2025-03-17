<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Gestión Escolar')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .sidebar {
            background-color: #343a40;
            min-width: 250px;
            min-height: calc(100vh - 56px);
            position: fixed;
            top: 56px;
            left: 0;
            z-index: 100;
            overflow-y: auto; /* Añadir esta propiedad para permitir scroll vertical */
            max-height: calc(100vh - 56px); /* Establecer altura máxima */
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: white;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            flex-grow: 1;
        }
        .sidebar-heading {
            color: #adb5bd;
            padding: 10px 20px;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .dropdown-menu {
            min-width: 200px;
        }
        .dropdown-item i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <!-- Barra de navegación superior -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">Sistema de Gestión Escolar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->nombre }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Perfil</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Configuración</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Menú lateral (sidebar) -->
    <div class="sidebar">
        <div class="pt-4"></div>
        <div class="sidebar-heading">
            Académico
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('estudiantes.*') ? 'active' : '' }}" href="{{ route('estudiantes.index') }}">
                    <i class="bi bi-mortarboard me-2"></i> Estudiantes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('docentes.*') ? 'active' : '' }}" href="{{ route('docentes.index') }}">
                    <i class="bi bi-person-badge me-2"></i> Docentes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('apoderados.*') ? 'active' : '' }}" href="{{ route('apoderados.index') }}">
                    <i class="bi bi-people me-2"></i> Apoderados
                </a>
            </li>
        </ul>

        <div class="sidebar-heading">
            Organización
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('aulas.*') ? 'active' : '' }}" href="{{ route('aulas.index') }}">
                    <i class="bi bi-layers me-2"></i> Aulas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('grados.*') ? 'active' : '' }}" href="{{ route('grados.index') }}">
                    <i class="bi bi-diagram-2 me-2"></i> Grados
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('secciones.*') ? 'active' : '' }}" href="{{ route('secciones.index') }}">
                    <i class="bi bi-grid me-2"></i> Secciones
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('materias.*') ? 'active' : '' }}" href="{{ route('materias.index') }}">
                    <i class="bi bi-book me-2"></i> Materias
                </a>
            </li>
        </ul>

        <div class="sidebar-heading">
            Gestión Académica
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('anios.*') ? 'active' : '' }}" href="{{ route('anios.index') }}">
                    <i class="bi bi-calendar-date"></i> Años Académicos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('trimestres.*') ? 'active' : '' }}" href="{{ route('trimestres.index') }}">
                    <i class="bi bi-calendar3 me-2"></i> Trimestres
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('asignaciones.*') ? 'active' : '' }}" href="{{ route('asignaciones.index') }}">
                    <i class="bi bi-person-workspace me-2"></i> Asignaciones
                </a>
            </li>
        </ul>

        <div class="sidebar-heading">
            Control y Evaluación
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('calificaciones.*') ? 'active' : '' }}" href="{{ route('calificaciones.index') }}">
                    <i class="bi bi-journal-check me-2"></i> Calificaciones
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('asistencia.*') ? 'active' : '' }}" href="{{ route('asistencia.index') }}">
                    <i class="bi bi-calendar-check me-2"></i> Asistencia
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('incidentes.*') ? 'active' : '' }}" href="{{ route('incidentes.index') }}">
                    <i class="bi bi-exclamation-triangle me-2"></i> Incidentes
                </a>
            </li>
        </ul>

        <div class="sidebar-heading">
            Sistema
        </div>
        <ul class="nav flex-column mb-5">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" href="{{ route('usuarios.index') }}">
                    <i class="bi bi-person-gear me-2"></i> Usuarios
                </a>
            </li>
        </ul>
    </div>

    <!-- Contenido principal -->
    <div class="main-content mt-5">
        <div class="container-fluid">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>