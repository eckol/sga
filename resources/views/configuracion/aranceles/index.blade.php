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
        select[name="tabla-aranceles_length"] {
            border-radius: 5px !important;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Aranceles</h2>
    </x-slot>

    <div class="card card-body p-2 shadow-sm">
        <div class="d-flex justify-content-between mb-2">
            <h6 class="fw-bold text-secondary"></h6>
            <button class="btn btn-primary btn-sm" style="font-size: 0.7rem;" data-bs-toggle="modal"
                data-bs-target="#modalCrearArancel">+ Nuevo Arancel</button>
        </div>

        <table id="tabla-aranceles" class="table table-sm table-hover table-bordered table-xs">
            <thead class="table-light">
                <tr>
                    <th width="50">ID</th>
                    <th>Año Lectivo</th>
                    <th>Ciclo</th>
                    <th>Matrícula</th>
                    <th>Anualidad</th>
                    <th width="150" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($aranceles as $arancel)
                    <tr>
                        <td>{{ $arancel->id }}</td>
                        <td>{{ $arancel->anio_lect }}</td>
                        <td>{{ $arancel->ciclo->ciclo }}</td>
                        <td>Gs. {{ number_format($arancel->monto_matricula, 0, ',', '.') }}</td>
                        <td>Gs. {{ number_format($arancel->monto_anualidad, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-warning btn-xs py-0 px-1 btn-editar"
                                style="font-size: 0.65rem;" data-id="{{ $arancel->id }}"
                                data-anio_lect="{{ $arancel->anio_lect }}" data-ciclo_id="{{ $arancel->ciclo_id }}"
                                data-monto_matricula="{{ $arancel->monto_matricula }}"
                                data-monto_anualidad="{{ $arancel->monto_anualidad }}">
                                <i class="fas fa-edit"></i>
                            </button>

                            <form action="{{ route('aranceles.destroy', $arancel->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs py-0 px-1" style="font-size: 0.65rem;"
                                    onclick="return confirm('¿Eliminar Registro?')"><i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Modal Crear --}}
    <div class="modal fade" id="modalCrearArancel" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('aranceles.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header p-2 bg-primary text-white">
                    <h6 class="modal-title">Registrar Nuevo Arancel</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Año Lectivo</label>
                        <input type="number" name="anio_lect" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Ciclo</label>
                        <select name="ciclo_id" class="form-select form-select-sm" required>
                            <option value="" disabled selected>Seleccione...</option>
                            @foreach($ciclos as $c)
                                <option value="{{ $c->id }}">{{ $c->ciclo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Monto Matrícula</label>
                        <input type="number" name="monto_matricula" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Monto Anualidad</label>
                        <input type="number" name="monto_anualidad" class="form-control form-control-sm" required>
                    </div>
                </div>
                <div class="modal-footer p-1">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Editar --}}
    <div class="modal fade" id="modalEditarArancel" tabindex="-1">
        <div class="modal-dialog">
            <form id="formEditar" method="POST" class="modal-content">
                @csrf @method('PATCH')
                <div class="modal-header p-2 bg-primary text-white">
                    <h6 class="modal-title">Modificar Arancel</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Año Lectivo</label>
                        <input type="number" name="anio_lect" id="edit_anio_lect" class="form-control form-control-sm"
                            required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Ciclo</label>
                        <select name="ciclo_id" id="edit_ciclo_id" class="form-select form-select-sm" required>
                            @foreach($ciclos as $c)
                                <option value="{{ $c->id }}">{{ $c->ciclo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Monto Matrícula</label>
                        <input type="number" name="monto_matricula" id="edit_monto_matricula"
                            class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Monto Anualidad</label>
                        <input type="number" name="monto_anualidad" id="edit_monto_anualidad"
                            class="form-control form-control-sm" required>
                    </div>
                </div>
                <div class="modal-footer p-1">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-save me-1"></i>Actualizar
                        Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.onload = function () {
            if (window.jQuery) {
                console.log("SGA: jQuery cargado correctamente");

                // Inicializar DataTable — apunta a #tabla-aranceles
                var table = $('#tabla-aranceles').DataTable({
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

                // Escuchador clic botón editar
                $(document).on('click', '.btn-editar', function (e) {
                    e.preventDefault();
                    console.log("SGA: Clic detectado en botón editar");

                    var id = $(this).data('id');
                    var anio_lect = $(this).data('anio_lect');
                    var ciclo_id = $(this).data('ciclo_id');
                    var monto_matricula = $(this).data('monto_matricula');
                    var monto_anualidad = $(this).data('monto_anualidad');

                    // Llenar campos del modal
                    $('#formEditar').attr('action', '/aranceles/' + id);
                    $('#edit_anio_lect').val(anio_lect);
                    $('#edit_ciclo_id').val(ciclo_id);
                    $('#edit_monto_matricula').val(monto_matricula);
                    $('#edit_monto_anualidad').val(monto_anualidad);

                    // Abrir modal
                    var myModal = new bootstrap.Modal(document.getElementById('modalEditarArancel'));
                    myModal.show();
                });

            } else {
                alert("Error crítico: jQuery no se ha cargado. Revise app.blade.php");
            }
        };
    </script>

</x-app-layout>