@extends('layouts.portal')

@section('content')
    <style>
        /* ── Estilos base del sistema ── */
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

        #tabla-portal-responsables th,
        #tabla-portal-responsables td {
            font-size: 0.75rem;
            vertical-align: middle;
        }
    </style>

    <div class="container-fluid py-2">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-0 text-dark fw-bold" style="font-size: 1.1rem;">
                        <i class="fas fa-users-viewfinder text-primary me-2"></i>Portal de Familias
                    </h5>
                    <small class="text-muted">Alumnos registrados bajo su responsabilidad para el año lectivo
                        {{ date('Y') }}</small>
                </div>
            </div>

            <div class="card-body p-3">
                <div class="table-responsive">
                    <table id="tabla-portal-responsables" class="table table-striped table-hover w-100 m-0">
                        <thead class="table-light text-secondary">
                            <tr>
                                <th style="width: 10%;">ID Alumno</th>
                                <th>Apellidos</th>
                                <th>Nombres</th>
                                <th style="width: 15%;">Cédula de Id.</th>
                                <th>Grado / Curso</th>
                                <th style="width: 10%;" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($alumnos) > 0)
                                @foreach($alumnos as $alumno)
                                    @php
                                        $inscripcion = $alumno->inscripciones->first();
                                        $gradoNombre = $inscripcion && $inscripcion->grado
                                            ? $inscripcion->grado->gradocurso
                                            : 'Sin Inscripción Activa';
                                    @endphp
                                    <tr>
                                        <td class="fw-bold text-secondary">#{{ $alumno->id }}</td>
                                        <td>{{ $alumno->apellidos }}</td>
                                        <td>{{ $alumno->nombres }}</td>
                                        <td>{{ number_format($alumno->cid, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.7rem;">
                                                <i class="fas fa-graduation-cap text-muted me-1"></i>{{ $gradoNombre }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-primary p-1 px-2 btn-ver-asistencia"
                                                data-id="{{ $alumno->id }}"
                                                data-nombre="{{ $alumno->apellidos }}, {{ $alumno->nombres }}"
                                                title="Ver Ficha de Asistencia">
                                                <i class="fas fa-search" style="font-size: 0.8rem;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-muted py-4 text-center" style="font-size: 0.75rem;"><i
                                            class="fas fa-info-circle me-1"></i></td>
                                    <td class="text-muted py-4 text-start" style="font-size: 0.75rem;">No se encontraron alumnos
                                        vinculados a su cuenta de correo para este año lectivo.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAlumnoAsist" tabindex="-1" style="z-index: 1060;">
        <div class="modal-dialog modal-xl" style="max-width: 95vw;">
            <div class="modal-content">

                <div class="modal-header p-2 bg-primary text-white">
                    <h6 class="modal-title">
                        <i class="fas fa-user-graduate me-1"></i>
                        <span id="modal-alumno-nombre">—</span>
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <input type="hidden" id="masist_alumno_id">
                <input type="hidden" id="masist_mes">
                <input type="hidden" id="masist_anio">

                <div class="modal-body p-2" style="font-size: 0.78rem;">

                    <ul class="nav nav-tabs" id="modalAlumnoAsistTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active py-1" data-bs-toggle="tab" data-bs-target="#masist-tab-datos"
                                type="button">
                                <i class="fas fa-id-card me-1"></i>Datos
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link py-1" data-bs-toggle="tab" data-bs-target="#masist-tab-responsables"
                                type="button">
                                <i class="fas fa-people-group me-1"></i>Responsables
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link py-1" data-bs-toggle="tab" data-bs-target="#masist-tab-inscripciones"
                                type="button">
                                <i class="fas fa-file-alt me-1"></i>Inscripciones
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link py-1" data-bs-toggle="tab" data-bs-target="#masist-tab-asistencia"
                                type="button">
                                <i class="fas fa-calendar-check me-1"></i>Asistencia
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link py-1" data-bs-toggle="tab" data-bs-target="#masist-tab-resumen"
                                type="button">
                                <i class="fas fa-chart-bar me-1"></i>Resumen anual
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link py-1" data-bs-toggle="tab" data-bs-target="#masist-tab-faltas"
                                type="button">
                                <i class="fas fa-triangle-exclamation me-1"></i>Faltas
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content pt-2">

                        {{-- Tab 1: Datos Personales --}}
                        <div class="tab-pane fade show active" id="masist-tab-datos">
                            <div class="row g-2 align-items-start">
                                <div class="col-md-2 text-center">
                                    <img id="masist-foto" src="" alt="Foto" class="rounded-circle shadow"
                                        style="width:90px;height:90px;object-fit:cover;">
                                </div>
                                <div class="col-md-10">
                                    <div class="row g-1">
                                        <div class="col-md-5">
                                            <label class="form-label mb-0 fw-bold">Apellidos</label>
                                            <input type="text" id="mdatos-apellidos"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label mb-0 fw-bold">Nombres</label>
                                            <input type="text" id="mdatos-nombres"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label mb-0 fw-bold">Cédula</label>
                                            <input type="text" id="mdatos-cid" class="form-control form-control-sm bg-light"
                                                readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label mb-0 fw-bold">Fecha Nac.</label>
                                            <input type="text" id="mdatos-fnac"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label mb-0 fw-bold">Sexo</label>
                                            <input type="text" id="mdatos-sexo"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label mb-0 fw-bold">Nacionalidad</label>
                                            <input type="text" id="mdatos-nacionalidad"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label mb-0 fw-bold">Teléfono</label>
                                            <input type="text" id="mdatos-telefono"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label mb-0 fw-bold">Email</label>
                                            <input type="text" id="mdatos-email"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label mb-0 fw-bold">Dirección</label>
                                            <input type="text" id="mdatos-direccion"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label mb-0 fw-bold">Barrio</label>
                                            <input type="text" id="mdatos-barrio"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label mb-0 fw-bold">Ciudad</label>
                                            <input type="text" id="mdatos-ciudad"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label mb-0 fw-bold">Vive Con</label>
                                            <input type="text" id="mdatos-vivecon"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label mb-0 fw-bold">Salud</label>
                                            <input type="text" id="mdatos-salud"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label mb-0 fw-bold">Observaciones</label>
                                            <input type="text" id="mdatos-observaciones"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tab 2: Responsables --}}
                        <div class="tab-pane fade" id="masist-tab-responsables">
                            <div class="row g-2">
                                {{-- Madre --}}
                                <div class="col-md-12">
                                    <h6 class="text-primary fw-bold border-bottom pb-1" style="font-size:0.78rem;">
                                        <i class="fas fa-female me-1"></i>Madre / Tutora
                                    </h6>
                                    <div class="row g-1">
                                        <div class="col-md-4">
                                            <label class="form-label mb-0 fw-bold">Nombre</label>
                                            <input type="text" id="mdatos-madre-nombre"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label mb-0 fw-bold">Cédula</label>
                                            <input type="text" id="mdatos-madre-cid"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label mb-0 fw-bold">Teléfono</label>
                                            <input type="text" id="mdatos-madre-tel"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label mb-0 fw-bold">Email</label>
                                            <input type="text" id="mdatos-madre-email"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label mb-0 fw-bold">Profesión</label>
                                            <input type="text" id="mdatos-madre-profesion"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label mb-0 fw-bold">Lugar de Trabajo</label>
                                            <input type="text" id="mdatos-madre-trabajo"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                    </div>
                                </div>
                                {{-- Padre --}}
                                <div class="col-md-12 mt-2">
                                    <h6 class="text-primary fw-bold border-bottom pb-1" style="font-size:0.78rem;">
                                        <i class="fas fa-male me-1"></i>Padre / Tutor
                                    </h6>
                                    <div class="row g-1">
                                        <div class="col-md-4">
                                            <label class="form-label mb-0 fw-bold">Nombre</label>
                                            <input type="text" id="mdatos-padre-nombre"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label mb-0 fw-bold">Cédula</label>
                                            <input type="text" id="mdatos-padre-cid"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label mb-0 fw-bold">Teléfono</label>
                                            <input type="text" id="mdatos-padre-tel"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label mb-0 fw-bold">Email</label>
                                            <input type="text" id="mdatos-padre-email"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label mb-0 fw-bold">Profesión</label>
                                            <input type="text" id="mdatos-padre-profesion"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label mb-0 fw-bold">Lugar de Trabajo</label>
                                            <input type="text" id="mdatos-padre-trabajo"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                    </div>
                                </div>
                                {{-- Encargado --}}
                                <div class="col-md-12 mt-2">
                                    <h6 class="text-primary fw-bold border-bottom pb-1" style="font-size:0.78rem;">
                                        <i class="fas fa-person me-1"></i>Encargado
                                    </h6>
                                    <div class="row g-1">
                                        <div class="col-md-4">
                                            <label class="form-label mb-0 fw-bold">Nombre</label>
                                            <input type="text" id="mdatos-encargado-nombre"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label mb-0 fw-bold">Cédula</label>
                                            <input type="text" id="mdatos-encargado-cid"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label mb-0 fw-bold">Teléfono</label>
                                            <input type="text" id="mdatos-encargado-tel"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label mb-0 fw-bold">Email</label>
                                            <input type="text" id="mdatos-encargado-email"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tab 3: Inscripciones --}}
                        <div class="tab-pane fade" id="masist-tab-inscripciones">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-hover" style="font-size:0.75rem;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Año</th>
                                            <th>Fecha</th>
                                            <th>Grado/Curso</th>
                                            <th>Firmante</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody id="masist-inscripciones-body">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Cargando...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Tab 4: Asistencia mensual --}}
                        <div class="tab-pane fade" id="masist-tab-asistencia">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <button id="masist-btn-prev" type="button"
                                    class="btn btn-outline-secondary btn-sm py-0 px-2" style="font-size:0.7rem;">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <span id="masist-cal-titulo" class="fw-bold" style="font-size:0.8rem;">—</span>
                                <button id="masist-btn-next" type="button"
                                    class="btn btn-outline-secondary btn-sm py-0 px-2" style="font-size:0.7rem;">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                            <div id="masist-calendario" class="py-1">
                                <p class="text-muted text-center">Cargando...</p>
                            </div>
                            <div class="mt-2 d-flex gap-3" style="font-size:0.65rem;color:#6c757d;">
                                <span><span
                                        style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#198754;margin-right:3px"></span>Presente</span>
                                <span><span
                                        style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#dc3545;margin-right:3px"></span>Ausente</span>
                                <span><span
                                        style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#ffc107;margin-right:3px"></span>Justificado</span>
                                <span><span
                                        style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#0dcaf0;margin-right:3px"></span>Tardanza</span>
                            </div>
                        </div>

                        {{-- Tab 5: Resumen anual --}}
                        <div class="tab-pane fade" id="masist-tab-resumen">
                            <div id="masist-resumen">
                                <p class="text-muted text-center py-2">Cargando...</p>
                            </div>
                        </div>

                        {{-- Tab 6: Faltas --}}
                        <div class="tab-pane fade" id="masist-tab-faltas">
                            <div class="table-responsive mt-1">
                                <table class="table table-sm table-bordered table-hover" style="font-size:0.75rem;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Indicador</th>
                                            <th>Asignatura</th>
                                            <th>Docente</th>
                                        </tr>
                                    </thead>
                                    <tbody id="masist-faltas-body">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Cargando...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer p-1">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // Guardamos en un array JS los IDs de alumnos válidos para este responsable (Blindaje Front)
            const idsPermitidos = {!! json_encode($alumnos->pluck('id')->toArray()) !!};

            // Inicialización limpia de DataTables
            $('#tabla-portal-responsables').DataTable({
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
                pageLength: 10,
                responsive: true,
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            });

            const CSRF = "{{ csrf_token() }}";
            const URL_POR_ALUMNO = "{{ url('academica/asistencias') }}";

            let _modalMes = new Date().getMonth() + 1;
            let _modalAnio = new Date().getFullYear();

            // Setea los select del modal cuando cambia el combo dinámicamente
            $('#masist_mes').on('change', function () {
                const aId = parseInt($('#masist_alumno_id').val());
                if (idsPermitidos.includes(aId)) {
                    _modalMes = parseInt($(this).val());
                    cargarCalendarioModal(aId, _modalMes, _modalAnio);
                }
            });

            $('#masist_anio').on('change', function () {
                const aId = parseInt($('#masist_alumno_id').val());
                if (idsPermitidos.includes(aId)) {
                    _modalAnio = parseInt($(this).val());
                    cargarCalendarioModal(aId, _modalMes, _modalAnio);
                }
            });

            // Al hacer clic en la lupa
            $('.btn-ver-asistencia').on('click', function () {
                const alumnoId = parseInt($(this).data('id'));
                const alumnoNombre = $(this).data('nombre');

                // VALIDACIÓN DE SEGURIDAD INTERNA:
                if (!idsPermitidos.includes(alumnoId)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Acceso Denegado',
                        text: 'No tiene autorización para visualizar los datos de este estudiante.',
                        confirmButtonColor: '#dc3545'
                    });
                    return;
                }

                $('#modal-alumno-nombre').text(alumnoNombre);
                $('#masist_alumno_id').val(alumnoId);

                _modalMes = new Date().getMonth() + 1;
                _modalAnio = new Date().getFullYear();
                $('#masist_mes').val(_modalMes);
                $('#masist_anio').val(_modalAnio);

                const firstTab = new bootstrap.Tab(document.querySelector('#modalAlumnoAsistTabs button[data-bs-target="#masist-tab-datos"]'));
                firstTab.show();

                $('#modalAlumnoAsist').modal('show');
                cargarCalendarioModal(alumnoId, _modalMes, _modalAnio);
            });

            // Lógica AJAX para alimentar el modal
            function cargarCalendarioModal(alumnoId, mes, anio) {
                if (!alumnoId || !idsPermitidos.includes(parseInt(alumnoId))) return;

                $('#masist-calendario').html('<p class="text-muted text-center py-2">Cargando calendario...</p>');
                $('#masist-resumen').html('<p class="text-muted text-center py-2">Cargando resumen...</p>');
                $('#masist-foto').attr('src', '');
                $('#mdatos-apellidos, #mdatos-nombres, #mdatos-cid, #mdatos-madre, #mdatos-padre, #mdatos-encargado').val('—');

                $.ajax({
                    url: `${URL_POR_ALUMNO}/${alumnoId}/por-alumno`,
                    method: 'GET',
                    data: { mes: mes, anio: anio },
                    success: function (res) {
                        // ── Tab Datos Personales ──
                        if (res.alumno) {
                            const a = res.alumno;
                            $('#mdatos-apellidos').val(a.apellidos || '—');
                            $('#mdatos-nombres').val(a.nombres || '—');
                            $('#mdatos-cid').val(a.cid ? parseInt(a.cid).toLocaleString('es-PY') : '—');
                            $('#mdatos-fnac').val(a.fnac || '—');
                            $('#mdatos-sexo').val(a.sexo?.sexo || '—');
                            $('#mdatos-nacionalidad').val(a.nacionalidad?.nacionalidad || '—');
                            $('#mdatos-telefono').val(a.telefono || '—');
                            $('#mdatos-email').val(a.email || '—');
                            $('#mdatos-direccion').val(a.direccion || '—');
                            $('#mdatos-barrio').val(a.barrio || '—');
                            $('#mdatos-ciudad').val(a.ciudad?.ciudad || '—');
                            $('#mdatos-vivecon').val(a.vivecon?.vive_con || a.vivecon?.vivecon || '—');
                            $('#mdatos-salud').val(a.salud || '—');
                            $('#mdatos-observaciones').val(a.observaciones || '—');

                            if (a.foto) {
                                $('#masist-foto').attr('src', `{{ asset('img/alumnos') }}/${a.foto}`);
                            } else {
                                $('#masist-foto').attr('src', `{{ asset('img/alumnos/alumno.jpg') }}`);
                            }

                            // ── Tab Responsables ──
                            if (a.madre) {
                                $('#mdatos-madre-nombre').val(a.madre.nombre || '—');
                                $('#mdatos-madre-cid').val(a.madre.cid || '—');
                                $('#mdatos-madre-tel').val(a.madre.telefono1 || '—');
                                $('#mdatos-madre-email').val(a.madre.email || '—');
                                $('#mdatos-madre-profesion').val(a.madre.profesion || '—');
                                $('#mdatos-madre-trabajo').val(a.madre.lugartrabajo || '—');
                            }
                            if (a.padre) {
                                $('#mdatos-padre-nombre').val(a.padre.nombre || '—');
                                $('#mdatos-padre-cid').val(a.padre.cid || '—');
                                $('#mdatos-padre-tel').val(a.padre.telefono1 || '—');
                                $('#mdatos-padre-email').val(a.padre.email || '—');
                                $('#mdatos-padre-profesion').val(a.padre.profesion || '—');
                                $('#mdatos-padre-trabajo').val(a.padre.lugartrabajo || '—');
                            }
                            if (a.encargado) {
                                $('#mdatos-encargado-nombre').val(a.encargado.nombre || '—');
                                $('#mdatos-encargado-cid').val(a.encargado.cid || '—');
                                $('#mdatos-encargado-tel').val(a.encargado.telefono1 || '—');
                                $('#mdatos-encargado-email').val(a.encargado.email || '—');
                            }

                            // ── Tab Faltas ──
                            if (a.faltas && a.faltas.length > 0) {
                                let htmlFaltas = '';
                                a.faltas.forEach(f => {
                                    htmlFaltas += `<tr>
                                        <td>${f.fecha ? f.fecha.substring(0, 10).split('-').reverse().join('/') : '—'}</td>
                                        <td>${f.indicador_falta?.indicador_falta || '—'}</td>
                                        <td>${f.asignatura?.asignatura || '—'}</td>
                                        <td>${f.docente || '—'}</td>
                                    </tr>`;
                                });
                                $('#masist-faltas-body').html(htmlFaltas);
                            } else {
                                $('#masist-faltas-body').html('<tr><td colspan="4" class="text-center text-muted">Sin faltas registradas.</td></tr>');
                            }
                        }

                        // ── Tab Inscripciones ──
                        if (res.inscripciones && res.inscripciones.length > 0) {
                            let htmlIns = '';
                            res.inscripciones.forEach(i => {
                                htmlIns += `<tr>
                                    <td>${i.anio_lectivo}</td>
                                    <td>${i.fecha ? i.fecha.substring(0, 10).split('-').reverse().join('/') : '—'}</td>
                                    <td>${i.grado?.gradocurso || '—'}</td>
                                    <td>${i.firmante_nombre || '—'}</td>
                                    <td><span class="badge ${i.estado === 'Matriculado' ? 'bg-success' : 'bg-secondary'}"
                                        style="font-size:0.65rem;">${i.estado || '—'}</span></td>
                                </tr>`;
                            });
                            $('#masist-inscripciones-body').html(htmlIns);
                        } else {
                            $('#masist-inscripciones-body').html('<tr><td colspan="4" class="text-center text-muted">Sin inscripciones.</td></tr>');
                        }

                        // ── Tab Asistencia — 10 calendarios ──
                        const NOMBRES_MES = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        const DIAS_H = ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'];

                        const mesesTodos = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
                        const promesas = mesesTodos.map(m =>
                            $.get(`${URL_POR_ALUMNO}/${alumnoId}/por-alumno`, { mes: m, anio: anio })
                                .then(r => ({ mes: m, asistencias: r.asistencias || [], feriados: r.feriados || [] }))
                                .catch(() => ({ mes: m, asistencias: [], feriados: [] }))
                        );

                        $('#masist-calendario').html('<p class="text-muted text-center py-2">Cargando calendarios...</p>');

                        $.when(...promesas).done(function (...resultados) {
                            const data = mesesTodos.map((m, i) => {
                                const raw = promesas.length === 1 ? resultados[0] : resultados[i];
                                const obj = Array.isArray(raw) ? raw[0] : raw;
                                return { mes: m, asistencias: obj?.asistencias || [], feriados: obj?.feriados || [] };
                            });

                            let htmlWrap = '<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:8px;padding:4px;">';

                            data.forEach(function (item) {
                                const m = item.mes;
                                const dMap = {};
                                (item.asistencias || []).forEach(a => { dMap[a.dia] = a.estado; });

                                const feriadosMes = item.feriados || [];
                                const diasEnMes = new Date(anio, m, 0).getDate();
                                const primerDia = new Date(anio, m - 1, 1).getDay();

                                let cp = 0, ca = 0, cj = 0, ct = 0;

                                htmlWrap += `<div style="border:1px solid #dee2e6;border-radius:8px;padding:6px 4px 4px;">`;
                                htmlWrap += `<div style="font-size:0.65rem;font-weight:700;text-align:center;margin-bottom:4px;color:#495057;text-transform:uppercase;">${NOMBRES_MES[m]}</div>`;
                                htmlWrap += `<div style="display:grid;grid-template-columns:repeat(7,1fr);gap:1px;font-size:0.55rem;">`;

                                DIAS_H.forEach(d => {
                                    htmlWrap += `<div style="text-align:center;color:#adb5bd;font-weight:600;padding-bottom:2px;">${d}</div>`;
                                });

                                for (let i = 0; i < primerDia; i++) {
                                    htmlWrap += `<div></div>`;
                                }

                                for (let d = 1; d <= diasEnMes; d++) {
                                    const diaSem = new Date(anio, m - 1, d).getDay();
                                    const esFinde = diaSem === 0 || diaSem === 6;
                                    const fechaStr = `${anio}-${String(m).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                                    const esFeriado = feriadosMes.includes(fechaStr);
                                    const estado = dMap[d] || '';

                                    let bg = '#fff', color = '#212529', fw = 'normal';
                                    if (esFinde) { bg = '#f8f9fa'; color = '#dee2e6'; }
                                    else if (esFeriado) { bg = '#e9ecef'; color = '#adb5bd'; }
                                    if (estado === 'Presente') { bg = '#198754'; color = '#fff'; fw = 'bold'; cp++; }
                                    else if (estado === 'Ausente') { bg = '#dc3545'; color = '#fff'; fw = 'bold'; ca++; }
                                    else if (estado === 'Justificado') { bg = '#ffc107'; color = '#212529'; fw = 'bold'; cj++; }
                                    else if (estado === 'Tardanza') { bg = '#0dcaf0'; color = '#212529'; fw = 'bold'; ct++; }

                                    htmlWrap += `<div style="text-align:center;padding:2px 0;border-radius:50%;width:18px;height:18px;line-height:18px;margin:0 auto;background:${bg};color:${color};font-weight:${fw};" title="${estado || ''}">${esFinde ? '' : d}</div>`;
                                }

                                htmlWrap += `</div>`;
                                htmlWrap += `<div style="display:flex;gap:3px;margin-top:4px;font-size:0.55rem;flex-wrap:wrap;justify-content:center;">`;
                                if (cp) htmlWrap += `<span style="background:#d1e7dd;color:#0a3622;border-radius:4px;padding:1px 4px;">P ${cp}</span>`;
                                if (ca) htmlWrap += `<span style="background:#f8d7da;color:#58151c;border-radius:4px;padding:1px 4px;">A ${ca}</span>`;
                                if (cj) htmlWrap += `<span style="background:#fff3cd;color:#664d03;border-radius:4px;padding:1px 4px;">J ${cj}</span>`;
                                if (ct) htmlWrap += `<span style="background:#cff4fc;color:#055160;border-radius:4px;padding:1px 4px;">T ${ct}</span>`;
                                if (!cp && !ca && !cj && !ct) htmlWrap += `<span style="color:#adb5bd;">sin datos</span>`;
                                htmlWrap += `</div></div>`;
                            });

                            htmlWrap += '</div>';
                            $('#masist-calendario').html(htmlWrap);
                        });

                        // ── Tab Resumen Anual ──
                        let porMes = {};
                        for (let m = 1; m <= 12; m++) { porMes[m] = { Presente: 0, Ausente: 0, Justificado: 0, Tardanza: 0 }; }
                        if (res.resumen_anio) {
                            res.resumen_anio.forEach(row => {
                                if (porMes[row.mes]) porMes[row.mes][row.estado] = row.total;
                            });
                        }
                        let htmlRes = `<table class="table table-sm table-striped table-hover m-0" style="font-size:0.72rem;">
                                        <thead class="table-light">
                                            <tr><th>Mes</th><th class="text-center text-success">P</th><th class="text-center text-danger">A</th><th class="text-center text-warning">J</th><th class="text-center text-info">T</th></tr>
                                        </thead><tbody>`;
                        for (let m = 1; m <= 12; m++) {
                            htmlRes += `<tr>
                                <td>${nombreMes(m)}</td>
                                <td class="text-center fw-bold text-success">${porMes[m].Presente || 0}</td>
                                <td class="text-center fw-bold text-danger">${porMes[m].Ausente || 0}</td>
                                <td class="text-center fw-bold text-warning">${porMes[m].Justificado || 0}</td>
                                <td class="text-center fw-bold text-info">${porMes[m].Tardanza || 0}</td>
                            </tr>`;
                        }
                        htmlRes += '</tbody></table>';
                        $('#masist-resumen').html(htmlRes);
                    },
                    error: function () {
                        $('#masist-calendario').html('<p class="text-danger text-center py-2">Error al cargar datos.</p>');
                    }
                });
            }

            function nombreMes(m) {
                const n = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                return n[parseInt(m)] || '';
            }

            // Flechas de navegación del modal
            $('#masist-btn-prev').on('click', function () {
                const aId = parseInt($('#masist_alumno_id').val());
                if (idsPermitidos.includes(aId)) {
                    _modalMes--;
                    if (_modalMes < 1) { _modalMes = 12; _modalAnio--; }
                    $('#masist_mes').val(_modalMes);
                    $('#masist_anio').val(_modalAnio);
                    cargarCalendarioModal(aId, _modalMes, _modalAnio);
                }
            });

            $('#masist-btn-next').on('click', function () {
                const aId = parseInt($('#masist_alumno_id').val());
                if (idsPermitidos.includes(aId)) {
                    _modalMes++;
                    if (_modalMes > 12) { _modalMes = 1; _modalAnio++; }
                    $('#masist_mes').val(_modalMes);
                    $('#masist_anio').val(_modalAnio);
                    cargarCalendarioModal(aId, _modalMes, _modalAnio);
                }
            });
        });
    </script>
@endsection