<x-app-layout>
    <style>
        .form-select-sm,
        .form-select-sm option {
            font-size: 0.75rem !important;
            padding-top: 0.2rem;
            padding-bottom: 0.2rem;
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
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Configuración de Módulos Horarios</h2>
    </x-slot>

    <div class="card card-body p-2 mt-3">
        <div class="d-flex justify-content-between mb-2">
            <h6 class="fw-bold">Lista de Horas/Módulos</h6>
            <button class="btn btn-primary btn-sm" style="font-size: 0.7rem;" data-bs-toggle="modal"
                data-bs-target="#modalCrear">+ Nuevo Módulo</button>
        </div>

        <table id="tabla-horas" class="table table-sm table-hover table-bordered table-xs">
            <thead class="table-light">
                <tr>
                    <th width="50">ID</th>
                    <th>Módulo</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th width="150" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($horas as $hora)
                    <tr>
                        <td>{{ $hora->id }}</td>
                        <td>{{ $hora->modulo }}</td>
                        <td>{{ substr($hora->hora_inicio, 0, 5) }}</td>
                        <td>{{ substr($hora->hora_fin, 0, 5) }}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-primary btn-xs py-0 px-1 btn-editar"
                                style="font-size: 0.65rem;" data-id="{{ $hora->id }}" data-modulo="{{ $hora->modulo }}"
                                data-inicio="{{ substr($hora->hora_inicio, 0, 5) }}"
                                data-fin="{{ substr($hora->hora_fin, 0, 5) }}">
                                Editar
                            </button>

                            <form action="{{ route('horas.destroy', $hora->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs py-0 px-1" style="font-size: 0.65rem;"
                                    onclick="return confirm('¿Borrar este módulo?')">Borrar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalCrear" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <form action="{{ route('horas.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header p-2 bg-primary text-white">
                    <h6 class="modal-title">Nuevo Módulo</h6>
                </div>
                <div class="modal-body p-2">
                    <input type="text" name="modulo" class="form-control form-control-sm mb-2" placeholder="Ej: 1ra."
                        required>
                    <label class="small fw-bold">Hora Inicio:</label>
                    <input type="time" name="hora_inicio" class="form-control form-control-sm mb-2" required>
                    <label class="small fw-bold">Hora Fin:</label>
                    <input type="time" name="hora_fin" class="form-control form-control-sm" required>
                </div>
                <div class="modal-footer p-1">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalEditar" tabindex="-1">
        <div class="modal-dialog">
            <form id="formEditar" method="POST" class="modal-content">
                @csrf @method('PATCH')
                <div class="modal-header p-2 bg-primary text-white">
                    <h6 class="modal-title">Modificar Módulo</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Nombre del Módulo</label>
                        <input type="text" name="modulo" id="edit_modulo" class="form-control form-control-sm" required>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label mb-0 fw-bold">Inicio</label>
                            <input type="time" name="hora_inicio" id="edit_inicio" class="form-control form-control-sm"
                                required>
                        </div>
                        <div class="col-6">
                            <label class="form-label mb-0 fw-bold">Fin</label>
                            <input type="time" name="hora_fin" id="edit_fin" class="form-control form-control-sm"
                                required>
                        </div>
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
        window.onload = function () {
            if (window.jQuery) {
                $('#tabla-horas').DataTable({
                    "order": [[2, "asc"]], // Ordenar por hora de inicio
                    "language": {
                        "search": "Buscar:",
                        "lengthMenu": "Mostrar _MENU_",
                        "paginate": { "next": ">", "previous": "<" },
                        "info": "Mostrando _START_ a _END_ de _TOTAL_"
                    },
                    "dom": "<'row mb-2'<'col-sm-6'l><'col-sm-6'f>>t<'row mt-2'<'col-sm-5'i><'col-sm-7'p>>"
                });

                $(document).on('click', '.btn-editar', function (e) {
                    var id = $(this).data('id');
                    $('#formEditar').attr('action', '/horas/' + id);
                    $('#edit_modulo').val($(this).data('modulo'));
                    $('#edit_inicio').val($(this).data('inicio'));
                    $('#edit_fin').val($(this).data('fin'));

                    var myModal = new bootstrap.Modal(document.getElementById('modalEditar'));
                    myModal.show();
                });
            }
        };
    </script>
</x-app-layout>