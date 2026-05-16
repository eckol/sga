<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SGA-CST') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <style>
        /* Estilos Generales Compactos XS */
        body {
            font-size: 0.8rem;
            background-color: #f4f6f9;
            font-family: 'Figtree', sans-serif;
        }

        /* 1. Barra lateral más angosta (200px) */
        .sidebar {
            min-height: 100vh;
            height: 100vh;
            position: sticky;
            top: 0;
            width: 200px;
            background: #2c3e50;
            color: white;
            transition: all 0.3s;
            overflow-y: auto;
        }

        /* 2. Logo centrado y texto SGA-CST más grande */
        .sidebar-header {
            padding: 15px;
            border-bottom: 1px solid #3e4f5f;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .logo-img {
            width: 55px;
            height: auto;
            margin-bottom: 8px;
        }

        .brand-text {
            font-size: 0.9rem;
            /* Tamaño similar al título del dashboard */
            font-weight: bold;
            color: #ecf0f1;
        }

        /* 3. Títulos de menú colapsables */
        .menu-header {
            padding: 8px 15px 5px;
            font-weight: bold;
            font-size: 0.75rem;
            color: #a0d8ef !important;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            user-select: none;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
        }

        .menu-header .menu-arrow {
            font-size: 0.6rem;
            transition: transform 0.25s ease;
            display: inline-block;
        }

        .menu-header.open .menu-arrow {
            transform: rotate(180deg);
        }

        /* Panel de enlaces: oculto por defecto con max-height */
        .menu-links {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .menu-links.open {
            max-height: 400px;
        }

        .sidebar .nav-link {
            color: #bdc3c7;
            padding: 5px 15px;
            font-size: 0.75rem;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: #34495e;
        }

        /* 4. Topbar con alineación vertical centralizada */
        .navbar-custom {
            background: white;
            border-bottom: 1px solid #dee2e6;
            height: 50px;
            /* Altura fija para control de alineación */
            display: flex;
            align-items: center;
            padding: 0 15px;
        }

        .user-profile-section {
            display: flex;
            align-items: center;
            /* Centro vertical */
            gap: 10px;
        }

        .user-name {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .dropdown-item {
            font-size: 0.75rem !important;
        }

        /* Ajuste de la flecha del dropdown al costado */
        .dropdown-toggle::after {
            vertical-align: middle;
            margin-left: 8px;
        }

        @media (max-width: 768px) {
            .sidebar {
                margin-left: -200px;
                position: absolute;
                z-index: 1000;
            }

            .sidebar.active {
                margin-left: 0;
            }
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">
    <div class="d-flex">
        <nav id="sidebar" class="sidebar flex-column">
            <div class="sidebar-header">
                <img src="{{ asset('img/logo_cst.png') }}" alt="CST" class="logo-img">
                <div class="brand-text">SGA - CST</div>
            </div>

            <div class="mt-3" id="sidebarAccordion">
                <div class="menu-header" style="cursor:default;">Principal</div>
                <a href="{{ route('dashboard') }}" class="nav-link active">Dashboard</a>

                <button class="menu-header" onclick="toggleMenu(this, 'menuAlumnado')">
                    Alumnado <span class="menu-arrow">&#9660;</span>
                </button>
                <div class="menu-links" id="menuAlumnado">
                    <a href="{{ route('alumnos.index') }}" class="nav-link">Alumnos</a>
                    <a href="{{ route('responsables.index', ['tipo' => 'padres']) }}" class="nav-link">Padres</a>
                    <a href="{{ route('responsables.index', ['tipo' => 'madres']) }}" class="nav-link">Madres</a>
                    <a href="{{ route('responsables.index', ['tipo' => 'encargados']) }}"
                        class="nav-link">Encargados</a>
                </div>
                <button class="menu-header" onclick="toggleMenu(this, 'menuColaboradores')">
                    Colaboradores <span class="menu-arrow">&#9660;</span>
                </button>
                <div class="menu-links" id="menuColaboradores">
                    <a href="{{ route('colaboradores.index') }}" class="nav-link">Colaboradores</a>
                </div>
                <button class="menu-header" onclick="toggleMenu(this, 'menuAcademica')">
                    Gestión Académica <span class="menu-arrow">&#9660;</span>
                </button>
                <div class="menu-links" id="menuAcademica">
                    <a href="{{ route('academica.alumnos-grado') }}" class="nav-link">Alumnos por Grado/Curso</a>
                    <a href="#" class="nav-link">Asignaturas</a>
                    <a href="#" class="nav-link">Horarios de clase</a>
                    <a href="#" class="nav-link">Calificaciones</a>
                    <a href="#" class="nav-link">Asistencia</a>
                </div>

                <button class="menu-header" onclick="toggleMenu(this, 'menuGabinete')">
                    Gabinete <span class="menu-arrow">&#9660;</span>
                </button>
                <div class="menu-links" id="menuGabinete">
                    <a href="#" class="nav-link">Entrevistas</a>
                    <a href="#" class="nav-link">Observaciones</a>
                </div>

                <button class="menu-header" onclick="toggleMenu(this, 'menuInscripciones')">
                    Inscripciones <span class="menu-arrow">&#9660;</span>
                </button>
                <div class="menu-links" id="menuInscripciones">
                    <a href="{{ route('aranceles.index') }}" class="nav-link">Aranceles</a>
                    <a href="{{ route('inscripciones.index') }}" class="nav-link">Inscripciones</a>
                </div>

                <button class="menu-header" onclick="toggleMenu(this, 'menuConfiguracion')">
                    Configuración <span class="menu-arrow">&#9660;</span>
                </button>
                <div class="menu-links" id="menuConfiguracion">
                    <a href="{{ route('ciclos.index') }}" class="nav-link">Ciclos Académicos</a>
                    <a href="{{ route('gradoscursos.index') }}" class="nav-link">Grados y Cursos (GC)</a>
                    <a href="{{ route('ciudades.index') }}" class="nav-link">Ciudades</a>
                    <a href="{{ route('nacionalidades.index') }}" class="nav-link">Nacionalidades</a>
                    <a href="{{ route('sexos.index') }}" class="nav-link">Sexos</a>
                    <a href="{{ route('parentescos.index') }}" class="nav-link">Parentescos</a>
                    <a href="{{ route('vivecon.index') }}" class="nav-link">Vive con</a>
                    <a href="{{ route('horas.index') }}" class="nav-link">Horas</a>
                    <a href="{{ route('estadosciviles.index') }}" class="nav-link">Estados Civiles</a>
                    <a href="{{ route('tiposcolaboradores.index') }}" class="nav-link">Tipos de Colaboradores</a>
                    <a href="{{ route('asignaturas.index') }}" class="nav-link">Asignaturas</a>
                </div>

                <button class="menu-header" onclick="toggleMenu(this, 'menuSeguridad')">
                    Seguridad <span class="menu-arrow">&#9660;</span>
                </button>
                <div class="menu-links" id="menuSeguridad">
                    <a href="{{ route('usuarios.index') }}" class="nav-link">Usuarios</a>
                    <a href="{{ route('roles.index') }}" class="nav-link">Roles</a>
                </div>
            </div>
        </nav>

        <div class="flex-fill">
            <nav class="navbar navbar-expand navbar-custom">
                <button type="button" id="sidebarCollapse" class="btn btn-sm btn-outline-secondary d-md-none">
                    Menú
                </button>

                <div class="ms-auto user-profile-section">
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle"
                            id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=0D8ABC&color=fff"
                                alt="avatar" width="28" height="28" class="rounded-circle">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Mi Perfil</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Cerrar Sesión</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="p-3">
                @isset($header)
                    <div class="mb-3">
                        <h5 class="fw-bold mb-0">{{ $header }}</h5>
                    </div>
                @endisset

                <div class="container-fluid">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    @stack('scripts')
    <script>
        // Menú móvil
        document.getElementById('sidebarCollapse')?.addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // ── Acordeón del sidebar ──────────────────────────────────────────
        var STORAGE_KEY = 'sga_sidebar_open';

        function openPanel(panelId) {
            var panel = document.getElementById(panelId);
            if (!panel) return;
            var btn = document.querySelector('[onclick="toggleMenu(this, \'' + panelId + '\')"]');
            panel.classList.add('open');
            if (btn) btn.classList.add('open');
        }

        function closeAll() {
            document.querySelectorAll('.menu-links.open').forEach(function (p) { p.classList.remove('open'); });
            document.querySelectorAll('.menu-header.open').forEach(function (b) { b.classList.remove('open'); });
        }

        function toggleMenu(btn, panelId) {
            var panel = document.getElementById(panelId);
            var isOpen = panel.classList.contains('open');
            closeAll();
            if (!isOpen) {
                panel.classList.add('open');
                btn.classList.add('open');
                localStorage.setItem(STORAGE_KEY, panelId);
            } else {
                localStorage.removeItem(STORAGE_KEY);
            }
        }

        // ── Al cargar: detectar ruta activa y restaurar estado ────────────
        document.addEventListener('DOMContentLoaded', function () {
            var currentUrl = window.location.href;
            var activePanel = null;

            // 1. Buscar enlace activo dentro de los paneles colapsables
            document.querySelectorAll('.menu-links a.nav-link').forEach(function (link) {
                var href = link.getAttribute('href');
                if (href && href !== '#' && currentUrl.indexOf(href) !== -1) {
                    link.classList.add('active');
                    var parentPanel = link.closest('.menu-links');
                    if (parentPanel) activePanel = parentPanel.id;
                }
            });

            // 2. Ruta activa tiene prioridad sobre localStorage
            if (activePanel) {
                openPanel(activePanel);
                localStorage.setItem(STORAGE_KEY, activePanel);
            } else {
                // 3. Sin ruta activa: restaurar último panel guardado
                var saved = localStorage.getItem(STORAGE_KEY);
                if (saved) openPanel(saved);
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '{{ session("success") }}',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#198754'
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session("error") }}',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#dc3545'
            });
        </script>
    @endif
</body>

</html>