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
                                        $gradoNombre = $inscripcion && $inscripcion->gradoCurso
                                            ? $inscripcion->gradoCurso->nombre
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

    {{-- Reutilizamos los modales nativos de asistencia en modo pasivo --}}
    @include('academica.asistencia.modales')
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
                $('#mdatos-apellidos, #mdatos-nombres, #mdatos-cid, #mdatos-madre, #mdatos-padre, #mdatos-encargado').text('—');

                $.ajax({
                    url: `${URL_POR_ALUMNO}/${alumnoId}/por-alumno`,
                    method: 'GET',
                    data: { mes: mes, anio: anio },
                    success: function (res) {
                        // 1. Pestaña Datos Básicos
                        if (res.alumno) {
                            $('#mdatos-apellidos').text(res.alumno.apellidos || '—');
                            $('#mdatos-nombres').text(res.alumno.nombres || '—');
                            $('#mdatos-cid').text(res.alumno.cid ? parseInt(res.alumno.cid).toLocaleString('es-PY') : '—');
                            if (res.alumno.madre) $('#mdatos-madre').text(`${res.alumno.madre.apellidos}, ${res.alumno.madre.nombres} (Tel: ${res.alumno.madre.telefono || '—'})`);
                            if (res.alumno.padre) $('#mdatos-padre').text(`${res.alumno.padre.apellidos}, ${res.alumno.padre.nombres} (Tel: ${res.alumno.padre.telefono || '—'})`);
                            if (res.alumno.encargado) $('#mdatos-encargado').text(`${res.alumno.encargado.apellidos}, ${res.alumno.encargado.nombres} (Tel: ${res.alumno.encargado.telefono || '—'})`);

                            if (res.alumno.foto) {
                                $('#masist-foto').attr('src', `{{ asset('storage') }}/${res.alumno.foto}`);
                            } else {
                                $('#masist-foto').attr('src', `{{ asset('images/no-avatar.png') }}`);
                            }
                        }

                        // 2. Pestaña Calendario Mensual
                        let htmlCal = `<table class="table table-bordered table-sm text-center m-0" style="font-size:0.72rem;">
                                            <thead class="table-light"><tr>`;
                        for (let d = 1; d <= res.dias_mes; d++) {
                            htmlCal += `<th style="width:24px; padding:2px; font-size:0.65rem;">${d}</th>`;
                        }
                        htmlCal += `</tr></thead><tbody><tr>`;

                        for (let d = 1; d <= res.dias_mes; d++) {
                            const fechaStr = `${anio}-${String(mes).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                            let claseBg = 'bg-white text-dark';
                            let letra = '·';
                            let tooltip = '';

                            if (res.findes.includes(d)) {
                                claseBg = 'bg-light text-muted opacity-50';
                                letra = 'F';
                            } else if (res.feriados.includes(fechaStr)) {
                                claseBg = 'bg-warning-subtle text-warning fw-bold';
                                letra = 'FE';
                            }

                            if (res.asistencias && res.asistencias[fechaStr]) {
                                const ast = res.asistencias[fechaStr];
                                tooltip = ` title="${ast.estado} ${ast.observacion ? '- ' + ast.observacion : ''}"`;
                                if (ast.estado === 'Presente') { claseBg = 'bg-success text-white fw-bold'; letra = 'P'; }
                                else if (ast.estado === 'Ausente') { claseBg = 'bg-danger text-white fw-bold'; letra = 'A'; }
                                else if (ast.estado === 'Justificado') { claseBg = 'bg-warning text-dark fw-bold'; letra = 'J'; }
                                else if (ast.estado === 'Tardanza') { claseBg = 'bg-info text-dark fw-bold'; letra = 'T'; }
                            }

                            htmlCal += `<td class="${claseBg}"${tooltip} style="padding:4px 2px; cursor:default;">${letra}</td>`;
                        }
                        htmlCal += `</tr></tbody></table>`;
                        $('#masist-calendario').html(htmlCal);

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