<x-app-layout>

    <style>
        /* ── Mismos estilos base que alumnos/index ── */
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

        /* ── Grilla de asistencia ── */
        #tabla-asistencia th,
        #tabla-asistencia td {
            font-size: 0.68rem;
            padding: 2px 3px;
            vertical-align: middle;
            white-space: nowrap;
        }

        #tabla-asistencia th.dia-col {
            width: 22px;
            text-align: center;
        }

        .btn-asist {
            width: 22px;
            height: 22px;
            padding: 0;
            font-size: 0.6rem;
            border-radius: 50%;
            line-height: 1;
            border: 1px solid #ccc;
            background: #f8f9fa;
            color: #6c757d;
            cursor: pointer;
            transition: background 0.15s;
        }

        .btn-asist.presente {
            background: #198754;
            color: #fff;
            border-color: #198754;
        }

        .btn-asist.ausente {
            background: #dc3545;
            color: #fff;
            border-color: #dc3545;
        }

        .btn-asist.justif {
            background: #ffc107;
            color: #000;
            border-color: #ffc107;
        }

        .btn-asist.tardanza {
            background: #0dcaf0;
            color: #000;
            border-color: #0dcaf0;
        }

        .btn-asist.feriado {
            background: #e9ecef;
            color: #adb5bd;
            border-color: #dee2e6;
            cursor: default;
        }

        /* Nombre alumno: columna fija */
        #tabla-asistencia th:first-child,
        #tabla-asistencia td:first-child {
            position: sticky;
            left: 0;
            background: #fff;
            z-index: 2;
            min-width: 160px;
        }

        #tabla-asistencia thead th:first-child {
            z-index: 3;
            background: #f8f9fa;
        }

        .table-responsive-asist {
            overflow-x: auto;
            max-height: 68vh;
            overflow-y: auto;
        }

        /* Totales */
        td.total-p {
            color: #198754;
            font-weight: 600;
        }

        td.total-a {
            color: #dc3545;
            font-weight: 600;
        }

        /* Leyenda */
        .leyenda-dot {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 3px;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Asistencia Diaria</h2>
    </x-slot>

    <div class="card card-body p-2 shadow-sm">

        {{-- ── Barra de filtros ── --}}
        <form method="GET" action="{{ route('asistencias.index') }}" class="row g-2 align-items-end mb-2">
            <div class="col-auto">
                <label class="form-label mb-0 fw-bold" style="font-size:0.72rem">Grado / Curso</label>
                <select name="grado_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach($grados as $g)
                        <option value="{{ $g->id }}" {{ $g->id == $selectedGradoId ? 'selected' : '' }}>
                            {{ $g->gradocurso }} ({{ $g->turno }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <label class="form-label mb-0 fw-bold" style="font-size:0.72rem">Mes</label>
                <select name="mes" class="form-select form-select-sm" onchange="this.form.submit()">
                    @php
                        $meses = [
                            '1' => 'Enero',
                            '2' => 'Febrero',
                            '3' => 'Marzo',
                            '4' => 'Abril',
                            '5' => 'Mayo',
                            '6' => 'Junio',
                            '7' => 'Julio',
                            '8' => 'Agosto',
                            '9' => 'Septiembre',
                            '10' => 'Octubre',
                            '11' => 'Noviembre',
                            '12' => 'Diciembre'
                        ];
                    @endphp
                    @foreach($meses as $num => $nombre)
                        <option value="{{ $num }}" {{ $num == $selectedMes ? 'selected' : '' }}>{{ $nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <label class="form-label mb-0 fw-bold" style="font-size:0.72rem">Año</label>
                <select name="anio" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach($anios as $a)
                        <option value="{{ $a }}" {{ $a == $selectedAnio ? 'selected' : '' }}>{{ $a }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto ms-auto d-flex align-items-end gap-2">
                {{-- Leyenda --}}
                <span style="font-size:0.65rem; color:#6c757d;">
                    <span class="leyenda-dot" style="background:#198754"></span>Presente
                    <span class="leyenda-dot ms-1" style="background:#dc3545"></span>Ausente
                    <span class="leyenda-dot ms-1" style="background:#ffc107"></span>Justif.
                    <span class="leyenda-dot ms-1" style="background:#0dcaf0"></span>Tard.
                </span>
                <button type="button" id="btn-guardar-grilla" class="btn btn-success btn-sm" style="font-size:0.7rem;">
                    <i class="fas fa-save me-1"></i>Guardar asistencia
                </button>
            </div>
        </form>

        {{-- ── Tabla grilla ── --}}
        @if($inscripciones->isEmpty())
            <div class="alert alert-warning py-1" style="font-size:0.8rem;">
                No hay alumnos matriculados para el grado y año seleccionados.
            </div>
        @else
            <div class="table-responsive-asist">
                <table id="tabla-asistencia" class="table table-sm table-bordered table-hover mb-0">
                    <thead class="table-light" style="position: sticky; top:0; z-index:4;">
                        <tr>
                            <th>Alumno/a</th>
                            @for($d = 1; $d <= $diasMes; $d++)
                                @php
                                    $fecha = \Carbon\Carbon::create($selectedAnio, $selectedMes, $d);
                                    $esFinde = $fecha->isWeekend();
                                @endphp
                                @if(!$esFinde)
                                    <th class="dia-col text-center" title="{{ $fecha->translatedFormat('l d/m') }}">
                                        {{ $d }}<br>
                                        <span style="font-size:0.55rem;font-weight:400">
                                            {{ $fecha->translatedFormat('D') }}
                                        </span>
                                    </th>
                                @endif
                            @endfor
                            <th class="text-center" title="Presentes">P</th>
                            <th class="text-center" title="Ausentes">A</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inscripciones as $ins)
                            @php
                                $alumno = $ins->alumno;
                                $insAsist = $asistencias->get($ins->id, collect()); // [ dia => Asistencia ]
                                $totalP = $insAsist->where('estado', 'Presente')->count();
                                $totalA = $insAsist->whereIn('estado', ['Ausente'])->count();
                            @endphp
                            <tr>
                                <td>
                                    <a href="#" class="btn-ver-alumno text-decoration-none text-dark" style="font-size:0.68rem"
                                        data-id="{{ $alumno->id }}"
                                        data-nombre="{{ $alumno->apellidos }}, {{ $alumno->nombres }}"
                                        data-inscripcion="{{ $ins->id }}" title="Ver ficha de {{ $alumno->nombres }}">
                                        {{ $alumno->apellidos }}, {{ $alumno->nombres }}
                                    </a>
                                </td>

                                @for($d = 1; $d <= $diasMes; $d++)
                                    @php
                                        $fecha = \Carbon\Carbon::create($selectedAnio, $selectedMes, $d);
                                        $esFinde = $fecha->isWeekend();
                                        $esFer = in_array($fecha->format('Y-m-d'), $feriadosPY);
                                        $registro = $insAsist->get($d);
                                        $estado = $registro?->estado ?? '';
                                        $estadoCss = match ($estado) {
                                            'Presente' => 'presente',
                                            'Ausente' => 'ausente',
                                            'Justificado' => 'justif',
                                            'Tardanza' => 'tardanza',
                                            default => '',
                                        };
                                    @endphp
                                    @if(!$esFinde)
                                        <td class="text-center">
                                            @if($esFer)
                                                <button class="btn-asist feriado" disabled title="Feriado">F</button>
                                            @else
                                                <button class="btn-asist {{ $estadoCss }}" data-inscripcion="{{ $ins->id }}"
                                                    data-fecha="{{ $fecha->format('Y-m-d') }}" data-estado="{{ $estado }}"
                                                    title="{{ $fecha->format('d/m') }} — {{ $estado ?: 'Sin marcar' }}">
                                                    {{ $estado ? strtoupper(substr($estado, 0, 1)) : '·' }}
                                                </button>
                                            @endif
                                        </td>
                                    @endif
                                @endfor

                                <td class="text-center total-p">{{ $totalP }}</td>
                                <td class="text-center total-a">{{ $totalA }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ── Modales ── --}}
    @include('academica.asistencia.modales')

    <script>
        window.onload = function () {

            if (!window.jQuery) {
                alert('Error crítico: jQuery no se ha cargado.');
                return;
            }

            const CSRF = '{{ csrf_token() }}';
            const URL_STORE = '{{ route("asistencias.store") }}';
            const URL_GRILLA = '{{ route("asistencias.guardarGrilla") }}';
            const URL_ALUMNO = '{{ url("academica/asistencias") }}';

            // ── Mapa en memoria: { "inscripcion_id|fecha" : estado } ──
            // Se construye desde el HTML al cargar la página
            const mapaAsistencia = {};

            $('.btn-asist:not(.finde):not(.feriado)').each(function () {
                const ins = $(this).data('inscripcion');
                const fecha = $(this).data('fecha');
                const est = $(this).data('estado') || '';
                mapaAsistencia[`${ins}|${fecha}`] = est;
            });

            // ── Ciclo de estados al hacer click ──
            const ciclo = ['', 'Presente', 'Ausente', 'Justificado', 'Tardanza'];
            const cssMap = {
                '': '', 'Presente': 'presente', 'Ausente': 'ausente',
                'Justificado': 'justif', 'Tardanza': 'tardanza'
            };
            const labelMap = {
                '': '·', 'Presente': 'P', 'Ausente': 'A',
                'Justificado': 'J', 'Tardanza': 'T'
            };

            $(document).on('click', '.btn-asist:not(.finde):not(.feriado)', function () {
                const $btn = $(this);
                const ins = $btn.data('inscripcion');
                const fecha = $btn.data('fecha');
                const key = `${ins}|${fecha}`;

                const actual = mapaAsistencia[key] || '';
                const idx = ciclo.indexOf(actual);
                const nuevo = ciclo[(idx + 1) % ciclo.length];

                // Actualizar en memoria y en el DOM inmediatamente (sin esperar AJAX)
                mapaAsistencia[key] = nuevo;
                $btn.removeClass('presente ausente justif tardanza')
                    .addClass(cssMap[nuevo])
                    .attr('data-estado', nuevo)
                    .text(labelMap[nuevo]);

                // Guardar registro individual vía AJAX
                if (nuevo !== '') {
                    $.ajax({
                        url: URL_STORE,
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': CSRF },
                        data: { inscripcion_id: ins, fecha: fecha, estado: nuevo },
                        error: function () {
                            // Revertir visualmente si falla
                            mapaAsistencia[key] = actual;
                            $btn.removeClass('presente ausente justif tardanza')
                                .addClass(cssMap[actual])
                                .attr('data-estado', actual)
                                .text(labelMap[actual]);
                            alert('Error al guardar. Intente de nuevo.');
                        }
                    });
                }
            });

            // ── Botón guardar grilla completa ──
            $('#btn-guardar-grilla').on('click', function () {
                const registros = [];
                $('.btn-asist:not(.finde):not(.feriado)').each(function () {
                    // Usamos .attr() en lugar de .data() para capturar los cambios dinámicos del DOM
                    const est = $(this).attr('data-estado') || '';
                    if (est !== '') {
                        registros.push({
                            inscripcion_id: $(this).attr('data-inscripcion'),
                            fecha: $(this).attr('data-fecha'),
                            estado: est,
                        });
                    }
                });

                if (registros.length === 0) {
                    Swal.fire('Sin cambios', 'No hay asistencias marcadas para guardar.', 'info');
                    return;
                }

                $.ajax({
                    url: URL_GRILLA,
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF },
                    contentType: 'application/json',
                    data: JSON.stringify({ registros: registros }),
                    success: function (res) {
                        Swal.fire('Guardado', `${res.total} registros guardados correctamente.`, 'success');
                    },
                    error: function () {
                        Swal.fire('Error', 'No se pudo guardar la asistencia.', 'error');
                    }
                });
            });

            // ── Abrir modal de ficha de alumno ──
            $(document).on('click', '.btn-ver-alumno', function (e) {
                e.preventDefault();
                const alumnoId = $(this).data('id');
                const nombre = $(this).data('nombre');
                const inscripcion = $(this).data('inscripcion');

                $('#modal-alumno-nombre').text(nombre);
                // Mostrar primer tab
                new bootstrap.Tab(document.querySelector('#modalAlumnoAsistTabs button:first-child')).show();
                // Cargar detalles vía AJAX (mismo endpoint que ya existe)
                cargarDetallesAlumno(alumnoId, inscripcion);
                new bootstrap.Modal(document.getElementById('modalAlumnoAsist')).show();
            });

            // ── Función para cargar detalles del alumno en el modal ──
            function cargarDetallesAlumno(alumnoId, inscripcionId) {
                // Tab Datos — reutiliza el endpoint existente
                $.get(`{{ url('academica/alumnos') }}/${alumnoId}/detalles`)
                    .done(function (res) {
                        $('#masist_madre').val(res.madre ? res.madre.nombre : 'No registrado');
                        $('#masist_padre').val(res.padre ? res.padre.nombre : 'No registrado');
                        // Inscripciones
                        let html = '';
                        (res.inscripciones || []).forEach(i => {
                            html += `<tr>
                            <td>${i.anio_lectivo}</td>
                            <td>${i.grado_curso}</td>
                            <td>${i.estado}</td>
                        </tr>`;
                        });
                        $('#masist_inscripciones').html(html || '<tr><td colspan="3" class="text-muted text-center">Sin historial</td></tr>');
                    });

                // Tab Asistencia — mes actual por defecto
                cargarCalendarioModal(alumnoId, '{{ $selectedMes }}', '{{ $selectedAnio }}');
                $('#masist_alumno_id').val(alumnoId);
            }

            // ── Cargar calendario de asistencia en el modal ──
            window.cargarCalendarioModal = function (alumnoId, mes, anio) {
                $('#masist-cal-titulo').text(nombreMes(mes) + ' ' + anio);
                $('#masist-cal-container').html('<p class="text-muted text-center py-2">Cargando...</p>');

                $.get(`${URL_ALUMNO}/${alumnoId}/por-alumno`, { mes: mes, anio: anio })
                    .done(function (res) {
                        construirCalendario(res.asistencias, mes, anio);
                        construirResumenAnio(res.resumen_anio);
                    })
                    .fail(function () {
                        $('#masist-cal-container').html('<p class="text-danger text-center">Error al cargar.</p>');
                    });
            };

            function construirCalendario(asistencias, mes, anio) {
                // Mapa día => {estado, badge}
                const diaMap = {};
                asistencias.forEach(a => { diaMap[a.dia] = a; });

                const diasEnMes = new Date(anio, mes, 0).getDate();
                const primerDia = new Date(anio, mes - 1, 1).getDay(); // 0=dom
                const feriados = @json($feriadosPY);

                const dias = ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'];
                let html = '<div style="display:grid;grid-template-columns:repeat(7,1fr);gap:2px;">';
                dias.forEach(d => {
                    html += `<div style="text-align:center;font-size:0.6rem;font-weight:600;color:#6c757d;padding-bottom:2px">${d}</div>`;
                });

                // Celdas vacías antes del primer día
                for (let i = 0; i < primerDia; i++) {
                    html += '<div></div>';
                }

                for (let d = 1; d <= diasEnMes; d++) {
                    const fecha = `${anio}-${String(mes).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                    const diaSem = new Date(anio, mes - 1, d).getDay();
                    const esFinde = diaSem === 0 || diaSem === 6;
                    const esFer = feriados.includes(fecha);
                    const reg = diaMap[d];

                    let bg = '#f8f9fa', color = '#adb5bd', title = '';
                    if (esFinde) { bg = 'transparent'; color = '#dee2e6'; }
                    else if (esFer) { bg = '#e9ecef'; color = '#adb5bd'; title = 'Feriado'; }
                    else if (reg) {
                        if (reg.estado === 'Presente') { bg = '#198754'; color = '#fff'; }
                        else if (reg.estado === 'Ausente') { bg = '#dc3545'; color = '#fff'; }
                        else if (reg.estado === 'Justificado') { bg = '#ffc107'; color = '#000'; }
                        else if (reg.estado === 'Tardanza') { bg = '#0dcaf0'; color = '#000'; }
                        title = reg.estado;
                    }

                    html += `<div style="width:26px;height:26px;border-radius:50%;background:${bg};color:${color};
                              display:flex;align-items:center;justify-content:center;
                              font-size:0.65rem;font-weight:500;margin:0 auto;"
                              title="${title}">${d}</div>`;
                }
                html += '</div>';
                $('#masist-cal-container').html(html);
            }

            function construirResumenAnio(resumen) {
                if (!resumen || resumen.length === 0) {
                    $('#masist-resumen').html('<p class="text-muted" style="font-size:0.7rem">Sin datos.</p>');
                    return;
                }
                // Agrupar por mes
                const porMes = {};
                resumen.forEach(r => {
                    if (!porMes[r.mes]) porMes[r.mes] = { Presente: 0, Ausente: 0, Justificado: 0, Tardanza: 0 };
                    porMes[r.mes][r.estado] = r.total;
                });
                let html = '<table class="table table-sm table-bordered" style="font-size:0.68rem"><thead class="table-light"><tr><th>Mes</th><th class="text-success">P</th><th class="text-danger">A</th><th class="text-warning">J</th><th class="text-info">T</th></tr></thead><tbody>';
                for (let m = 1; m <= 12; m++) {
                    if (!porMes[m]) continue;
                    html += `<tr><td>${nombreMes(m)}</td>
                    <td class="text-center">${porMes[m].Presente || 0}</td>
                    <td class="text-center">${porMes[m].Ausente || 0}</td>
                    <td class="text-center">${porMes[m].Justificado || 0}</td>
                    <td class="text-center">${porMes[m].Tardanza || 0}</td></tr>`;
                }
                html += '</tbody></table>';
                $('#masist-resumen').html(html);
            }

            function nombreMes(m) {
                const n = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                return n[parseInt(m)] || '';
            }

            // ── Navegación de meses dentro del modal ──
            let _modalAlumnoId = null;
            let _modalMes = parseInt('{{ $selectedMes }}');
            let _modalAnio = parseInt('{{ $selectedAnio }}');

            $('#modalAlumnoAsist').on('show.bs.modal', function () {
                _modalMes = parseInt('{{ $selectedMes }}');
                _modalAnio = parseInt('{{ $selectedAnio }}');
            });

            $('#masist-btn-prev').on('click', function () {
                _modalMes--;
                if (_modalMes < 1) { _modalMes = 12; _modalAnio--; }
                cargarCalendarioModal($('#masist_alumno_id').val(), _modalMes, _modalAnio);
            });

            $('#masist-btn-next').on('click', function () {
                _modalMes++;
                if (_modalMes > 12) { _modalMes = 1; _modalAnio++; }
                cargarCalendarioModal($('#masist_alumno_id').val(), _modalMes, _modalAnio);
            });

        }; // end window.onload
    </script>

</x-app-layout>