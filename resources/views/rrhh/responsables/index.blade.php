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

        select[name="tabla-responsables_length"] {
            border-radius: 5px !important;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de {{ ucfirst($tipo) }}</h2>
    </x-slot>

    <div class="card card-body p-2 shadow-sm">
        <div class="d-flex justify-content-between mb-2">
            <h6 class="fw-bold text-secondary"></h6>
            <button class="btn btn-primary btn-sm" style="font-size: 0.7rem;" data-bs-toggle="modal"
                data-bs-target="#modalCrear">+ Nuevo Registro</button>
        </div>

        <table id="tabla-responsables" class="table table-sm table-hover table-bordered table-xs">
            <thead class="table-light">
                <tr>
                    <th width="50">ID</th>
                    <th>Nombre Completo</th>
                    <th>C.I.D.</th>
                    <th>Teléfono 1</th>
                    <th>Teléfono 2</th>
                    <th>Email</th>
                    <th width="100" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registros as $reg)
                    <tr>
                        <td>{{ $reg->id }}</td>
                        <td>{{ $reg->nombre }}</td>
                        <td>{{ number_format($reg->cid, 0, ',', '.') }}</td>
                        <td>{{ $reg->telefono1 }}</td>
                        <td>{{ $reg->telefono2 ?? '-' }}</td>
                        <td>{{ $reg->email ?? 'N/A' }}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-primary btn-xs py-0 px-1 btn-editar"
                                style="font-size: 0.65rem;" data-id="{{ $reg->id }}" data-nombre="{{ $reg->nombre }}"
                                data-cid="{{ $reg->cid }}" data-profesion="{{ $reg->profesion }}"
                                data-direccion="{{ $reg->direccion }}" data-barrio="{{ $reg->barrio }}"
                                data-ciudad="{{ $reg->ciudad_id }}" data-tel1="{{ $reg->telefono1 }}"
                                data-tel2="{{ $reg->telefono2 }}" data-email="{{ $reg->email }}"
                                data-trabajo="{{ $reg->lugartrabajo }}" data-ruc="{{ $reg->ruc }}" data-dv="{{ $reg->dv }}">
                                Editar
                            </button>

                            <form action="{{ route('responsables.destroy', [$tipo, $reg->id]) }}" method="POST"
                                class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs py-0 px-1" style="font-size: 0.65rem;"
                                    onclick="return confirm('¿Eliminar registro?')">Borrar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ======================== MODAL CREAR ======================== --}}
    <div class="modal fade" id="modalCrear" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('responsables.store', $tipo) }}" method="POST" class="modal-content">
                @csrf
                <input type="hidden" name="rol_id" value="{{ $rol_id }}">
                <div class="modal-header p-2 bg-primary text-white">
                    <h6 class="modal-title">Registrar Nuevo: {{ ucfirst($tipo) }}</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3" style="font-size: 0.8rem;">
                    <div class="row g-2">
                        <div class="col-md-8">
                            <label class="form-label mb-0 fw-bold">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-0 fw-bold">C.I.D. (Solo números)</label>
                            <input type="number" name="cid" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-0 fw-bold">Email</label>
                            <input type="email" name="email" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-0 fw-bold">Teléfono 1</label>
                            <input type="text" name="telefono1" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-0 fw-bold">Teléfono 2</label>
                            <input type="text" name="telefono2" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label mb-0 fw-bold">Dirección</label>
                            <input type="text" name="direccion" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-0 fw-bold">Barrio</label>
                            <input type="text" name="barrio" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-0 fw-bold">Ciudad</label>
                            <select name="ciudad_id" class="form-select form-select-sm" required>
                                <option value="" disabled selected>Seleccione...</option>
                                @foreach($ciudades as $ciudad)
                                    <option value="{{ $ciudad->id }}">{{ $ciudad->ciudad }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-0 fw-bold">Profesión</label>
                            <input type="text" name="profesion" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-0 fw-bold">Lugar de Trabajo</label>
                            <input type="text" name="lugartrabajo" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-0 fw-bold">RUC</label>
                            <input type="text" name="ruc" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label mb-0 fw-bold">DV</label>
                            <input type="text" name="dv" maxlength="1" class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-1">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================== MODAL EDITAR ======================== --}}
    <div class="modal fade" id="modalEditar" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form id="formEditar" method="POST" class="modal-content">
                @csrf @method('PUT')
                <div class="modal-header p-2 bg-primary text-white">
                    <h6 class="modal-title">Modificar: {{ ucfirst(Str::singular($tipo)) }}</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3" style="font-size: 0.8rem;">
                    <div class="row g-2">
                        <div class="col-md-8">
                            <label class="form-label mb-0 fw-bold">Nombre Completo</label>
                            <input type="text" name="nombre" id="edit_nombre" class="form-control form-control-sm"
                                required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-0 fw-bold">C.I.D.</label>
                            <input type="number" name="cid" id="edit_cid" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-0 fw-bold">Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-0 fw-bold">Teléfono 1</label>
                            <input type="text" name="telefono1" id="edit_telefono1" class="form-control form-control-sm"
                                required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-0 fw-bold">Teléfono 2</label>
                            <input type="text" name="telefono2" id="edit_telefono2"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label mb-0 fw-bold">Dirección</label>
                            <input type="text" name="direccion" id="edit_direccion" class="form-control form-control-sm"
                                required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-0 fw-bold">Barrio</label>
                            <input type="text" name="barrio" id="edit_barrio" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-0 fw-bold">Ciudad</label>
                            <select name="ciudad_id" id="edit_ciudad_id" class="form-select form-select-sm" required>
                                @foreach($ciudades as $ciudad)
                                    <option value="{{ $ciudad->id }}">{{ $ciudad->ciudad }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-0 fw-bold">Profesión</label>
                            <input type="text" name="profesion" id="edit_profesion"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-0 fw-bold">Lugar de Trabajo</label>
                            <input type="text" name="lugartrabajo" id="edit_lugartrabajo"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-0 fw-bold">RUC</label>
                            <input type="text" name="ruc" id="edit_ruc" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label mb-0 fw-bold">DV</label>
                            <input type="text" name="dv" id="edit_dv" maxlength="1"
                                class="form-control form-control-sm">
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

    {{-- ======================== JAVASCRIPT ======================== --}}
    <script>
        // Igual que en usuarios: esperamos a que jQuery y Bootstrap estén listos
        window.onload = function () {
            if (window.jQuery) {

                // Inicializar DataTable
                $('#tabla-responsables').DataTable({
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

                // Escuchador para el botón Editar
                $(document).on('click', '.btn-editar', function (e) {
                    e.preventDefault();
                    var d = $(this).data();

                    // Armar la URL de acción con el tipo Blade y el ID del registro
                    $('#formEditar').attr('action', '/rrhh/responsables/{{ $tipo }}/' + d.id);

                    // Rellenar los campos del modal
                    $('#edit_nombre').val(d.nombre);
                    $('#edit_cid').val(d.cid);
                    $('#edit_profesion').val(d.profesion);
                    $('#edit_direccion').val(d.direccion);
                    $('#edit_barrio').val(d.barrio);
                    $('#edit_ciudad_id').val(d.ciudad);
                    $('#edit_telefono1').val(d.tel1);
                    $('#edit_telefono2').val(d.tel2);
                    $('#edit_email').val(d.email);
                    $('#edit_lugartrabajo').val(d.trabajo);
                    $('#edit_ruc').val(d.ruc);
                    $('#edit_dv').val(d.dv);

                    // Abrir el modal
                    var myModal = new bootstrap.Modal(document.getElementById('modalEditar'));
                    myModal.show();
                });

            } else {
                alert("Error crítico: jQuery no se ha cargado. Revise app.blade.php");
            }
        };
    </script>

</x-app-layout>