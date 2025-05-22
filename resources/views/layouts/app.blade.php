<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Gestión Escolar')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Barra de navegación superior con efecto blur y fecha reposicionada -->
    <nav class="navbar navbar-expand-lg fixed-top navbar-blur">
        <div class="container-fluid">
            <div class="navbar-brand-container">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <i></i>Sistema de Gestión Escolar
                </a>
                <!-- Markup para la fecha con día de la semana -->
                <div class="navbar-date">
                    <span id="current-date">{{ date('l, d \d\e F \d\e Y') }}</span>
                </div>
            </div>
            
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
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="bi bi-person"></i> Perfil</a></li>
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
                <a class="nav-link {{ request()->routeIs('calificaciones-old.*') ? 'active' : '' }}" href="{{ route('calificaciones-old.index') }}">
                    <i class="bi bi-journal-check me-2"></i> Calificaciones Viejas
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
    <div class="main-content mt-5 fade-transition">
        <div class="container-fluid">
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
    @stack('scripts')
    <script>
        // Función inmediata para restaurar la posición del scroll lo antes posible
        (function() {
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) {
                const savedScrollPosition = localStorage.getItem('sidebarScrollPosition');
                if (savedScrollPosition) {
                    // Desactivar temporalmente el comportamiento suave para la restauración inicial
                    sidebar.style.scrollBehavior = 'auto';
                    sidebar.scrollTop = parseInt(savedScrollPosition);
                }
            }
        })();
        
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            
            if (sidebar) {
                // Establecer comportamiento suave para futuros scrolls iniciados por el usuario
                setTimeout(() => {
                    sidebar.style.scrollBehavior = 'smooth';
                }, 100);
                
                // Aplicar la posición guardada nuevamente después de cualquier manipulación del DOM
                const savedScrollPosition = localStorage.getItem('sidebarScrollPosition');
                if (savedScrollPosition) {
                    // Usar requestAnimationFrame para asegurar que ocurra en el próximo ciclo de pintado
                    requestAnimationFrame(function() {
                        sidebar.style.scrollBehavior = 'auto';
                        sidebar.scrollTop = parseInt(savedScrollPosition);
                        
                        // Restaurar el comportamiento suave después de establecer la posición
                        setTimeout(() => {
                            sidebar.style.scrollBehavior = 'smooth';
                        }, 50);
                    });
                }
                
                // Guardar la posición del scroll cuando cambia, pero con throttling
                let scrollTimeoutId;
                sidebar.addEventListener('scroll', function() {
                    clearTimeout(scrollTimeoutId);
                    scrollTimeoutId = setTimeout(() => {
                        localStorage.setItem('sidebarScrollPosition', sidebar.scrollTop);
                    }, 100);
                });
                
                // Mejorar el manejo de clics en los enlaces
                document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                    link.addEventListener('click', function(event) {
                        // Solo para enlaces que navegan a otra página (no tienen preventDefault)
                        if (link.getAttribute('href') && link.getAttribute('href') !== '#') {
                            // Guardar la posición actual
                            localStorage.setItem('sidebarScrollPosition', sidebar.scrollTop);
                            
                            // Añadir un pequeño retraso para asegurar que se guarde la posición
                            if (!link.getAttribute('target')) {
                                const href = link.getAttribute('href');
                                if (!event.ctrlKey && !event.metaKey) {
                                    event.preventDefault();
                                    setTimeout(() => {
                                        window.location.href = href;
                                    }, 10);
                                }
                            }
                        }
                    });
                });
            }
        });
        
        // Prevenir parpadeos en la navegación mediante history API
        window.addEventListener('pageshow', function(event) {
            // Verificar si la página se cargó desde la caché (navegación hacia atrás/adelante)
            if (event.persisted) {
                const sidebar = document.querySelector('.sidebar');
                if (sidebar) {
                    // Desactivar animaciones brevemente
                    sidebar.style.transition = 'none';
                    
                    // Restaurar la posición del scroll
                    const savedScrollPosition = localStorage.getItem('sidebarScrollPosition');
                    if (savedScrollPosition) {
                        sidebar.scrollTop = parseInt(savedScrollPosition);
                    }
                    
                    // Reactivar animaciones
                    setTimeout(() => {
                        sidebar.style.transition = '';
                        sidebar.style.scrollBehavior = 'smooth';
                    }, 50);
                }
            }
        });
    </script>
    <!-- Script para corregir problemas de modales -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Asegurar que todos los modales se muevan directamente al body
            document.querySelectorAll('.modal').forEach(function(modal) {
                // Solo mover si no es hijo directo del body
                if (modal.parentElement !== document.body) {
                    document.body.appendChild(modal);
                }
            });
            
            // Corregir problemas de z-index dinámicamente
            $(document).on('show.bs.modal', '.modal', function() {
                // Asegurar que el modal esté por encima del backdrop
                $(this).css('z-index', 1080);
                
                // Asegurar que el backdrop esté visible pero por debajo del modal
                setTimeout(function() {
                    $('.modal-backdrop').css('z-index', 1070);
                }, 10);
            });
        });
    </script>
    <!-- Script para actualizar la fecha dinámicamente con día de la semana -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Función para actualizar la fecha con día de la semana
        function updateDate() {
            const dateElement = document.getElementById('current-date');
            if (!dateElement) return;
            
            const now = new Date();
            
            // Nombres de los días de la semana en español
            const weekdays = [
                'Domingo', 'Lunes', 'Martes', 'Miércoles', 
                'Jueves', 'Viernes', 'Sábado'
            ];
            
            // Nombres de los meses en español
            const months = [
                'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 
                'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
            ];
            
            // Obtener el día de la semana
            const weekday = weekdays[now.getDay()];
            
            // Formatear la fecha completa en español con día de la semana
            let formattedDate;
            
            try {
                // Intentar usar la API Intl si está disponible
                const options = { 
                    weekday: 'long', 
                    day: 'numeric', 
                    month: 'long', 
                    year: 'numeric' 
                };
                formattedDate = now.toLocaleDateString('es-ES', options);
            } catch (e) {
                // Fallback manual con nuestra propia implementación
                formattedDate = weekday + ', ' + 
                                now.getDate() + ' de ' + 
                                months[now.getMonth()] + ' de ' + 
                                now.getFullYear();
            }
            
            // Actualizar el texto del elemento con la fecha formateada
            dateElement.textContent = formattedDate;
        }
        
        // Actualizar la fecha al cargar la página
        updateDate();
        
        // Actualizar la fecha cada minuto
        setInterval(updateDate, 60000);
    });
    </script>
    <script>
        function fetchPage(url, updateHistory = true) {
            // Mostrar indicador de carga
            document.body.classList.add('loading');
            
            // Realizar la petición
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html, application/xhtml+xml'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Parsear la respuesta HTML
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Extraer el contenido principal
                const newContent = doc.querySelector('.main-content');
                if (newContent) {
                    // Actualizar el contenido con una transición suave
                    const currentContent = document.querySelector('.main-content');
                    currentContent.style.opacity = '0';
                    
                    setTimeout(() => {
                        currentContent.innerHTML = newContent.innerHTML;
                        currentContent.style.opacity = '1';
                        
                        // Actualizar el título
                        document.title = doc.title;
                        
                        // Actualizar el historial si es necesario
                        if (updateHistory) {
                            history.pushState({url: url}, doc.title, url);
                        }
                        
                        // Ejecutar scripts nuevos
                        executeScripts(doc);
                        
                        // Quitar indicador de carga
                        document.body.classList.remove('loading');
                    }, 300);
                }
            })
            .catch(error => {
                console.error('Error al cargar la página:', error);
                document.body.classList.remove('loading');
                window.location.href = url;
            });
        }

        // Manejar la navegación hacia atrás/adelante
        window.addEventListener('popstate', function(event) {
            if (event.state && event.state.url) {
                fetchPage(event.state.url, false);
            }
        });

        // Inicializar la navegación AJAX
        document.addEventListener('DOMContentLoaded', function() {
            initAjaxNavigation();
            initDynamicForms();
        });
    </script>
    <style>
        /* Mejorar el rendimiento del scroll */
        .sidebar {
            will-change: scroll-position;
            -webkit-overflow-scrolling: touch;
            overflow-y: auto;
            transform: translateZ(0);
            backface-visibility: hidden;
            perspective: 1000px;
            transition: transform 0.2s ease-out;
        }

        /* Transiciones más suaves */
        .main-content {
            opacity: 1;
            transition: opacity 0.3s ease-in-out;
            background-color: var(--bg-light);
        }

        /* Prevenir el fundido negro */
        body {
            background-color: var(--bg-light);
            transition: background-color 0.3s ease-in-out;
        }

        /* Mejorar el indicador de carga */
        .loading {
            position: relative;
        }

        .loading::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .loading.loading::after {
            opacity: 1;
        }

        /* Optimizar las transiciones de la barra de navegación */
        .navbar-blur {
            background-color: rgba(255, 255, 255, 0.7) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            transition: background-color 0.3s ease-in-out;
        }

        /* Contenedor para el título y la fecha */
        .navbar-brand-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            line-height: 1.2;
            padding-right: 1rem;
        }

        /* Título principal */
        .navbar-brand {
            margin-bottom: 0.15rem;
            padding-bottom: 0;
            font-weight: bold;
        }

        /* Estilo para la fecha debajo del título */
        .navbar-date {
            font-size: 0.8rem;
            color: rgba(0, 0, 0, 0.6);
            font-weight: 400;
            display: flex;
            align-items: center;
            letter-spacing: 0.01rem;
            transition: color 0.3s ease;
        }

        /* Icono del calendario */
        .navbar-date i {
            font-size: 0.85rem;
            opacity: 0.8;
            margin-right: 0.4rem;
            color: var(--bs-primary, #0d6efd);
        }

        /* Efecto hover para el contenedor del título */
        .navbar-brand-container:hover .navbar-date {
            color: rgba(0, 0, 0, 0.85);
        }

        /* Transición general sólo para navbar y título */
        .navbar-blur,
        .navbar-brand {
            transition: all 0.3s ease;
        }

        /* Sólo transición de color para la fecha */
        .navbar-date {
            transition: color 0.3s ease;
        }


        /* Mejores estilos para el menú desplegable */
        .dropdown-menu {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Ajustes responsive */
        @media (max-width: 767.98px) {
            .navbar-brand-container {
                max-width: 70%;
            }
            
            .navbar-date {
                font-size: 0.7rem;
            }
            
            .navbar-brand {
                font-size: 0.9rem;
            }
        }

        /* Estilos adicionales para una mejor experiencia durante la navegación AJAX */
        /* Indicador de carga para cuando se está realizando la navegación AJAX */
        .loading {
            cursor: progress;
        }

        .loading .main-content {
            transition: opacity 0.2s ease-out;
        }

        /* Mejora la transición entre páginas */
        .main-content {
            transition: opacity 0.3s ease-in-out;
        }

        /* Efecto de resaltado para el enlace activo actual */
        .sidebar .nav-link.ajax-active {
            background-color: rgba(13, 110, 253, 0.1);
            color: var(--bs-primary);
            font-weight: 500;
            position: relative;
        }

        .sidebar .nav-link.ajax-active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background-color: var(--bs-primary);
            border-radius: 0 2px 2px 0;
        }

        /* Animación sutil para indicar la carga */
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }

        .loading .main-content {
            animation: pulse 1s infinite;
        }
        
    </style>
</body>
</html>

