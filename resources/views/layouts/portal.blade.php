<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SGA-CST') }} - Portal Familias</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        /* Barra Superior Minimalista */
        .portal-header {
            background-color: #ffffff;
            border-bottom: 1px solid #e3e6f0;
            padding: 0.5rem 1.5rem;
        }

        .portal-brand {
            font-size: 1rem;
            font-weight: 700;
            color: #4e73df;
            text-decoration: none;
        }

        .user-info {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .content-wrapper {
            padding: 20px;
        }
    </style>
</head>

<body>

    <header class="portal-header d-flex align-items-center justify-content-between shadow-sm">
        <a href="#" class="portal-brand d-flex align-items-center">
            <i class="fas fa-school me-2"></i>
            <span>SGA - Colegio Santa Teresita</span>
        </a>

        <div class="d-flex align-items-center gap-3">
            <div class="user-info d-none d-sm-block text-end">
                <span class="fw-bold text-dark d-block">{{ Auth::user()->name }}</span>
                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.65rem;">Responsable</span>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger px-2 py-1" style="font-size: 0.75rem;"
                    title="Cerrar Sesión">
                    <i class="fas fa-sign-out-alt me-1"></i>Salir
                </button>
            </form>
        </div>
    </header>

    <main class="content-wrapper">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
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

    @yield('scripts')
</body>

</html>