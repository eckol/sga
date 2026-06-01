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

    <style>
        body {
            font-size: 0.8rem;
            background-color: #f4f6f9;
            font-family: 'Figtree', sans-serif;
        }

        /* ── Sidebar ─────────────────────────────── */
        .sidebar {
            min-height: 100vh;
            height: 100vh;
            position: sticky;
            top: 0;
            width: 210px;
            min-width: 210px;
            background: linear-gradient(180deg, #1a2535 0%, #2c3e50 100%);
            color: white;
            transition: width 0.3s ease, min-width 0.3s ease, margin-left 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1050;
            scrollbar-width: thin;
            scrollbar-color: #3e5060 transparent;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #3e5060;
            border-radius: 2px;
        }

        .sidebar.collapsed {
            width: 0;
            min-width: 0;
            overflow: hidden;
        }

        .flex-fill {
            transition: all 0.3s ease;
            min-width: 0;
        }

        /* ── Logo / header ───────────────────────── */
        .sidebar-header {
            padding: 16px 12px 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .logo-img {
            width: 50px;
            height: auto;
            margin-bottom: 6px;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }

        .brand-text {
            font-size: 0.85rem;
            font-weight: 700;
            color: #ecf0f1;
            letter-spacing: 1px;
        }

        /* ── Sección / título de grupo ───────────── */
        .menu-section-label {
            padding: 14px 14px 4px;
            font-size: 0.62rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.35);
            letter-spacing: 1.2px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        /* ── Botón de grupo colapsable ───────────── */
        .menu-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 7px 14px;
            background: none;
            border: none;
            color: #a0c4de;
            font-size: 0.73rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            cursor: pointer;
            text-align: left;
            border-radius: 0;
            transition: background 0.15s, color 0.15s;
            user-select: none;
            /* Evita que el click se "trague" el nav-link de abajo */
            position: relative;
            z-index: 1;
        }

        .menu-header:hover {
            background: rgba(255, 255, 255, 0.06);
            color: #d0e8f5;
        }

        .menu-header.open {
            color: #7ec8f0;
            background: rgba(126, 200, 240, 0.07);
        }

        .menu-arrow {
            font-size: 0.55rem;
            transition: transform 0.22s ease;
            opacity: 0.6;
            flex-shrink: 0;
        }

        .menu-header.open .menu-arrow {
            transform: rotate(180deg);
            opacity: 1;
        }

        /* ── Panel colapsable ────────────────────── */
        .menu-links {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.28s ease;
        }

        .menu-links.open {
            max-height: 600px;
        }

        /* ── Nav links ───────────────────────────── */
        .sidebar .nav-link {
            display: block;
            color: #b0bec5;
            padding: 5px 14px 5px 24px;
            font-size: 0.73rem;
            line-height: 1.5;
            border-left: 2px solid transparent;
            transition: color 0.15s, background 0.15s, border-color 0.15s;
            white-space: nowrap;
            text-decoration: none;
            /* Asegura que el área de click sea solo del link */
            position: relative;
            z-index: 2;
        }

        .sidebar .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.07);
            border-left-color: rgba(126, 200, 240, 0.4);
        }

        .sidebar .nav-link.active {
            color: #ffffff;
            background: rgba(126, 200, 240, 0.13);
            border-left-color: #7ec8f0;
            font-weight: 600;
        }

        /* Dashboard link directo */
        .sidebar .nav-link-direct {
            padding-left: 14px;
            border-left-color: transparent;
        }

        /* ── Topbar ──────────────────────────────── */
        .navbar-custom {
            background: #ffffff;
            border-bottom: 1px solid #e8ecf0;
            height: 50px;
            display: flex;
            align-items: center;
            padding: 0 16px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .user-profile-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-name {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .dropdown-item {
            font-size: 0.75rem !important;
        }

        .dropdown-toggle::after {
            vertical-align: middle;
            margin-left: 8px;
        }

        /* ── Mobile ──────────────────────────────── */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                height: 100vh;
                margin-left: -210px;
                width: 210px !important;
                min-width: 210px !important;
            }

            .sidebar.mobile-open {
                margin-left: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.45);
                z-index: 1040;
            }

            .sidebar-overlay.active {
                display: block;
            }
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="d-flex">
        <nav id="sidebar" class="sidebar flex-column">
            <div class="sidebar-header">
                <img src="{{ asset('img/logo_cst.png') }}" alt="CST" class="logo-img">
                <div class="brand-text">SGA · CST</div>
            </div>

            <div class="mt-2" id="sidebarNav">

                <div class="menu-section-label">Principal</div>
                <a href="{{ route('dashboard') }}" class="nav-link nav-link-direct">
                    <i class="fas fa-home me-1" style="width:13px;opacity:.7;"></i> Dashboard
                </a>

                <div class="menu-section-label">Gestión</div>

                <button type="button" class="menu-header" data-target="menuAlumnado">
                    <span><i class="fas fa-users me-1" style="width:13px;opacity:.7;"></i> Alumnado</span>
                    <span class="menu-arrow">&#9660;</span>
                </button>
                <div class="menu-links" id="menuAlumnado">
                    <a href="{{ route('alumnos.index') }}" class="nav-link">Alumnos</a>
                    <a href="{{ route('responsables.index', ['tipo' => 'padres']) }}" class="nav-link">Padres</a>
                    <a href="{{ route('responsables.index', ['tipo' => 'madres']) }}" class="nav-link">Madres</a>
                    <a href="{{ route('responsables.index', ['tipo' => 'encargados']) }}"
                        class="nav-link">Encargados</a>
                </div>

                <button type="button" class="menu-header" data-target="menuColaboradores">
                    <span><i class="fas fa-id-badge me-1" style="width:13px;opacity:.7;"></i> Colaboradores</span>
                    <span class="menu-arrow">&#9660;</span>
                </button>
                <div class="menu-links" id="menuColaboradores">
                    <a href="{{ route('colaboradores.index') }}" class="nav-link">Colaboradores</a>
                    <a href="{{ route('periodos-laborales.index') }}" class="nav-link">Períodos Laborales</a>
                </div>

                <button type="button" class="menu-header" data-target="menuAcademica">
                    <span><i class="fas fa-book-open me-1" style="width:13px;opacity:.7;"></i> Gestión Académica</span>
                    <span class="menu-arrow">&#9660;</span>
                </button>
                <div class="menu-links" id="menuAcademica">
                    <a href="{{ route('academica.alumnos-grado') }}" class="nav-link">Alumnos por Grado/Curso</a>
                    <a href="{{ route('academica.docentes-asignatura.index') }}" class="nav-link">Asignaturas por
                        docente</a>
                    <a href="{{ route('academica.horarios.index') }}" class="nav-link">Horarios de clase</a>
                    <a href="{{ route('academica.faltas.index') }}" class="nav-link">Faltas</a>
                    <a href="{{ route('asistencias.index') }}" class="nav-link">Asistencia</a>
                    <a href="#" class="nav-link">Calificaciones</a>
                </div>

                <button type="button" class="menu-header" data-target="menuGabinete">
                    <span><i class="fas fa-comments me-1" style="width:13px;opacity:.7;"></i> Gabinete</span>
                    <span class="menu-arrow">&#9660;</span>
                </button>
                <div class="menu-links" id="menuGabinete">
                    <a href="{{ route('entrevistas.index') }}" class="nav-link">Entrevistas</a>
                    <a href="#" class="nav-link">Observaciones</a>
                </div>

                <button type="button" class="menu-header" data-target="menuInscripciones">
                    <span><i class="fas fa-file-alt me-1" style="width:13px;opacity:.7;"></i> Inscripciones</span>
                    <span class="menu-arrow">&#9660;</span>
                </button>
                <div class="menu-links" id="menuInscripciones">
                    <a href="{{ route('aranceles.index') }}" class="nav-link">Aranceles</a>
                    <a href="{{ route('inscripciones.index') }}" class="nav-link">Inscripciones</a>
                </div>

                <button type="button" class="menu-header" data-target="menuPortalResponsables">
                    <span><i class="fas fa-door-open me-1" style="width:13px;opacity:.7;"></i> Portal
                        Responsables</span>
                    <span class="menu-arrow">&#9660;</span>
                </button>
                <div class="menu-links" id="menuPortalResponsables">
                    <a href="{{ route('portal_responsables.index') }}" class="nav-link">Portal de Responsables</a>
                </div>

                <div class="menu-section-label">Sistema</div>

                <button type="button" class="menu-header" data-target="menuConfiguracion">
                    <span><i class="fas fa-cog me-1" style="width:13px;opacity:.7;"></i> Configuración</span>
                    <span class="menu-arrow">&#9660;</span>
                </button>
                <div class="menu-links" id="menuConfiguracion">
                    <a href="{{ route('ciclos.index') }}" class="nav-link">Ciclos Académicos</a>
                    <a href="{{ route('gradoscursos.index') }}" class="nav-link">Grados y Cursos (GC)</a>
                    <a href="{{ route('ciudades.index') }}" class="nav-link">Ciudades</a>
                    <a href="{{ route('nacionalidades.index') }}" class="nav-link">Nacionalidades</a>
                    <a href="{{ route('sexos.index') }}" class="nav-link">Sexos</a>
                    <a href="{{ route('parentescos.index') }}" class="nav-link">Parentescos</a>
                    <a href="{{ route('vivecon.index') }}" class="nav-link">Vive con</a>
                    <a href="{{ route('horas.index') }}" class="nav-link">Horas cátedra</a>
                    <a href="{{ route('estadosciviles.index') }}" class="nav-link">Estados Civiles</a>
                    <a href="{{ route('tiposcolaboradores.index') }}" class="nav-link">Tipos de Colaboradores</a>
                    <a href="{{ route('asignaturas.index') }}" class="nav-link">Asignaturas</a>
                    <a href="{{ route('indicadores_faltas.index') }}" class="nav-link">Indicadores de Faltas</a>
                </div>

                <button type="button" class="menu-header" data-target="menuSeguridad">
                    <span><i class="fas fa-lock me-1" style="width:13px;opacity:.7;"></i> Seguridad</span>
                    <span class="menu-arrow">&#9660;</span>
                </button>
                <div class="menu-links" id="menuSeguridad">
                    <a href="{{ route('usuarios.index') }}" class="nav-link">Usuarios</a>
                    <a href="{{ route('roles.index') }}" class="nav-link">Roles</a>
                </div>

            </div>
        </nav>

        <div class="flex-fill">
            <nav class="navbar navbar-expand navbar-custom">
                <button type="button" id="sidebarToggle" class="btn btn-sm btn-outline-secondary me-3">
                    <i class="fas fa-bars"></i>
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
        (function () {
            'use strict';

            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('sidebarOverlay');
            var toggle = document.getElementById('sidebarToggle');
            var STORE_KEY = 'sga_open_panel';
            var COLL_KEY = 'sga_collapsed';
            var isMobile = function () { return window.innerWidth <= 768; };

            /* ── Sidebar toggle ──────────────────────── */
            function openSidebar() {
                if (isMobile()) {
                    sidebar.classList.add('mobile-open');
                    overlay.classList.add('active');
                } else {
                    sidebar.classList.remove('collapsed');
                    localStorage.setItem(COLL_KEY, '0');
                }
            }

            function closeSidebar() {
                if (isMobile()) {
                    sidebar.classList.remove('mobile-open');
                    overlay.classList.remove('active');
                } else {
                    sidebar.classList.add('collapsed');
                    localStorage.setItem(COLL_KEY, '1');
                }
            }

            function toggleSidebar() {
                if (isMobile()) {
                    sidebar.classList.contains('mobile-open') ? closeSidebar() : openSidebar();
                } else {
                    sidebar.classList.contains('collapsed') ? openSidebar() : closeSidebar();
                }
            }

            toggle.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', closeSidebar);

            /* Restaurar estado desktop */
            if (!isMobile() && localStorage.getItem(COLL_KEY) === '1') {
                sidebar.classList.add('collapsed');
            }

            /* ── Acordeón ────────────────────────────── */
            function getPanelId(btn) { return btn.getAttribute('data-target'); }

            function openPanel(panelId) {
                var panel = document.getElementById(panelId);
                var btn = document.querySelector('[data-target="' + panelId + '"]');
                if (!panel) return;
                panel.classList.add('open');
                if (btn) btn.classList.add('open');
            }

            function closePanel(panelId) {
                var panel = document.getElementById(panelId);
                var btn = document.querySelector('[data-target="' + panelId + '"]');
                if (!panel) return;
                panel.classList.remove('open');
                if (btn) btn.classList.remove('open');
            }

            function closeAll() {
                document.querySelectorAll('.menu-links.open').forEach(function (p) {
                    closePanel(p.id);
                });
            }

            /* Delegación de eventos: un solo listener en el contenedor */
            document.getElementById('sidebarNav').addEventListener('click', function (e) {
                /* Si el click fue en un nav-link → no hacer nada con el acordeón */
                if (e.target.closest('a.nav-link')) return;

                var btn = e.target.closest('button.menu-header');
                if (!btn) return;

                var panelId = getPanelId(btn);
                var panel = document.getElementById(panelId);
                if (!panel) return;

                var isOpen = panel.classList.contains('open');
                closeAll();
                if (!isOpen) {
                    openPanel(panelId);
                    localStorage.setItem(STORE_KEY, panelId);
                } else {
                    localStorage.removeItem(STORE_KEY);
                }
            });

            /* ── Al cargar: detectar página activa ───── */
            document.addEventListener('DOMContentLoaded', function () {
                var currentPath = window.location.pathname;
                var activePanel = null;

                document.querySelectorAll('.menu-links a.nav-link').forEach(function (link) {
                    var href = link.getAttribute('href');
                    if (!href || href === '#') return;

                    /* Comparación exacta de pathname para evitar falsos positivos */
                    try {
                        var linkPath = new URL(href, window.location.origin).pathname;
                        /* Coincidencia exacta O sub-ruta directa (ej: /rrhh/alumnos/123) */
                        if (currentPath === linkPath ||
                            (currentPath.startsWith(linkPath + '/') && linkPath !== '/')) {
                            link.classList.add('active');
                            var parentPanel = link.closest('.menu-links');
                            if (parentPanel && !activePanel) activePanel = parentPanel.id;
                        }
                    } catch (err) { /* URL inválida, ignorar */ }
                });

                /* Prioridad: página activa > último guardado */
                if (activePanel) {
                    openPanel(activePanel);
                    localStorage.setItem(STORE_KEY, activePanel);
                } else {
                    var saved = localStorage.getItem(STORE_KEY);
                    if (saved) openPanel(saved);
                }
            });
        })();
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