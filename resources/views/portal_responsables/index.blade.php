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
        <div class="modal-dialog modal-xl" style="max-width: 75vw;">
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
                    </ul>

                    <div class="tab-content pt-2">

                        {{-- Tab 1: Datos básicos --}}
                        <div class="tab-pane fade show active" id="masist-tab-datos">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-3 text-center">
                                    <img id="masist-foto" src="" alt="Foto" class="rounded-circle shadow"
                                        style="width:90px;height:90px;object-fit:cover;">
                                </div>
                                <div class="col-md-9">
                                    <div class="row g-1">
                                        <div class="col-md-6">
                                            <label class="form-label mb-0 fw-bold"
                                                style="font-size:0.72rem">Apellidos</label>
                                            <input type="text" id="mdatos-apellidos"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label mb-0 fw-bold" style="font-size:0.72rem">Nombres</label>
                                            <input type="text" id="mdatos-nombres"
                                                class="form-control form-control-sm bg-light" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label mb-0 fw-bold" style="font-size:0.72rem">Cédula</label>
                                            <input type="text" id="mdatos-cid" class="form-control form-control-sm bg-light"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label class="form-label mb-0 fw-bold" style="font-size:0.72rem">Madre / Tutora</label>
                                    <input type="text" id="mdatos-madre" class="form-control form-control-sm bg-light"
                                        readonly>
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label class="form-label mb-0 fw-bold" style="font-size:0.72rem">Padre / Tutor</label>
                                    <input type="text" id="mdatos-padre" class="form-control form-control-sm bg-light"
                                        readonly>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label mb-0 fw-bold" style="font-size:0.72rem">Encargado</label>
                                    <input type="text" id="mdatos-encargado" class="form-control form-control-sm bg-light"
                                        readonly>
                                </div>
                            </div>
                        </div>

                        {{-- Tab 2: Asistencia mensual --}}
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

                        {{-- Tab 3: Resumen anual --}}
                        <div class="tab-pane fade" id="masist-tab-resumen">
                            <div id="masist-resumen">
                                <p class="text-muted text-center py-2">Cargando...</p>
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
                        // 1. Pestaña Datos Básicos
                        if (res.alumno) {
                            $('#mdatos-apellidos').val(res.alumno.apellidos || '—');
                            $('#mdatos-nombres').val(res.alumno.nombres || '—');
                            $('#mdatos-cid').val(res.alumno.cid ? parseInt(res.alumno.cid).toLocaleString('es-PY') : '—');
                            if (res.alumno.madre) $('#mdatos-madre').val(`${res.alumno.madre.nombre} (Tel: ${res.alumno.madre.telefono1 || '—'})`);
                            if (res.alumno.padre) $('#mdatos-padre').val(`${res.alumno.padre.nombre} (Tel: ${res.alumno.padre.telefono1 || '—'})`);
                            if (res.alumno.encargado) $('#mdatos-encargado').val(`${res.alumno.encargado.nombre} (Tel: ${res.alumno.encargado.telefono1 || '—'})`);

                            if (res.alumno.foto) {
                                $('#masist-foto').attr('src', `{{ asset('img/alumnos') }}/${res.alumno.foto}`);
                            } else {
                                $('#masist-foto').attr('src', `{{ asset('images/no-avatar.png') }}`);
                            }
                        }

                        // 2. Pestaña Asistencia — 10 calendarios mensuales (Feb–Nov) en cuadrícula
                        const NOMBRES_MES = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        const DIAS_H = ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'];

                        // Convertir array de asistencias a mapa { "YYYY-MM-DD": estado }
                        const mapaAsist = {};
                        (res.asistencias || []).forEach(a => {
                            // a.dia es número, reconstruimos la fecha con el mes/anio actuales
                            const fechaKey = `${anio}-${String(mes).padStart(2, '0')}-${String(a.dia).padStart(2, '0')}`;
                            mapaAsist[fechaKey] = a.estado;
                        });

                        // Pedir los demás meses en paralelo (todos excepto el actual)
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

                            let htmlWrap = '<div style="display:flex;flex-wrap:wrap;gap:10px;padding:4px;">';

                            data.forEach(function (item) {
                                const m = item.mes;
                                const dMap = {};
                                (item.asistencias || []).forEach(a => { dMap[a.dia] = a.estado; });

                                const feriadosMes = item.feriados || [];
                                const diasEnMes = new Date(anio, m, 0).getDate();
                                const primerDia = new Date(anio, m - 1, 1).getDay();

                                let cp = 0, ca = 0, cj = 0, ct = 0;

                                htmlWrap += `<div style="border:1px solid #dee2e6;border-radius:8px;padding:6px;min-width:180px;flex:1 1 180px;">`;
                                htmlWrap += `<div style="font-size:0.7rem;font-weight:bold;text-align:center;margin-bottom:4px;color:#495057;">${NOMBRES_MES[m]}</div>`;
                                htmlWrap += `<div style="display:grid;grid-template-columns:repeat(7,1fr);gap:1px;font-size:0.6rem;">`;

                                DIAS_H.forEach(d => {
                                    htmlWrap += `<div style="text-align:center;color:#6c757d;font-weight:bold;padding:1px;">${d}</div>`;
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
                                    if (esFinde) { bg = '#f8f9fa'; color = '#adb5bd'; }
                                    else if (esFeriado) { bg = '#fff3cd'; color = '#856404'; }
                                    if (estado === 'Presente') { bg = '#198754'; color = '#fff'; fw = 'bold'; cp++; }
                                    else if (estado === 'Ausente') { bg = '#dc3545'; color = '#fff'; fw = 'bold'; ca++; }
                                    else if (estado === 'Justificado') { bg = '#ffc107'; color = '#212529'; fw = 'bold'; cj++; }
                                    else if (estado === 'Tardanza') { bg = '#0dcaf0'; color = '#212529'; fw = 'bold'; ct++; }

                                    htmlWrap += `<div style="text-align:center;padding:2px 1px;border-radius:3px;background:${bg};color:${color};font-weight:${fw};" title="${estado || ''}">${esFinde ? '' : d}</div>`;
                                }

                                htmlWrap += `</div>`; // grid

                                // Resumen del mes
                                htmlWrap += `<div style="display:flex;gap:4px;margin-top:4px;font-size:0.58rem;flex-wrap:wrap;">`;
                                if (cp) htmlWrap += `<span style="background:#198754;color:#fff;border-radius:4px;padding:1px 4px;">P ${cp}</span>`;
                                if (ca) htmlWrap += `<span style="background:#dc3545;color:#fff;border-radius:4px;padding:1px 4px;">A ${ca}</span>`;
                                if (cj) htmlWrap += `<span style="background:#ffc107;color:#212529;border-radius:4px;padding:1px 4px;">J ${cj}</span>`;
                                if (ct) htmlWrap += `<span style="background:#0dcaf0;color:#212529;border-radius:4px;padding:1px 4px;">T ${ct}</span>`;
                                if (!cp && !ca && !cj && !ct) htmlWrap += `<span style="color:#adb5bd;">sin datos</span>`;
                                htmlWrap += `</div>`;

                                htmlWrap += `</div>`; // card mes
                            });

                            htmlWrap += '</div>';
                            $('#masist-calendario').html(htmlWrap);
                        });

                        // 3. Pestaña Resumen Anual
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