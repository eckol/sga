<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Usuarios</h2>
    </x-slot>

    <div class="card card-body p-2 shadow-sm">
        <div class="d-flex justify-content-between mb-2">
            <h6 class="fw-bold text-secondary"></h6>
            <button class="btn btn-primary btn-sm" style="font-size: 0.7rem;" data-bs-toggle="modal"
                data-bs-target="#modalCrearUsuario">+ Nuevo Usuario</button>
        </div>

        <table id="tabla-usuarios" class="table table-sm table-hover table-bordered table-xs">
            <thead class="table-light">
                <tr>
                    <th width="50">ID</th>
                    <th>Nombre Completo</th>
                    <th>Email / Usuario</th>
                    <th>Rol Asignado</th>
                    <th width="150" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @php
                                $nombreRol = strtolower($user->rol->rol ?? '');
                                $colorBadge = match ($nombreRol) {
                                    'admin' => 'bg-danger',
                                    'directivo' => 'bg-warning text-dark',
                                    'responsable' => 'bg-success',
                                    'orientador' => 'bg-primary',
                                    'evaluador' => 'bg-violeta', //'bg-info text-dark'
                                    'docente' => 'bg-secondary',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $colorBadge }}" style="font-size: 0.65rem;">
                                {{ strtoupper($user->rol->rol ?? 'Sin Rol') }}
                            </span>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-primary btn-xs py-0 px-1 btn-editar"
                                style="font-size: 0.65rem;" data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                data-email="{{ $user->email }}" data-role="{{ $user->role_id }}">
                                Editar
                            </button>

                            <form action="{{ route('usuarios.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs py-0 px-1" style="font-size: 0.65rem;"
                                    onclick="return confirm('¿Eliminar acceso?')">Borrar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalCrearUsuario" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('usuarios.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header p-2 bg-primary text-white">
                    <h6 class="modal-title">Registrar Nuevo Usuario</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Nombre Completo</label>
                        <input type="text" name="name" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Contraseña</label>
                        <input type="password" name="password" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Rol</label>
                        <select name="role_id" class="form-select form-select-sm" required>
                            <option value="" disabled selected>Seleccione...</option>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->id }}">{{ strtoupper($rol->rol) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer p-1">
                    <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalEditarUsuario" tabindex="-1">
        <div class="modal-dialog">
            <form id="formEditar" method="POST" class="modal-content">
                @csrf @method('PATCH')
                <div class="modal-header p-2 bg-primary text-white">
                    <h6 class="modal-title">Modificar Usuario</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Nombre</label>
                        <input type="text" name="name" id="edit_name" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Rol</label>
                        <select name="role_id" id="edit_role_id" class="form-select form-select-sm" required>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->id }}">{{ strtoupper($rol->rol) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer p-1">
                    <button type="submit" class="btn btn-success btn-sm">Actualizar Cambios</button>
                </div>
            </form>
        </div>
    </div>
    <style>
        /* Forzar tamaño XS en selectores y sus opciones */
        .form-select-sm,
        .form-select-sm option {
            font-size: 0.75rem !important;
            padding-top: 0.2rem;
            padding-bottom: 0.2rem;
        }

        /* Forzar tamaño XS en elementos de DataTables */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            font-size: 0.75rem !important;
        }

        /* Redondear campos de búsqueda y selectores */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input,
        .form-select-sm,
        .form-control-sm {
            border-radius: 8px !important;
        }

        /* Botones de paginación más compactos */
        .page-link {
            padding: 0.25rem 0.5rem !important;
            font-size: 0.75rem !important;
        }

        /* Ajuste específico para que el select de registros no se vea recto */
        select[name="tabla-usuarios_length"] {
            border-radius: 5px !important;
        }

        /* Color naranja personalizado para el SGA */
        .bg-violeta {
            background-color: #5b01afff !important;
            color: white !important;
        }
    </style>
    <script>
        // Usamos una función que espera a que TODO el documento y las librerías estén cargadas
        window.onload = function () {
            if (window.jQuery) {
                console.log("SGA: jQuery cargado correctamente");

                // Inicializar DataTable
                var table = $('#tabla-usuarios').DataTable({
                    "order": [[0, "asc"]],
                    "pageLength": 10,
                    "language": {
                        "search": "Buscar:",
                        "lengthMenu": "Mostrar _MENU_ registros",
                        "paginate": { "next": "Siguiente", "previous": "Anterior" },
                        "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                        "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                        "infoFiltered": "(filtrado de _MAX_ registros)",
                        "zeroRecords": "No se encontraron registros",
                        "emptyTable": "No hay datos disponibles en la tabla"

                    },
                    // Estructura de la tabla: l=selector, f=filtro, t=tabla, i=info, p=paginación
                    "dom": "<'row mb-2'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
                });

                // ESCUCHADOR DE CLIC (Versión ultra-compatible)
                $(document).on('click', '.btn-editar', function (e) {
                    e.preventDefault();
                    console.log("SGA: Clic detectado en botón editar");

                    var id = $(this).data('id');
                    var name = $(this).data('name');
                    var email = $(this).data('email');
                    var role = $(this).data('role');

                    // Llenar campos
                    $('#formEditar').attr('action', '/usuarios/' + id);
                    $('#edit_name').val(name);
                    $('#edit_email').val(email);
                    $('#edit_role_id').val(role);

                    // Forzar apertura del modal
                    var myModal = new bootstrap.Modal(document.getElementById('modalEditarUsuario'));
                    myModal.show();
                });
            } else {
                alert("Error crítico: jQuery no se ha cargado. Revise app.blade.php");
            }
        };
    </script>
</x-app-layout>