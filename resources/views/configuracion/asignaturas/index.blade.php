<x-app-layout>

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
        select[name="tabla-asignaturas_length"] {
            border-radius: 5px !important;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Asignaturas</h2>
    </x-slot>

    <div class="card card-body p-2">
        <div class="d-flex justify-content-between mb-2">
            <h6 class="fw-bold"></h6>
            <button class="btn btn-primary btn-sm" style="font-size: 0.7rem;" data-bs-toggle="modal"
                data-bs-target="#modalCrear">+ Nueva Asignatura</button>
        </div>

        <table id="tabla-asignaturas" class="table table-sm table-hover table-bordered table-xs">
            <thead class="table-light">
                <tr>
                    <th width="50">ID</th>
                    <th>Asignatura</th>
                    <th>Abreviación</th>
                    <th width="150" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($asignaturas as $asignatura)
                    <tr>
                        <td>{{ $asignatura->id }}</td>
                        <td>{{ $asignatura->asignatura }}</td>
                        <td>{{ $asignatura->abreviacion }}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-warning btn-xs py-0 px-1 btn-editar"
                                style="font-size: 0.65rem;" data-id="{{ $asignatura->id }}"
                                data-asignatura="{{ $asignatura->asignatura }}"
                                data-abreviacion="{{ $asignatura->abreviacion }}">
                                <i class="fas fa-edit"></i>
                            </button>

                            <form action="{{ route('asignaturas.destroy', $asignatura->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs py-0 px-1" style="font-size: 0.65rem;"
                                    onclick="return confirm('¿Borrar?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalCrear" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <form action="{{ route('asignaturas.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header p-2 bg-primary text-white">
                    <h6 class="modal-title">Nueva Asignatura</h6>
                </div>
                <div class="modal-body p-2">
                    <label class="form-label mb-0 fw-bold">Asignatura</label>
                    <input type="text" name="asignatura" class="form-control form-control-sm"
                        placeholder="Nombre de la asignatura" required>
                    <label class="form-label mb-0 fw-bold">Abreviación</label>
                    <input type="text" name="abreviacion" class="form-control form-control-sm" placeholder="Abreviación"
                        required>
                </div>
                <div class="modal-footer p-1">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalEditarAsignatura" tabindex="-1">
        <div class="modal-dialog">
            <form id="formEditar" method="POST" class="modal-content">
                @csrf @method('PATCH')
                <div class="modal-header p-2 bg-primary text-white">
                    <h6 class="modal-title">Modificar Asignatura</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Asignatura</label>
                        <input type="text" name="asignatura" id="edit_asignatura" class="form-control form-control-sm"
                            required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Abreviación</label>
                        <input type="text" name="abreviacion" id="edit_abreviacion" class="form-control form-control-sm"
                            required>
                    </div>
                </div>
                <div class="modal-footer p-1">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-sm">Actualizar Cambios</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        // Usamos una función que espera a que TODO el documento y las librerías estén cargadas
        window.onload = function () {
            if (window.jQuery) {
                console.log("SGA: jQuery cargado correctamente");

                // Inicializar DataTable
                var table = $('#tabla-asignaturas').DataTable({
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
                    var asignatura = $(this).data('asignatura');
                    var abreviacion = $(this).data('abreviacion');

                    // Llenar campos
                    $('#formEditar').attr('action', '/asignaturas/' + id);
                    $('#edit_asignatura').val(asignatura);
                    $('#edit_abreviacion').val(abreviacion);

                    // Forzar apertura del modal
                    var myModal = new bootstrap.Modal(document.getElementById('modalEditarAsignatura'));
                    myModal.show();
                });
            } else {
                alert("Error crítico: jQuery no se ha cargado. Revise app.blade.php");
            }
        };
    </script>


</x-app-layout>