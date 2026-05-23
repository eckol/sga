<x-app-layout>

    <!-- CDNs específicos para DataTables Buttons -->
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
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registro de Faltas</h2>
    </x-slot>

    {{-- Mensajes de éxito --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-1 px-2 mb-2" style="font-size:0.8rem">
            {{ session('success') }}
            <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── FILTROS ── --}}
    <div class="card card-body p-2 shadow-sm mb-3">
        <form method="GET" action="{{ route('academica.faltas.index') }}" id="formFiltro">
            <div class="row align-items-center g-2">
                <div class="col-md-auto">
                    <label class="col-form-label col-form-label-sm fw-bold">Grado/Curso:</label>
                </div>
                <div class="col-md-3">
                    <select name="grado_id" class="form-select form-select-sm"
                        onchange="document.getElementById('formFiltro').submit()">
                        <option value="">— Todos —</option>
                        @foreach($grados as $g)
                            <option value="{{ $g->id }}" {{ $gradoId == $g->id ? 'selected' : '' }}>
                                {{ $g->gradocurso }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-auto">
                    <label class="col-form-label col-form-label-sm fw-bold">Desde:</label>
                </div>
                <div class="col-md-2">
                    <input type="date" name="fecha_desde" class="form-control form-control-sm" value="{{ $fechaDesde }}"
                        onchange="document.getElementById('formFiltro').submit()">
                </div>
                <div class="col-md-auto">
                    <label class="col-form-label col-form-label-sm fw-bold">Hasta:</label>
                </div>
                <div class="col-md-2">
                    <input type="date" name="fecha_hasta" class="form-control form-control-sm" value="{{ $fechaHasta }}"
                        onchange="document.getElementById('formFiltro').submit()">
                </div>
                <div class="col-md-auto">
                    <a href="{{ route('academica.faltas.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                </div>
                <div class="col-md-auto ms-auto">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#modalCrear">
                        <i class="fas fa-plus"></i> Nueva Falta
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ── TABLA ── --}}
    <div class="card card-body p-2 shadow-sm">
        <table id="tabla-faltas" class="table table-sm table-hover table-bordered">
            <thead class="table-light">
                <tr>
                    <th width="40" class="text-center">N°</th>
                    <th width="40" class="text-center">ID</th>
                    <th>Falta</th>
                    <th>Fecha</th>
                    <th>Grado/Curso</th>
                    <th>Alumno</th>
                    <th>Asignatura</th>
                    <th width="80" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($faltas as $f)
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center">{{ $f->id }}</td>
                        <td>{{ $f->indicadorFalta->indicador_falta ?? '—' }}</td>
                        <td>{{ \Carbon\Carbon::parse($f->fecha)->format('d/m/Y') }}</td>
                        <td>{{ $f->gradoCurso->gradocurso ?? '—' }}</td>
                        <td>{{ $f->alumno ? $f->alumno->apellidos . ', ' . $f->alumno->nombres : '—' }}</td>
                        <td>{{ $f->asignatura->asignatura ?? '—' }}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-warning btn-xs py-0 px-1 btn-editar"
                                style="font-size:0.65rem" data-id="{{ $f->id }}"
                                data-fecha="{{ $f->fecha->format('Y-m-d') }}" data-grado="{{ $f->grado_curso_id }}"
                                data-alumno="{{ $f->alumno_id }}" data-asignatura="{{ $f->asignatura_id }}"
                                data-indicador="{{ $f->indicador_falta_id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-xs py-0 px-1 btn-eliminar"
                                style="font-size:0.65rem" data-id="{{ $f->id }}"
                                data-desc="{{ $f->indicadorFalta->indicador_falta ?? '' }} — {{ $f->alumno ? $f->alumno->apellidos . ' ' . $f->alumno->nombres : '' }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ════════════════════════════════════════════════ --}}
    {{-- MODAL CREAR --}}
    {{-- ════════════════════════════════════════════════ --}}
    <div class="modal fade" id="modalCrear" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-cst">
                    <h6 class="modal-title"><i class="fas fa-plus-circle me-1"></i> Registrar Nueva Falta</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('academica.faltas.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label form-label-sm fw-bold">Fecha <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="fecha" id="crear_fecha" class="form-control form-control-sm"
                                    required value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label form-label-sm fw-bold">Grado/Curso <span
                                        class="text-danger">*</span></label>
                                <select name="grado_curso_id" id="crear_grado" class="form-select form-select-sm"
                                    required>
                                    <option value="">— Seleccionar —</option>
                                    @foreach($grados as $g)
                                        <option value="{{ $g->id }}">{{ $g->gradocurso }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label form-label-sm fw-bold">Alumno <span
                                        class="text-danger">*</span></label>
                                <select name="alumno_id" id="crear_alumno" class="form-select form-select-sm" required
                                    disabled>
                                    <option value="">— Primero seleccione un grado —</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label form-label-sm fw-bold">Asignatura <span
                                        class="text-danger">*</span></label>
                                <select name="asignatura_id" id="crear_asignatura" class="form-select form-select-sm"
                                    required disabled>
                                    <option value="">— Primero seleccione un grado —</option>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label form-label-sm fw-bold">Docente</label>
                                <input type="text" id="crear_docente" class="form-control form-control-sm"
                                    placeholder="(se completa automáticamente)" readonly>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label form-label-sm fw-bold">Tipo de Falta <span
                                        class="text-danger">*</span></label>
                                <select name="indicador_falta_id" id="crear_indicador"
                                    class="form-select form-select-sm" required>
                                    <option value="">— Seleccionar —</option>
                                    @foreach($indicadores as $ind)
                                        <option value="{{ $ind->id }}">{{ $ind->indicador_falta }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer py-1">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-save me-1"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════ --}}
    {{-- MODAL EDITAR --}}
    {{-- ════════════════════════════════════════════════ --}}
    <div class="modal fade" id="modalEditar" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-cst">
                    <h6 class="modal-title"><i class="fas fa-edit me-1"></i> Editar Falta</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="formEditar" action="">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label form-label-sm fw-bold">Fecha <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="fecha" id="editar_fecha" class="form-control form-control-sm"
                                    required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label form-label-sm fw-bold">Grado/Curso <span
                                        class="text-danger">*</span></label>
                                <select name="grado_curso_id" id="editar_grado" class="form-select form-select-sm"
                                    required>
                                    <option value="">— Seleccionar —</option>
                                    @foreach($grados as $g)
                                        <option value="{{ $g->id }}">{{ $g->gradocurso }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label form-label-sm fw-bold">Alumno <span
                                        class="text-danger">*</span></label>
                                <select name="alumno_id" id="editar_alumno" class="form-select form-select-sm" required>
                                    <option value="">— Cargando —</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label form-label-sm fw-bold">Asignatura <span
                                        class="text-danger">*</span></label>
                                <select name="asignatura_id" id="editar_asignatura" class="form-select form-select-sm"
                                    required>
                                    <option value="">— Cargando —</option>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label form-label-sm fw-bold">Docente</label>
                                <input type="text" id="editar_docente" class="form-control form-control-sm" readonly>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label form-label-sm fw-bold">Tipo de Falta <span
                                        class="text-danger">*</span></label>
                                <select name="indicador_falta_id" id="editar_indicador"
                                    class="form-select form-select-sm" required>
                                    <option value="">— Seleccionar —</option>
                                    @foreach($indicadores as $ind)
                                        <option value="{{ $ind->id }}">{{ $ind->indicador_falta }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer py-1">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning btn-sm">
                            <i class="fas fa-save me-1"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════ --}}
    {{-- MODAL ELIMINAR --}}
    {{-- ════════════════════════════════════════════════ --}}
    <div class="modal fade" id="modalEliminar" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header modal-header-cst">
                    <h6 class="modal-title"><i class="fas fa-trash me-1"></i> Eliminar Falta</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="font-size:0.85rem">
                    ¿Eliminar la falta <strong id="eliminar_desc"></strong>?
                </div>
                <div class="modal-footer py-1">
                    <form method="POST" id="formEliminar" action="">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash me-1"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>
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
            window.onload = function () {
                if (!window.jQuery) {
                    alert('Error crítico: jQuery no cargado.');
                    return;
                }

                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

                // ── DataTable ──
                var t = $('#tabla-faltas').DataTable({
                    paging: true,
                    pageLength: 25,
                    order: [[3, 'desc']],
                    columnDefs: [{ searchable: false, orderable: false, targets: 0 }],
                    language: {
                        search: 'Buscar:',
                        lengthMenu: 'Mostrar _MENU_ registros',
                        info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                        infoEmpty: 'Mostrando 0 registros',
                        infoFiltered: '(filtrado de _MAX_)',
                        zeroRecords: 'No se encontraron registros',
                        emptyTable: 'No hay faltas registradas',
                        paginate: { first: '«', last: '»', next: '›', previous: '‹' }
                    },
                    dom: "<'row mb-2'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    buttons: [
                        {
                            extend: 'excelHtml5', text: '<i class="fas fa-file-excel"></i> Excel',
                            className: 'btn btn-success btn-sm',
                            title: 'Colegio Privado Santa Teresita - Luque, Paraguay',
                            messageTop: 'Registro de Faltas',
                            exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7] }
                        },
                        {
                            extend: 'pdfHtml5', text: '<i class="fas fa-file-pdf"></i> PDF',
                            className: 'btn btn-danger btn-sm',
                            title: 'Colegio Privado Santa Teresita - Luque, Paraguay',
                            messageTop: 'Registro de Faltas',
                            exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7] }
                        },
                        {
                            extend: 'print', text: '<i class="fas fa-print"></i> Imprimir',
                            className: 'btn btn-secondary btn-sm',
                            title: 'Colegio Privado Santa Teresita - Luque, Paraguay',
                            exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7] }
                        }
                    ]
                });

                t.on('order.dt search.dt', function () {
                    let i = 1;
                    t.cells(null, 0, { search: 'applied', order: 'applied' }).every(function () {
                        this.data(i++);
                    });
                }).draw();

                // ── Función reutilizable: cargar alumnos por grado ──
                function cargarAlumnos(gradoId, selectEl, selectedId = null) {
                    $(selectEl).prop('disabled', true).html('<option>Cargando...</option>');
                    if (!gradoId) {
                        $(selectEl).prop('disabled', true).html('<option value="">— Primero seleccione un grado —</option>');
                        return;
                    }
                    $.get('{{ url("academica/faltas/alumnos-por-grado") }}/' + gradoId, function (data) {
                        let opts = '<option value="">— Seleccionar alumno —</option>';
                        data.forEach(a => {
                            const sel = (selectedId && a.id == selectedId) ? 'selected' : '';
                            opts += `<option value="${a.id}" ${sel}>${a.apellidos}, ${a.nombres}</option>`;
                        });
                        $(selectEl).prop('disabled', false).html(opts);
                    });
                }

                // ── Función reutilizable: cargar asignaturas+docente por grado ──
                function cargarAsignaturas(gradoId, selectEl, docenteEl, selectedId = null) {
                    $(selectEl).prop('disabled', true).html('<option>Cargando...</option>');
                    $(docenteEl).val('');
                    if (!gradoId) {
                        $(selectEl).prop('disabled', true).html('<option value="">— Primero seleccione un grado —</option>');
                        return;
                    }
                    $.get('{{ url("academica/faltas/asignaturas-por-grado") }}/' + gradoId, function (data) {
                        let opts = '<option value="">— Seleccionar asignatura —</option>';
                        data.forEach(a => {
                            const sel = (selectedId && a.asignatura_id == selectedId) ? 'selected' : '';
                            opts += `<option value="${a.asignatura_id}" data-docente="${a.docente}" ${sel}>${a.asignatura}</option>`;
                        });
                        $(selectEl).prop('disabled', false).html(opts);
                        // Si hay seleccionado, mostrar docente
                        if (selectedId) {
                            const opt = $(selectEl).find('option:selected');
                            $(docenteEl).val(opt.data('docente') || '');
                        }
                    });
                }

                // ── Cambio de grado en modal CREAR ──
                $('#crear_grado').on('change', function () {
                    const gid = $(this).val();
                    cargarAlumnos(gid, '#crear_alumno');
                    cargarAsignaturas(gid, '#crear_asignatura', '#crear_docente');
                });

                // ── Cambio de asignatura en modal CREAR: mostrar docente ──
                $(document).on('change', '#crear_asignatura', function () {
                    $('#crear_docente').val($(this).find('option:selected').data('docente') || '');
                });

                // ── Cambio de grado en modal EDITAR ──
                $('#editar_grado').on('change', function () {
                    const gid = $(this).val();
                    cargarAlumnos(gid, '#editar_alumno');
                    cargarAsignaturas(gid, '#editar_asignatura', '#editar_docente');
                });

                // ── Cambio de asignatura en modal EDITAR: mostrar docente ──
                $(document).on('change', '#editar_asignatura', function () {
                    $('#editar_docente').val($(this).find('option:selected').data('docente') || '');
                });

                // ── Abrir modal EDITAR ──
                $(document).on('click', '.btn-editar', function () {
                    const btn = $(this);
                    const faltaId = btn.data('id');
                    const gradoId = btn.data('grado');
                    const alumnoId = btn.data('alumno');
                    const asigId = btn.data('asignatura');
                    const indId = btn.data('indicador');
                    const fecha = btn.data('fecha');

                    $('#formEditar').attr('action', '{{ url("academica/faltas") }}/' + faltaId);
                    $('#editar_fecha').val(fecha);
                    $('#editar_indicador').val(indId);

                    // Cargar grado y luego alumnos/asignaturas con valores preseleccionados
                    $('#editar_grado').val(gradoId);
                    cargarAlumnos(gradoId, '#editar_alumno', alumnoId);
                    cargarAsignaturas(gradoId, '#editar_asignatura', '#editar_docente', asigId);

                    new bootstrap.Modal(document.getElementById('modalEditar')).show();
                });

                // ── Abrir modal ELIMINAR ──
                $(document).on('click', '.btn-eliminar', function () {
                    const id = $(this).data('id');
                    const desc = $(this).data('desc');
                    $('#eliminar_desc').text(desc);
                    $('#formEliminar').attr('action', '{{ url("academica/faltas") }}/' + id);
                    new bootstrap.Modal(document.getElementById('modalEliminar')).show();
                });
            };
        </script>
    @endpush

</x-app-layout>