<x-app-layout>

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

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

        .dt-buttons .btn {
            font-size: 0.75rem;
            margin-right: 5px;
        }

        /* Modal header estilo institucional */
        .modal-header-cst {
            background-color: #1e3a5f;
            color: #fff;
        }

        .modal-header-cst .btn-close {
            filter: invert(1);
        }

        /* Forzar tamaño de fuente en celdas de tabla */
        .table-xs th,
        .table-xs td {
            font-size: 0.72rem !important;
        }
    </style>

    <div class="container-fluid py-3">

        <div class="card mb-3 shadow-sm" style="border-radius: 10px;">
            <div class="card-body p-2">
                <div class="row g-2 align-items-center">
                    <div class="col-md-6">
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">
                            <i class="fas fa-comments-alt text-primary me-1"></i> Control de Entrevistas y Actas Institucionales
                        </h6>
                    </div>
                    <div class="col-md-6 text-end">
                        <button class="btn btn-primary btn-sm px-2 py-1 fw-bold" style="border-radius: 8px; font-size: 0.75rem;" data-bs-toggle="modal" data-bs-target="#modalCrearAlumno">
                            <i class="fas fa-user-plus me-1"></i> + Entrevista Alumno
                        </button>
                        <button class="btn btn-success btn-sm px-2 py-1 fw-bold" style="border-radius: 8px; font-size: 0.75rem;" data-bs-toggle="modal" data-bs-target="#modalCrearResponsable">
                            <i class="fas fa-users me-1"></i> + Acta Responsables
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm" style="border-radius: 10px;">
            <div class="card-header bg-white py-1 border-bottom">
                <ul class="nav nav-tabs card-header-tabs border-bottom-0" id="entrevistasTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active py-1 px-2 fw-bold text-secondary" id="alumnos-tab" data-bs-toggle="tab" data-bs-target="#alumnos-pane" type="button" role="tab" style="font-size: 0.75rem;">
                            <i class="fas fa-user-graduate text-info me-1"></i> Entrevistas a Alumnos
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-1 px-2 fw-bold text-secondary" id="responsables-tab" data-bs-toggle="tab" data-bs-target="#responsables-pane" type="button" role="tab" style="font-size: 0.75rem;">
                            <i class="fas fa-user-friends text-success me-1"></i> Actas con Padres / Encargados
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body p-2">
                <div class="tab-content" id="entrevistasTabContent">
                    
                    <div class="tab-pane fade show active" id="alumnos-pane" role="tabpanel">
                        <div class="table-responsive">
                            <table id="tabla-entrevistas-alumnos" class="table table-sm table-bordered table-striped table-hover table-xs align-middle" style="width:100%;">
                                <thead class="table-light text-secondary">
                                    <tr>
                                        <th class="text-center" style="width: 80px;">Fecha</th>
                                        <th>Estudiante / Alumno</th>
                                        <th>Entrevistador / Orientador</th>
                                        <th>Motivo Principal</th>
                                        <th>Observaciones y Acuerdos</th>
                                        <th class="text-center" style="width: 50px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($entrevistasAlumnos as $ea)
                                    <tr>
                                        <td class="text-center fw-bold text-dark">{{ $ea->fecha->format('d/m/Y') }}</td>
                                        <td class="fw-bold">{{ $ea->alumno->apellidos }}, {{ $ea->alumno->nombres }}</td>
                                        <td>{{ $ea->entrevistador->apellidos }}, {{ $ea->entrevistador->nombres }}</td>
                                        <td><span class="fw-semibold text-dark">{{ $ea->motivo }}</span></td>
                                        <td class="text-muted" style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $ea->observaciones }}">{{ $ea->observaciones ?? '—' }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-warning btn-sm py-0 px-1 btn-editar-alumno" 
                                                    data-id="{{ $ea->id }}" 
                                                    data-fecha="{{ $ea->fecha->format('Y-m-d') }}"
                                                    data-alumno-id="{{ $ea->alumno_id }}"
                                                    data-alumno-nombre="{{ $ea->alumno->apellidos }}, {{ $ea->alumno->nombres }}"
                                                    data-colaborador-id="{{ $ea->colaborador_id }}"
                                                    data-motivo="{{ $ea->motivo }}"
                                                    data-obs="{{ $ea->observaciones }}" title="Editar">
                                                <i class="fas fa-edit" style="font-size: 0.65rem;"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm py-0 px-1 btn-eliminar-alumno" 
                                                    data-id="{{ $ea->id }}" 
                                                    data-desc="Entrevista de {{ $ea->alumno->apellidos }} (Fecha: {{ $ea->fecha->format('d/m/Y') }})" title="Eliminar">
                                                <i class="fas fa-trash-alt" style="font-size: 0.65rem;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="responsables-pane" role="tabpanel">
                        <div class="table-responsive">
                            <table id="tabla-entrevistas-responsables" class="table table-sm table-bordered table-striped table-hover table-xs align-middle" style="width:100%;">
                                <thead class="table-light text-secondary">
                                    <tr>
                                        <th class="text-center" style="width: 80px;">Fecha</th>
                                        <th>Alumno Asociado</th>
                                        <th>Responsables Concurrentes</th>
                                        <th>Atendido por</th>
                                        <th>Motivo del Acta</th>
                                        <th>Testigos / Otros Presentes</th>
                                        <th class="text-center" style="width: 50px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($entrevistasResponsables as $er)
                                    <tr>
                                        <td class="text-center fw-bold text-dark">{{ $er->fecha->format('d/m/Y') }}</td>
                                        <td class="fw-bold text-primary">{{ $er->alumno->apellidos }}, {{ $er->alumno->nombres }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                @if($er->madre_cid)<span class="badge bg-light text-dark border p-1" style="font-size:0.58rem;"><i class="fas fa-female text-danger me-1"></i>Madre</span>@endif
                                                @if($er->padre_cid)<span class="badge bg-light text-dark border p-1" style="font-size:0.58rem;"><i class="fas fa-male text-primary me-1"></i>Padre</span>@endif
                                                @if($er->encargado_cid)<span class="badge bg-light text-dark border p-1" style="font-size:0.58rem;"><i class="fas fa-user-shield text-success me-1"></i>Encargado</span>@endif
                                            </div>
                                        </td>
                                        <td>{{ $er->entrevistador->apellidos }}, {{ $er->entrevistador->nombres }}</td>
                                        <td class="fw-semibold">{{ $er->motivo }}</td>
                                        <td>
                                            @forelse($er->testigos as $testigo)
                                                <span class="badge bg-secondary py-1 px-1" style="font-size: 0.58rem; font-weight: normal;">{{ $testigo->nombres }} {{ $testigo->apellidos }}</span>
                                            @empty
                                                <span class="text-muted italic" style="font-size: 0.65rem;">Sin testigos</span>
                                            @endforelse
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-warning btn-sm py-0 px-1 btn-editar-resp" 
                                                    data-id="{{ $er->id }}" 
                                                    data-fecha="{{ $er->fecha->format('Y-m-d') }}"
                                                    data-alumno-id="{{ $er->alumno_id }}"
                                                    data-alumno-nombre="{{ $er->alumno->apellidos }}, {{ $er->alumno->nombres }}"
                                                    data-colaborador-id="{{ $er->colaborador_id }}"
                                                    data-motivo="{{ $er->motivo }}"
                                                    data-obs="{{ $er->observaciones }}"
                                                    data-testigos="{{ json_encode($er->testigos->pluck('id')) }}" title="Editar">
                                                <i class="fas fa-edit" style="font-size: 0.65rem;"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm py-0 px-1 btn-eliminar-resp" 
                                                    data-id="{{ $er->id }}" 
                                                    data-desc="Acta de Responsables - Alumno: {{ $er->alumno->apellidos }}" title="Eliminar">
                                                <i class="fas fa-trash-alt" style="font-size: 0.65rem;"></i>
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
        </div>
    </div>

    <div class="modal fade" id="modalCrearAlumno" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('entrevistas.alumno.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header p-2 modal-header-cst">
                    <h6 class="modal-title" style="font-size: 0.85rem;"><i class="fas fa-user-plus me-1"></i> Registrar Nueva Entrevista Alumno</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-2">
                    <div class="row g-2" style="font-size: 0.75rem;">
                        <div class="col-md-4">
                            <label class="form-label mb-0 fw-bold">Fecha</label>
                            <input type="date" name="fecha" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label mb-0 fw-bold">Entrevistador / Colaborador</label>
                            <select name="colaborador_id" class="form-select form-select-sm" required>
                                <option value="">Seleccione...</option>
                                @foreach($colaboradores as $c)
                                    <option value="{{ $c->id }}">{{ $c->apellidos }}, {{ $c->nombres }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-0 fw-bold">Buscar Alumno / Estudiante</label>
                            <select name="alumno_id" class="form-select form-select-sm select2-modal" required>
                                <option value="">Escriba el nombre o apellido...</option>
                                @foreach($alumnos as $a)
                                    <option value="{{ $a->id }}">{{ $a->apellidos }}, {{ $a->nombres }} (CID: {{ $a->cid }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-0 fw-bold">Motivo Principal</label>
                            <input type="text" name="motivo" class="form-control form-control-sm" placeholder="Ej: Citación de orientación por conducta" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-0 fw-bold">Observaciones / Minuta</label>
                            <textarea name="observaciones" class="form-control form-control-sm" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-1">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm">Guardar Registro</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalCrearResponsable" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('entrevistas.responsable.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header p-2 modal-header-cst" style="background-color: #198754;">
                    <h6 class="modal-title" style="font-size: 0.85rem;"><i class="fas fa-users me-1"></i> Levantar Acta con Responsables</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-2">
                    <div class="row g-2" style="font-size: 0.75rem;">
                        <div class="col-md-4">
                            <label class="form-label mb-0 fw-bold">Fecha</label>
                            <input type="date" name="fecha" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label mb-0 fw-bold">Presidido por (Colaborador)</label>
                            <select name="colaborador_id" class="form-select form-select-sm" required>
                                <option value="">Seleccione...</option>
                                @foreach($colaboradores as $c)
                                    <option value="{{ $c->id }}">{{ $c->apellidos }}, {{ $c->nombres }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-0 fw-bold">Alumno Vinculado</label>
                            <select name="alumno_id" class="form-select form-select-sm select2-modal" required>
                                <option value="">Vincular estudiante...</option>
                                @foreach($alumnos as $a)
                                    <option value="{{ $a->id }}">{{ $a->apellidos }}, {{ $a->nombres }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-0 fw-bold">Testigos / Otros Miembros Presentes</label>
                            <select name="testigos[]" class="form-select form-select-sm select2-modal" multiple style="width: 100%;">
                                @foreach($colaboradores as $c)
                                    <option value="{{ $c->id }}">{{ $c->apellidos }}, {{ $c->nombres }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-0 fw-bold">Motivo del Encuentro</label>
                            <input type="text" name="motivo" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-0 fw-bold">Resoluciones / Acuerdos del Acta</label>
                            <textarea name="observaciones" class="form-control form-control-sm" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-1">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-sm">Asentar Acta</button>
                </div>
            </form>
        </div>
    </div>


    <div class="modal fade" id="modalEditarAlumno" tabindex="-1">
        <div class="modal-dialog">
            <form id="formEditarAlumno" method="POST" class="modal-content">
                @csrf @method('PUT')
                <div class="modal-header p-2 bg-warning text-dark">
                    <h6 class="modal-title fw-bold" style="font-size: 0.85rem;"><i class="fas fa-edit me-1"></i> Modificar Entrevista Alumno</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-2">
                    <div class="row g-2" style="font-size: 0.75rem;">
                        <div class="col-12">
                            <label class="form-label mb-0 fw-bold">Alumno</label>
                            <input type="text" id="edit_al_nombre" class="form-control form-control-sm bg-light text-muted" readonly disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-0 fw-bold">Fecha</label>
                            <input type="date" name="fecha" id="edit_al_fecha" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label mb-0 fw-bold">Entrevistador</label>
                            <select name="colaborador_id" id="edit_al_colaborador" class="form-select form-select-sm" required>
                                @foreach($colaboradores as $c)
                                    <option value="{{ $c->id }}">{{ $c->apellidos }}, {{ $c->nombres }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-0 fw-bold">Motivo Principal</label>
                            <input type="text" name="motivo" id="edit_al_motivo" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-0 fw-bold">Observaciones / Acuerdos</label>
                            <textarea name="observaciones" id="edit_al_obs" class="form-control form-control-sm" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-1">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning btn-sm fw-bold text-dark">Actualizar Registro</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalEditarResponsable" tabindex="-1">
        <div class="modal-dialog">
            <form id="formEditarResponsable" method="POST" class="modal-content">
                @csrf @method('PUT')
                <div class="modal-header p-2 bg-warning text-dark">
                    <h6 class="modal-title fw-bold" style="font-size: 0.85rem;"><i class="fas fa-edit me-1"></i> Modificar Acta de Responsables</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-2">
                    <div class="row g-2" style="font-size: 0.75rem;">
                        <div class="col-12">
                            <label class="form-label mb-0 fw-bold">Alumno Vinculado</label>
                            <input type="text" id="edit_res_nombre" class="form-control form-control-sm bg-light text-muted" readonly disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-0 fw-bold">Fecha</label>
                            <input type="date" name="fecha" id="edit_res_fecha" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label mb-0 fw-bold">Atendido por</label>
                            <select name="colaborador_id" id="edit_res_colaborador" class="form-select form-select-sm" required>
                                @foreach($colaboradores as $c)
                                    <option value="{{ $c->id }}">{{ $c->apellidos }}, {{ $c->nombres }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-0 fw-bold">Testigos en Acta (Multiselect)</label>
                            <select name="testigos[]" id="edit_res_testigos" class="form-select form-select-sm select2-modal" multiple style="width: 100%;">
                                @foreach($colaboradores as $c)
                                    <option value="{{ $c->id }}">{{ $c->apellidos }}, {{ $c->nombres }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-0 fw-bold">Motivo del Encuentro</label>
                            <input type="text" name="motivo" id="edit_res_motivo" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-0 fw-bold">Resoluciones / Acuerdos</label>
                            <textarea name="observaciones" id="edit_res_obs" class="form-control form-control-sm" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-1">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning btn-sm fw-bold text-dark">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalEliminarCst" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <form id="formEliminarCst" method="POST" class="modal-content">
                @csrf @method('DELETE')
                <div class="modal-header p-2 bg-danger text-white">
                    <h6 class="modal-title" style="font-size: 0.85rem;"><i class="fas fa-exclamation-triangle me-1"></i> Confirmar Eliminación</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3 text-center">
                    <p class="mb-0" style="font-size: 0.75rem;">¿Está seguro que desea borrar permanentemente el registro de:</p>
                    <strong id="eliminar_desc_cst" class="text-danger d-block mt-1" style="font-size: 0.75rem;">—</strong>
                </div>
                <div class="modal-footer p-1 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger btn-sm">Eliminar Registro</button>
                </div>
            </form>
        </div>
    </div>


    @push('scripts')
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

        <script>
            $(document).ready(function() {
                
                // Configuración común de DataTables (Copiada de tu archivo faltas.index)
                const configDataTable = {
                    pageLength: 15,
                    lengthMenu: [15, 30, 50, 100],
                    order: [[0, 'desc']],
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                    },
                    dom: "<'row mb-2'<'col-sm-5'B><'col-sm-7'f>>" +
                         "<'row'<'col-sm-12'tr>>" +
                         "<'row mt-2'<'col-sm-6'i><'col-sm-6'p>>",
                    buttons: [
                        { extend: 'excel', className: 'btn btn-success btn-sm py-1 px-2 border-0', text: '<i class="fas fa-file-excel"></i> Excel' },
                        { extend: 'pdf', className: 'btn btn-danger btn-sm py-1 px-2 border-0', text: '<i class="fas fa-file-pdf"></i> PDF', orientation: 'landscape' },
                        { extend: 'print', className: 'btn btn-secondary btn-sm py-1 px-2 border-0', text: '<i class="fas fa-print"></i> Imprimir' }
                    ]
                };

                // Inicializar ambas tablas con DataTables Buttons incorporado
                $('#tabla-entrevistas-alumnos').DataTable(configDataTable);
                $('#tabla-entrevistas-responsables').DataTable(configDataTable);

                // Inicialización de Select2 para modales (si lo maneja tu app.blade)
                if ($.fn.select2) {
                    $('.select2-modal').select2({
                        theme: 'bootstrap-5',
                        width: '100%'
                    });
                }

                // ── CONTROLADOR DE MODAL EDITAR: ALUMNOS ──
                $(document).on('click', '.btn-editar-alumno', function() {
                    const id = $(this).data('id');
                    $('#formEditarAlumno').attr('action', '{{ url("academica/entrevistas/alumno") }}/' + id);
                    $('#edit_al_nombre').val($(this).data('alumno-nombre'));
                    $('#edit_al_fecha').val($(this).data('fecha'));
                    $('#edit_al_colaborador').val($(this).data('colaborador-id'));
                    $('#edit_al_motivo').val($(this).data('motivo'));
                    $('#edit_al_obs').val($(this).data('obs'));

                    new bootstrap.Modal(document.getElementById('modalEditarAlumno')).show();
                });

                // ── CONTROLADOR DE MODAL EDITAR: RESPONSABLES ──
                $(document).on('click', '.btn-editar-resp', function() {
                    const id = $(this).data('id');
                    $('#formEditarResponsable').attr('action', '{{ url("academica/entrevistas/responsable") }}/' + id);
                    $('#edit_res_nombre').val($(this).data('alumno-nombre'));
                    $('#edit_res_fecha').val($(this).data('fecha'));
                    $('#edit_res_colaborador').val($(this).data('colaborador-id'));
                    $('#edit_res_motivo').val($(this).data('motivo'));
                    $('#edit_res_obs').val($(this).data('obs'));

                    // Set de multiselect para testigos
                    const testigosIds = $(this).data('testigos');
                    if ($.fn.select2) {
                        $('#edit_res_testigos').val(testigosIds).trigger('change');
                    } else {
                        $('#edit_res_testigos').val(testigosIds);
                    }

                    new bootstrap.Modal(document.getElementById('modalEditarResponsable')).show();
                });

                // ── CONTROLADORES DE MODAL ELIMINAR REUTILIZABLE ──
                $(document).on('click', '.btn-eliminar-alumno', function() {
                    const id = $(this).data('id');
                    const desc = $(this).data('desc');
                    $('#eliminar_desc_cst').text(desc);
                    $('#formEliminarCst').attr('action', '{{ url("academica/entrevistas/alumno") }}/' + id);
                    new bootstrap.Modal(document.getElementById('modalEliminarCst')).show();
                });

                $(document).on('click', '.btn-eliminar-resp', function() {
                    const id = $(this).data('id');
                    const desc = $(this).data('desc');
                    $('#eliminar_desc_cst').text(desc);
                    $('#formEliminarCst').attr('action', '{{ url("academica/entrevistas/responsable") }}/' + id);
                    new bootstrap.Modal(document.getElementById('modalEliminarCst')).show();
                });
            });
        </script>
    @endpush

</x-app-layout>