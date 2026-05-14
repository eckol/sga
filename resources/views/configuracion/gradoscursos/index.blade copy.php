<x-app-layout>
    <style>
        .form-select-sm,
        .form-select-sm option {
            font-size: 0.75rem !important;
            padding: 0.2rem;
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            font-size: 0.75rem !important;
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input,
        .form-select-sm,
        .form-control-sm {
            border-radius: 8px !important;
        }

        .page-link {
            padding: 0.25rem 0.5rem !important;
            font-size: 0.75rem !important;
        }

        select[name="tabla-gradoscursos_length"] {
            border-radius: 5px !important;
        }
    </style>

    <div class="py-3">
        <div class="card shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center"
                style="border-radius: 15px 15px 0 0;">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-graduation-cap me-2"></i>Gestión de
                    Grados y Cursos</h6>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrear"
                    style="border-radius: 8px;">
                    <i class="fas fa-plus-circle me-1"></i> Nuevo Registro
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabla-gradoscursos" class="table table-hover table-sm w-100">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">Grado / Curso</th>
                                <th>Turno</th>
                                <th>Ciclo</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gradoscursos as $gc)
                                <tr>
                                    <td class="ps-3 align-middle">{{ $gc->gradocurso }}</td>
                                    <td class="align-middle">{{ $gc->turno == 'M' ? 'Mañana' : 'Tarde' }}</td>
                                    <td class="align-middle">{{ $gc->ciclo->ciclo }}</td>
                                    <td class="text-center align-middle">
                                        <div class="btn-group">
                                            <button class="btn btn-outline-primary btn-sm btn-editar-gc"
                                                data-id="{{ $gc->id }}" data-gradocurso="{{ $gc->gradocurso }}"
                                                data-turno="{{ $gc->turno }}" data-ciclo="{{ $gc->ciclo_id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('gradoscursos.destroy', $gc->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('¿Eliminar?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 15px;">
                <form action="{{ route('gradoscursos.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Nuevo Grado/Curso</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre del Grado/Curso</label>
                            <input type="text" name="gradocurso" class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Turno</label>
                            <select name="turno" class="form-select form-select-sm" required>
                                <option value="M">Mañana</option>
                                <option value="T">Tarde</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ciclo</label>
                            <select name="ciclo_id" class="form-select form-select-sm" required>
                                @foreach($ciclos as $c)
                                    <option value="{{ $c->id }}">{{ $c->ciclo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 15px;">
                <form id="formEditarGC" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Grado/Curso</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre del Grado/Curso</label>
                            <input type="text" name="gradocurso" id="edit_gradocurso"
                                class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Turno</label>
                            <select name="turno" id="edit_turno" class="form-select form-select-sm" required>
                                <option value="M">Mañana</option>
                                <option value="T">Tarde</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ciclo</label>
                            <select name="ciclo_id" id="edit_ciclo" class="form-select form-select-sm" required>
                                @foreach($ciclos as $c)
                                    <option value="{{ $c->id }}">{{ $c->ciclo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#tabla-gradoscursos').DataTable({
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

                $(document).on('click', '.btn-editar-gc', function () {
                    var d = $(this).data();
                    $('#formEditarGC').attr('action', '/gradoscursos/' + d.id);
                    $('#edit_gradocurso').val(d.gradocurso);
                    $('#edit_turno').val(d.turno);
                    $('#edit_ciclo').val(d.ciclo);
                    var myModal = new bootstrap.Modal(document.getElementById('modalEditar'));
                    myModal.show();
                });
            });
        </script>
    @endpush
</x-app-layout>