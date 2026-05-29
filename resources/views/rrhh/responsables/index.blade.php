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

    {{-- ── Pestañas ─────────────────────────────────────────────────────── --}}
    <div class="card card-body p-2 shadow-sm">
        <div class="card-header bg-white py-1 border-bottom px-0">
            <ul class="nav nav-tabs card-header-tabs border-bottom-0" id="responsablesTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active py-1 px-2 fw-bold text-secondary" id="tab-lista-btn"
                        data-bs-toggle="tab" data-bs-target="#tab-lista" type="button" role="tab"
                        style="font-size: 0.75rem;">
                        <i class="fas fa-list text-primary me-1"></i> Listado de {{ ucfirst($tipo) }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-1 px-2 fw-bold text-secondary" id="tab-entrevistas-btn"
                        data-bs-toggle="tab" data-bs-target="#tab-entrevistas" type="button" role="tab"
                        style="font-size: 0.75rem;">
                        <i class="fas fa-comments text-success me-1"></i> Entrevistas
                        <span class="badge bg-success ms-1" style="font-size:0.6rem;">{{ $entrevistas->count() }}</span>
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-2">
            <div class="tab-content" id="responsablesTabContent">

                {{-- ══════════════════════ TAB 1: LISTADO ══════════════════════ --}}
                <div class="tab-pane fade show active" id="tab-lista" role="tabpanel">
                    <div class="d-flex justify-content-between mb-2 mt-1">
                        <h6 class="fw-bold text-secondary mb-0" style="font-size:0.8rem;"></h6>
                        <button class="btn btn-primary btn-sm" style="font-size: 0.7rem;" data-bs-toggle="modal"
                            data-bs-target="#modalCrear">
                            <i class="fas fa-plus me-1"></i> Nuevo Registro
                        </button>
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
                                            style="font-size: 0.65rem;" data-id="{{ $reg->id }}"
                                            data-nombre="{{ $reg->nombre }}" data-cid="{{ $reg->cid }}"
                                            data-profesion="{{ $reg->profesion }}" data-direccion="{{ $reg->direccion }}"
                                            data-barrio="{{ $reg->barrio }}" data-ciudad="{{ $reg->ciudad_id }}"
                                            data-tel1="{{ $reg->telefono1 }}" data-tel2="{{ $reg->telefono2 }}"
                                            data-email="{{ $reg->email }}" data-trabajo="{{ $reg->lugartrabajo }}"
                                            data-ruc="{{ $reg->ruc }}" data-dv="{{ $reg->dv }}">
                                            Editar
                                        </button>

                                        <form action="{{ route('responsables.destroy', [$tipo, $reg->id]) }}" method="POST"
                                            class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-xs py-0 px-1"
                                                style="font-size: 0.65rem;"
                                                onclick="return confirm('¿Eliminar registro?')">Borrar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- ══════════════════════ TAB 2: ENTREVISTAS ══════════════════════ --}}
                <div class="tab-pane fade" id="tab-entrevistas" role="tabpanel">
                    <div class="mt-1 mb-2">
                        <p class="mb-0 text-muted" style="font-size:0.75rem;">
                            <i class="fas fa-info-circle me-1 text-primary"></i>
                            Actas de entrevistas en las que participaron los/as <strong>{{ $tipo }}</strong> vinculados
                            al sistema.
                        </p>
                    </div>

                    <table id="tabla-entrevistas-resp" class="table table-sm table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th width="40" class="text-center">N°</th>
                                <th style="width:80px;" class="text-center">Fecha</th>
                                <th>Alumno Vinculado</th>
                                <th>Atendido por</th>
                                <th>Motivo</th>
                                <th width="60" class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($entrevistas as $ent)
                                <tr>
                                    <td class="text-center"></td>
                                    <td class="text-center" data-order="{{ $ent->fecha->format('Ymd') }}">
                                        {{ $ent->fecha->format('d/m/Y') }}
                                    </td>
                                    <td>{{ $ent->alumno ? $ent->alumno->apellidos . ', ' . $ent->alumno->nombres : '—' }}
                                    </td>
                                    <td>{{ $ent->entrevistador ? $ent->entrevistador->apellidos . ', ' . $ent->entrevistador->nombres : '—' }}
                                    </td>
                                    <td>{{ $ent->motivo }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-info btn-xs py-0 px-1 btn-ver-entrevista"
                                            style="font-size:0.65rem;" data-id="{{ $ent->id }}"
                                            data-fecha="{{ $ent->fecha->format('d/m/Y') }}"
                                            data-alumno="{{ $ent->alumno ? $ent->alumno->apellidos . ', ' . $ent->alumno->nombres : '—' }}"
                                            data-entrevistador="{{ $ent->entrevistador ? $ent->entrevistador->apellidos . ', ' . $ent->entrevistador->nombres : '—' }}"
                                            data-motivo="{{ $ent->motivo }}" data-obs="{{ $ent->observaciones }}"
                                            data-testigos="{{ $ent->testigos->map(fn($t) => $t->apellidos . ', ' . $t->nombres)->implode(' | ') }}"
                                            title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    {{-- ══════════════════════ MODAL VER ENTREVISTA ══════════════════════ --}}
    <div class="modal fade" id="modalVerEntrevista" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-2" style="background-color:#1e3a5f; color:#fff;">
                    <h6 class="modal-title fw-bold" style="font-size:0.85rem;">
                        <i class="fas fa-comments me-1"></i> Detalle de Entrevista / Acta
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3" style="font-size:0.8rem;">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label mb-0 fw-bold text-muted">Fecha</label>
                            <input type="text" id="ver_fecha" class="form-control form-control-sm bg-light" readonly>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label mb-0 fw-bold text-muted">Alumno Vinculado</label>
                            <input type="text" id="ver_alumno" class="form-control form-control-sm bg-light" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-0 fw-bold text-muted">Atendido por</label>
                            <input type="text" id="ver_entrevistador" class="form-control form-control-sm bg-light"
                                readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-0 fw-bold text-muted">Motivo Principal</label>
                            <input type="text" id="ver_motivo" class="form-control form-control-sm bg-light" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-0 fw-bold text-muted">Testigos / Miembros presentes</label>
                            <input type="text" id="ver_testigos" class="form-control form-control-sm bg-light" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-0 fw-bold text-muted">Resoluciones / Acuerdos</label>
                            <textarea id="ver_obs" class="form-control form-control-sm bg-light" rows="4"
                                readonly></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-1">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
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
        window.onload = function () {
            if (window.jQuery) {

                const langDt = {
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    paginate: { next: "›", previous: "‹" },
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    infoEmpty: "0 registros",
                    infoFiltered: "(filtrado de _MAX_)",
                    zeroRecords: "No se encontraron registros",
                    emptyTable: "No hay datos disponibles"
                };

                // ── DataTable Responsables ──
                $('#tabla-responsables').DataTable({
                    order: [[0, "asc"]],
                    pageLength: 15,
                    language: langDt,
                    dom: "<'row mb-2'<'col-sm-6'l><'col-sm-6'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row mt-2'<'col-sm-5'i><'col-sm-7'p>>"
                });

                // ── DataTable Entrevistas ──
                var tEnt = $('#tabla-entrevistas-resp').DataTable({
                    order: [[1, "desc"]],
                    pageLength: 15,
                    columnDefs: [
                        { searchable: false, orderable: false, targets: 0 }
                    ],
                    language: langDt,
                    dom: "<'row mb-2'<'col-sm-6'l><'col-sm-6'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row mt-2'<'col-sm-5'i><'col-sm-7'p>>"
                });

                // Numeración automática entrevistas
                tEnt.on('order.dt search.dt', function () {
                    let i = 1;
                    tEnt.cells(null, 0, { search: 'applied', order: 'applied' }).every(function () {
                        this.data(i++);
                    });
                }).draw();

                // ── Editar Responsable ──
                $(document).on('click', '.btn-editar', function (e) {
                    e.preventDefault();
                    var d = $(this).data();
                    $('#formEditar').attr('action', '/rrhh/responsables/{{ $tipo }}/' + d.id);
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
                    new bootstrap.Modal(document.getElementById('modalEditar')).show();
                });

                // ── Ver Detalle Entrevista ──
                $(document).on('click', '.btn-ver-entrevista', function () {
                    var d = $(this).data();
                    $('#ver_fecha').val(d.fecha);
                    $('#ver_alumno').val(d.alumno);
                    $('#ver_entrevistador').val(d.entrevistador);
                    $('#ver_motivo').val(d.motivo);
                    $('#ver_testigos').val(d.testigos || '—');
                    $('#ver_obs').val(d.obs || '—');
                    new bootstrap.Modal(document.getElementById('modalVerEntrevista')).show();
                });

            } else {
                alert("Error crítico: jQuery no se ha cargado. Revise app.blade.php");
            }
        };
    </script>

</x-app-layout>