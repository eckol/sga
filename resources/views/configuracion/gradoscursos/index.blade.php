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
        select[name="tabla-gradoscursos_length"] {
            border-radius: 5px !important;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Grados y Cursos</h2>
    </x-slot>

    <div class="card card-body p-2 shadow-sm">
        <div class="d-flex justify-content-between mb-2">
            <h6 class="fw-bold text-secondary"></h6>
            <button class="btn btn-primary btn-sm" style="font-size: 0.7rem;" data-bs-toggle="modal"
                data-bs-target="#modalCrear">+ Nuevo Grado/Curso</button>
        </div>

        <table id="tabla-gradoscursos" class="table table-sm table-hover table-bordered table-xs">
            <thead class="table-light">
                <tr>
                    <th width="50">ID</th>
                    <th>Grado / Curso</th>
                    <th>Turno</th>
                    <th>Ciclo</th>
                    <th width="150" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gradoscursos as $gc)
                    <tr>
                        <td>{{ $gc->id }}</td>
                        <td>{{ $gc->gradocurso }}</td>
                        <td>{{ $gc->turno == 'M' ? 'Mañana' : 'Tarde' }}</td>
                        <td>{{ $gc->ciclo->ciclo }}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-primary btn-xs py-0 px-1 btn-editar-gc"
                                style="font-size: 0.65rem;" data-id="{{ $gc->id }}" data-gradocurso="{{ $gc->gradocurso }}"
                                data-turno="{{ $gc->turno }}" data-ciclo="{{ $gc->ciclo_id }}">
                                Editar
                            </button>

                            <form action="{{ route('gradoscursos.destroy', $gc->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs py-0 px-1" style="font-size: 0.65rem;"
                                    onclick="return confirm('¿Borrar?')">Borrar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Modal Crear --}}
    <div class="modal fade" id="modalCrear" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <form action="{{ route('gradoscursos.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header p-2 bg-primary text-white">
                    <h6 class="modal-title">Nuevo Grado/Curso</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-2">
                    <label class="form-label mb-0 fw-bold">Nombre del Grado/Curso</label>
                    <input type="text" name="gradocurso" class="form-control form-control-sm mb-2"
                        placeholder="Ej: 1ro Básico" required>

                    <label class="form-label mb-0 fw-bold">Turno</label>
                    <select name="turno" class="form-select form-select-sm mb-2" required>
                        <option value="M">Mañana</option>
                        <option value="T">Tarde</option>
                    </select>

                    <label class="form-label mb-0 fw-bold">Ciclo</label>
                    <select name="ciclo_id" class="form-select form-select-sm" required>
                        @foreach($ciclos as $c)
                            <option value="{{ $c->id }}">{{ $c->ciclo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer p-1">
                    <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Editar --}}
    <div class="modal fade" id="modalEditar" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <form id="formEditarGC" method="POST" class="modal-content">
                @csrf @method('PUT')
                <div class="modal-header p-2 bg-primary text-white">
                    <h6 class="modal-title">Modificar Grado/Curso</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-2">
                    <label class="form-label mb-0 fw-bold">Nombre del Grado/Curso</label>
                    <input type="text" name="gradocurso" id="edit_gradocurso" class="form-control form-control-sm mb-2"
                        required>

                    <label class="form-label mb-0 fw-bold">Turno</label>
                    <select name="turno" id="edit_turno" class="form-select form-select-sm mb-2" required>
                        <option value="M">Mañana</option>
                        <option value="T">Tarde</option>
                    </select>

                    <label class="form-label mb-0 fw-bold">Ciclo</label>
                    <select name="ciclo_id" id="edit_ciclo" class="form-select form-select-sm" required>
                        @foreach($ciclos as $c)
                            <option value="{{ $c->id }}">{{ $c->ciclo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer p-1">
                    <button type="submit" class="btn btn-success btn-sm">Actualizar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.onload = function () {
            if (window.jQuery) {

                // Inicializar DataTable
                $('#tabla-gradoscursos').DataTable({
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
                    "dom": "<'row mb-2'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
                });

                // Listener para abrir modal de edición
                $(document).on('click', '.btn-editar-gc', function () {
                    var id = $(this).data('id');
                    var gradocurso = $(this).data('gradocurso');
                    var turno = $(this).data('turno');
                    var ciclo = $(this).data('ciclo');

                    $('#formEditarGC').attr('action', '/gradoscursos/' + id);
                    $('#edit_gradocurso').val(gradocurso);
                    $('#edit_turno').val(turno);
                    $('#edit_ciclo').val(ciclo);

                    var myModal = new bootstrap.Modal(document.getElementById('modalEditar'));
                    myModal.show();
                });

            } else {
                alert("Error crítico: jQuery no se ha cargado. Revise app.blade.php");
            }
        };
    </script>

</x-app-layout>