<x-app-layout>

    <!-- CNDs específicos para DataTables Buttons -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

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
        select[name="tabla-alumnos_length"] {
            border-radius: 5px !important;
        }

        /* Switch Activo/Matriculado */
        .toggle {
            position: relative;
            display: inline-block;
            width: 38px;
            height: 22px;
            margin-top: 4px;
        }

        .toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .3s;
            border-radius: 22px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .3s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #198754;
            /* verde = al día */
        }

        input:not(:checked)+.slider {
            background-color: #dc3545;
            /* rojo = no al día */
        }

        input:checked+.slider:before {
            transform: translateX(16px);
        }

        .dt-buttons .btn {
            font-size: 0.75rem;
            margin-right: 5px;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Alumnos por Grado/Curso</h2>
    </x-slot>

    <div class="card card-body p-2 shadow-sm mb-3">
        <form method="GET" action="{{ route('academica.alumnos-grado') }}" id="formFiltro">
            <div class="row align-items-center">
                <div class="col-md-auto">
                    <label for="grado_id" class="col-form-label col-form-label-sm fw-bold">Seleccionar
                        Grado/Curso:</label>
                </div>
                <div class="col-md-4">
                    <select name="grado_id" id="grado_id" class="form-select form-select-sm"
                        onchange="document.getElementById('formFiltro').submit();">
                        @foreach($grados as $g)
                            <option value="{{ $g->id }}" {{ $selectedGradoId == $g->id ? 'selected' : '' }}>
                                {{ $g->gradocurso }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>

    @if($selectedGrado)
        <div class="card card-body p-2 shadow-sm">
            <div class="d-flex justify-content-between mb-2">
                <h6 class="fw-bold text-secondary">Nómina de alumnos del {{ $selectedGrado->gradocurso }}</h6>
            </div>

            <table id="tabla-alumnos" class="table table-sm table-hover table-bordered table-xs">
                <thead class="table-light">
                    <tr>
                        <th width="40" class="text-center">N°</th>
                        <th>Apellidos y Nombres</th>
                        <th>Cédula Id.</th>
                        <th>Sexo</th>
                        <th>Teléfono</th>
                        <th class="text-center">Al día</th>
                        <th width="80" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alumnos as $al)
                        <tr>
                            <td class="text-center"></td>
                            <td>{{ $al->apellidos }}, {{ $al->nombres }}</td>
                            <td>{{ $al->cid }}</td>
                            <td>{{ $al->sexo->sexo ?? 'N/A' }}</td>
                            <td>{{ $al->telefono ?? '-' }}</td>
                            <td class="text-center align-middle">
                                <label class="toggle mb-0" style="margin-top:0;">
                                    <input type="checkbox" class="toggle-estado" data-id="{{ $al->id }}" data-campo="al_dia" {{ $al->inscripciones->first()?->al_dia ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-warning btn-xs py-0 px-1 btn-editar"
                                    style="font-size: 0.65rem;" data-id="{{ $al->id }}" data-json='{{ json_encode($al) }}'>
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @include('rrhh.alumnos.modales')

    <!-- Scripts de exportación -->
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
                if (window.jQuery) {

                    // CSRF para peticiones Ajax
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    // Inicializar DataTable con botones y sin paginación
                    var t = $('#tabla-alumnos').DataTable({
                        "paging": false,
                        "order": [[1, "asc"]], // Ordenar por nombre inicialmente
                        "columnDefs": [{
                            "searchable": false,
                            "orderable": false,
                            "targets": 0
                        }],
                        "language": {
                            "search": "Buscar:",
                            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                            "infoFiltered": "(filtrado de _MAX_ registros)",
                            "zeroRecords": "No se encontraron registros",
                            "emptyTable": "No hay datos disponibles en la tabla"
                        },
                        "dom": "<'row mb-2'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                            "<'row'<'col-sm-12'tr>>" +
                            "<'row mt-2'<'col-sm-12'i>>",
                        "buttons": [
                            {
                                extend: 'excelHtml5',
                                text: '<i class="fas fa-file-excel"></i> Excel',
                                className: 'btn btn-success btn-sm',
                                title: 'Colegio Privado Santa Teresita - Luque, Paraguay',
                                messageTop: 'Nómina de alumnos del {{ $selectedGrado ? $selectedGrado->gradocurso : "" }}',
                                exportOptions: {
                                    columns: [0, 1, 2, 3]
                                }
                            },
                            {
                                extend: 'pdfHtml5',
                                text: '<i class="fas fa-file-pdf"></i> PDF',
                                className: 'btn btn-danger btn-sm',
                                title: 'Colegio Privado Santa Teresita - Luque, Paraguay',
                                messageTop: 'Nómina de alumnos del {{ $selectedGrado ? $selectedGrado->gradocurso : "" }}',
                                exportOptions: {
                                    columns: [0, 1, 2, 3]
                                },
                                customize: function (doc) {
                                    if (doc.content[0]) doc.content[0].alignment = 'center';
                                    if (doc.content[1]) {
                                        doc.content[1].alignment = 'center';
                                        doc.content[1].margin = [0, 0, 0, 15];
                                    }
                                    doc.styles.tableHeader.alignment = 'left';
                                    doc.styles.tableBodyEven.alignment = 'left';
                                    doc.styles.tableBodyOdd.alignment = 'left';
                                    if (doc.content[2] && doc.content[2].table) {
                                        // Forzar anchos proporcionales para que la tabla ocupe todo el ancho y se vea centrada
                                        doc.content[2].table.widths = ['10%', '50%', '20%', '20%'];
                                    }
                                }
                            },
                            {
                                extend: 'print',
                                text: '<i class="fas fa-print"></i> Imprimir',
                                className: 'btn btn-secondary btn-sm',
                                title: 'Colegio Privado Santa Teresita - Luque, Paraguay',
                                messageTop: 'Nómina de alumnos del {{ $selectedGrado ? $selectedGrado->gradocurso : "" }}',
                                exportOptions: {
                                    columns: [0, 1, 2, 3]
                                },
                                customize: function (win) {
                                    $(win.document.body).css('text-align', 'center');
                                    $(win.document.body).find('h1').css('text-align', 'center');
                                    $(win.document.body).find('div').css('text-align', 'center');

                                    $(win.document.body).find('table')
                                        .addClass('compact')
                                        .css('margin', '0 auto')
                                        .css('width', '90%');

                                    $(win.document.body).find('table th, table td').css('text-align', 'left');
                                }
                            }
                        ]
                    });

                    // Numeración automática de la primera columna (respetando orden y búsqueda)
                    t.on('order.dt search.dt', function () {
                        let i = 1;
                        t.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
                            this.data(i++);
                        });
                    }).draw();

                    // ── DataTable de faltas (dentro del modal, inicializada una sola vez) ──
                    var dtFaltas = $('#tabla-faltas-alumno').DataTable({
                        "order": [[1, "desc"]],
                        "pageLength": 5,
                        "lengthMenu": [5, 10, 25],
                        "columns": [
                            { "title": "ID", "width": "40px" },
                            { "title": "Fecha", "width": "85px" },
                            { "title": "Indicador" },
                            { "title": "Grado/Curso", "width": "15%" },
                            { "title": "Asignatura", "width": "20%" },
                            { "title": "Editar", "orderable": false, "className": "text-center", "width": "50px" }
                        ],
                        "autoWidth": false,
                        "language": {
                            "search": "Buscar:",
                            "lengthMenu": "Mostrar _MENU_",
                            "paginate": { "next": "›", "previous": "‹" },
                            "info": "_START_–_END_ de _TOTAL_",
                            "infoEmpty": "0 registros",
                            "zeroRecords": "Sin faltas",
                            "emptyTable": "Sin faltas registradas"
                        },
                        "dom": "<'row mb-1'<'col-sm-6'l><'col-sm-6'f>>" +
                            "<'row'<'col-sm-12'tr>>" +
                            "<'row mt-1'<'col-sm-5'i><'col-sm-7'p>>"
                    });

                    // ── DataTable de registros anecdóticos ──
                    var dtRegistros = $('#tabla-registros-alumno').DataTable({
                        "order": [[1, "desc"]],
                        "pageLength": 5,
                        "lengthMenu": [5, 10, 25],
                        "columns": [
                            { "title": "ID", "width": "40px" },
                            { "title": "Fecha", "width": "85px" },
                            { "title": "Asignatura", "width": "25%" },
                            { "title": "Detalle" },
                            { "title": "Editar", "orderable": false, "className": "text-center", "width": "50px" }
                        ],
                        "autoWidth": false,
                        "language": {
                            "search": "Buscar:",
                            "lengthMenu": "Mostrar _MENU_",
                            "paginate": { "next": "›", "previous": "‹" },
                            "info": "_START_–_END_ de _TOTAL_",
                            "infoEmpty": "0 registros",
                            "zeroRecords": "Sin registros",
                            "emptyTable": "Sin registros anecdóticos"
                        },
                        "dom": "<'row mb-1'<'col-sm-6'l><'col-sm-6'f>>" +
                            "<'row'<'col-sm-12'tr>>" +
                            "<'row mt-1'<'col-sm-5'i><'col-sm-7'p>>"
                    });

                    // ── DataTable de entrevistas ──
                    var dtEntrevistas = $('#tabla-entrevistas-alumno').DataTable({
                        "order": [[0, "desc"]],
                        "pageLength": 5,
                        "lengthMenu": [5, 10, 25],
                        "autoWidth": false,
                        "columns": [
                            { "title": "Fecha", "width": "80px" },
                            { "title": "Tipo", "width": "70px" },
                            { "title": "Atendido por", "width": "30%" },
                            { "title": "Motivo", "width": "40%" },
                            { "title": "Editar", "orderable": false, "className": "text-center", "width": "50px" }
                        ],
                        "language": {
                            "search": "Buscar:",
                            "lengthMenu": "Mostrar _MENU_",
                            "paginate": { "next": "›", "previous": "‹" },
                            "info": "_START_–_END_ de _TOTAL_",
                            "infoEmpty": "0 registros",
                            "zeroRecords": "Sin entrevistas",
                            "emptyTable": "Sin entrevistas registradas"
                        },
                        "dom": "<'row mb-1'<'col-sm-6'l><'col-sm-6'f>>" +
                            "<'row'<'col-sm-12'tr>>" +
                            "<'row mt-1'<'col-sm-5'i><'col-sm-7'p>>"
                    });

                    // ── Listener para abrir modal de edición de ALUMNO ──
                    $(document).on('click', '.btn-editar:not(.btn-editar-falta):not(.btn-editar-entrevista)', function () {
                        var d = $(this).data('json');
                        $('#formEditar').attr('action', "{{ url('rrhh/alumnos') }}/" + d.id);

                        // Reset tabs to first one
                        if (bootstrap.Tab.getInstance($('#modalAlumnoTabs button:first')[0])) {
                            bootstrap.Tab.getInstance($('#modalAlumnoTabs button:first')[0]).show();
                        } else {
                            new bootstrap.Tab($('#modalAlumnoTabs button:first')[0]).show();
                        }

                        // Mapeo dinámico de campos al modal
                        Object.keys(d).forEach(key => {
                            let el = $(`#edit_${key}`);
                            if (el.length > 0) {
                                if (el.attr('type') === 'checkbox') {
                                    el.prop('checked', d[key] === 'Sí' || d[key] === 1 || d[key] === true);
                                } else {
                                    el.val(d[key]);
                                }
                            }
                        });

                        // Foto
                        let fotoPreview = document.getElementById('preview_foto_editar');
                        if (d['foto']) {
                            fotoPreview.src = "{{ asset('img/alumnos/') }}/" + d['foto'];
                        } else {
                            fotoPreview.src = "{{ asset('img/alumnos/alumno.jpg') }}";
                        }

                        // Guardar alumno activo para tabs lazy (Asistencia y Calendario)
                        _asistAlumnoId = d.id;
                        _asistCache = {};
                        _calExAlumnoId = d.id;
                        _calExCache = {};

                        // Limpiar mientras carga
                        $('#info_madre_nombre, #info_padre_nombre, #info_encargado_nombre').val('Cargando...');
                        $('#table-inscripciones-historial').html('<tr><td colspan="7" class="text-center">Cargando...</td></tr>');
                        $('#cal-examenes-wrap').html('<div class="text-center text-muted py-3" style="font-size:0.75rem;"><div class="spinner-border spinner-border-sm me-2"></div>Cargando calendario...</div>');
                        dtFaltas.clear().draw();
                        dtRegistros.clear().draw();
                        dtEntrevistas.clear().draw();

                        // Cargar detalles vía AJAX
                        $.get("{{ url('academica/alumnos') }}/" + d.id + "/detalles")
                            .done(function (res) {
                                // Responsables
                                $('#info_madre_nombre').val(res.madre ? res.madre.nombre : 'No registrado');
                                $('#info_madre_telefono').val(res.madre ? (res.madre.telefono1 || '') : '');
                                $('#info_padre_nombre').val(res.padre ? res.padre.nombre : 'No registrado');
                                $('#info_padre_telefono').val(res.padre ? (res.padre.telefono1 || '') : '');
                                $('#info_encargado_nombre').val(res.encargado ? res.encargado.nombre : 'No registrado');
                                $('#info_encargado_telefono').val(res.encargado ? (res.encargado.telefono1 || '') : '');

                                // Historial de Inscripciones
                                let html = '';
                                if (res.inscripciones && res.inscripciones.length > 0) {
                                    res.inscripciones.forEach(ins => {
                                        let btnEditIns = `<button type="button"
                                                                                class="btn btn-warning btn-xs py-0 px-1 btn-editar-inscripcion"
                                                                                style="font-size:0.65rem;"
                                                                                data-ins='${JSON.stringify(ins)}'
                                                                                title="Editar inscripci\u00f3n">
                                                                                <i class="fas fa-edit"></i>
                                                                            </button>`;
                                        html += `<tr>
                                                                                                <td>${ins.id}</td>
                                                                                                <td>${ins.fecha}</td>
                                                                                                <td>${ins.anio_lectivo}</td>
                                                                                                <td>${ins.grado_curso}</td>
                                                                                                <td>${ins.firmante_nombre || ''}</td>
                                                                                                <td>${ins.firmante_rol || ''}</td>
                                                                                                <td>${ins.estado}</td>
                                                                                                <td class="text-center">${btnEditIns}</td>
                                                                                            </tr>`;
                                    });
                                } else {
                                    html = '<tr><td colspan="8" class="text-center text-muted">Sin historial</td></tr>';
                                }
                                $('#table-inscripciones-historial').html(html);

                                // Repoblar DataTable de Faltas
                                dtFaltas.clear();
                                if (res.faltas && res.faltas.length > 0) {
                                    res.faltas.forEach(f => {
                                        var boton = `<button type="button"
                                                                                            class="btn btn-warning btn-xs py-0 px-1 btn-editar-falta"
                                                                                            style="font-size:0.65rem;"
                                                                                            data-id="${f.id}"
                                                                                            data-fecha="${f.fecha_raw ?? ''}"
                                                                                            data-grado="${f.grado_curso_id}"
                                                                                            data-alumno="${f.alumno_id}"
                                                                                            data-asignatura="${f.asignatura_id}"
                                                                                            data-indicador="${f.indicador_falta_id}"
                                                                                            title="Editar falta">
                                                                                            <i class="fas fa-edit"></i>
                                                                                            </button>`;
                                        dtFaltas.row.add([
                                            f.id,
                                            f.fecha,
                                            f.falta,
                                            f.grado_curso,
                                            f.asignatura || '-',
                                            boton
                                        ]);
                                    });
                                }
                                dtFaltas.draw();

                                // Repoblar DataTable de Registros Anecdóticos
                                dtRegistros.clear();
                                if (res.registros_anecdoticos && res.registros_anecdoticos.length > 0) {
                                    res.registros_anecdoticos.forEach(r => {
                                        var boton = `<button type="button" class="btn btn-warning btn-xs py-0 px-1 btn-editar-registro-anecdotico"
                                                                            style="font-size:0.65rem;"
                                                                            data-id="${r.id}"
                                                                            data-fecha="${r.fecha_raw ?? ''}"
                                                                            data-grado="${r.grado_curso_id}"
                                                                            data-alumno="${r.alumno_id}"
                                                                            data-asignatura="${r.asignatura_id}"
                                                                            data-detalle="${r.detalle.replace(/"/g, '&quot;')}"
                                                                            title="Editar registro">
                                                                            <i class="fas fa-edit"></i>
                                                                         </button>`;
                                        dtRegistros.row.add([
                                            r.id,
                                            r.fecha,
                                            r.asignatura || '—',
                                            r.detalle,
                                            boton
                                        ]);
                                    });
                                }
                                dtRegistros.draw();

                                // Repoblar DataTable de Entrevistas
                                dtEntrevistas.clear();
                                if (res.entrevistas && res.entrevistas.length > 0) {
                                    res.entrevistas.forEach(e => {
                                        let badgeClass = e.tipo === 'Alumno' ? 'bg-info' : 'bg-success';
                                        let badgeHtml = `<span class="badge ${badgeClass}" style="font-size:0.6rem;">${e.tipo}</span>`;

                                        let boton = `<button type="button" class="btn btn-warning btn-xs py-0 px-1 btn-editar-entrevista"
                                                                                                        style="font-size:0.65rem;"
                                                                                                        data-id="${e.id}"
                                                                                                        data-tipo="${e.tipo}"
                                                                                                        data-fecha="${e.fecha_raw}"
                                                                                                        data-colaborador="${e.colaborador_id}"
                                                                                                        data-motivo="${e.motivo}"
                                                                                                        data-obs="${e.obs || ''}"
                                                                                                        data-testigos='${JSON.stringify(e.testigos || [])}'
                                                                                                        title="Editar entrevista">
                                                                                                        <i class="fas fa-edit"></i>
                                                                                                        </button>`;
                                        dtEntrevistas.row.add([
                                            e.fecha,
                                            badgeHtml,
                                            e.entrevistador,
                                            e.motivo,
                                            boton
                                        ]);
                                    });
                                }
                                dtEntrevistas.draw();
                            })
                            .fail(function () {
                                $('#info_madre_nombre, #info_padre_nombre, #info_encargado_nombre').val('Error al cargar');
                                $('#table-inscripciones-historial').html('<tr><td colspan="7" class="text-center text-danger">Error al cargar</td></tr>');
                                dtFaltas.clear().draw();
                                dtEntrevistas.clear().draw();
                                dtRegistros.clear().draw();
                            });

                        new bootstrap.Modal(document.getElementById('modalEditar')).show();
                    });

                    // ── Funciones auxiliares para modal Editar Falta ──
                    function faltaCargarAlumnos(gradoId, selectedId) {
                        $('#falta_editar_alumno').prop('disabled', true).html('<option>Cargando...</option>');
                        $.get("{{ url('academica/faltas/alumnos-por-grado') }}/" + gradoId, function (data) {
                            let opts = '<option value="">— Seleccionar alumno —</option>';
                            data.forEach(a => {
                                const sel = (a.id == selectedId) ? 'selected' : '';
                                opts += `<option value="${a.id}" ${sel}>${a.apellidos}, ${a.nombres}</option>`;
                            });
                            $('#falta_editar_alumno').prop('disabled', false).html(opts);
                        });
                    }

                    function faltaCargarAsignaturas(gradoId, selectedId) {
                        $('#falta_editar_asignatura').prop('disabled', true).html('<option>Cargando...</option>');
                        $('#falta_editar_docente').val('');
                        $.get("{{ url('academica/faltas/asignaturas-por-grado') }}/" + gradoId, function (data) {
                            let opts = '<option value="">— Seleccionar asignatura —</option>';
                            data.forEach(a => {
                                const sel = (a.asignatura_id == selectedId) ? 'selected' : '';
                                opts += `<option value="${a.asignatura_id}" data-docente="${a.docente}" ${sel}>${a.asignatura}</option>`;
                            });
                            $('#falta_editar_asignatura').prop('disabled', false).html(opts);
                            if (selectedId) {
                                $('#falta_editar_docente').val($('#falta_editar_asignatura option:selected').data('docente') || '');
                            }
                        });
                    }

                    // Cambio de grado dentro del modal Editar Falta
                    $(document).on('change', '#falta_editar_grado', function () {
                        faltaCargarAlumnos($(this).val(), null);
                        faltaCargarAsignaturas($(this).val(), null);
                    });

                    // Cambio de asignatura: actualizar docente
                    $(document).on('change', '#falta_editar_asignatura', function () {
                        $('#falta_editar_docente').val($(this).find('option:selected').data('docente') || '');
                    });

                    // ── Abrir modal Editar Falta ──
                    $(document).on('click', '.btn-editar-falta', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();

                        const faltaId = $(this).data('id');
                        const gradoId = $(this).data('grado');
                        const alumnoId = $(this).data('alumno');
                        const asigId = $(this).data('asignatura');
                        const indId = $(this).data('indicador');
                        const fecha = $(this).data('fecha');

                        $('#formEditarFalta').attr('action', "{{ url('academica/faltas') }}/" + faltaId);
                        $('#falta_editar_fecha').val(fecha);
                        $('#falta_editar_indicador').val(indId);
                        $('#falta_editar_grado').val(gradoId);

                        faltaCargarAlumnos(gradoId, alumnoId);
                        faltaCargarAsignaturas(gradoId, asigId);

                        bootstrap.Modal.getInstance(document.getElementById('modalEditar'))?.hide();
                        setTimeout(function () {
                            new bootstrap.Modal(document.getElementById('modalEditarFalta')).show();
                        }, 300);
                    });

                    // ── Funciones auxiliares para el modal Editar Registro Anecdótico ──
                    function ranecCargarAlumnos(gradoId, selectedId) {
                        $('#ranec_editar_alumno').prop('disabled', true).html('<option>Cargando...</option>');
                        $.get("{{ url('academica/faltas/alumnos-por-grado') }}/" + gradoId, function (data) {
                            let opts = '<option value="">— Seleccionar alumno —</option>';
                            data.forEach(a => {
                                const sel = (a.id == selectedId) ? 'selected' : '';
                                opts += `<option value="${a.id}" ${sel}>${a.apellidos}, ${a.nombres}</option>`;
                            });
                            $('#ranec_editar_alumno').prop('disabled', false).html(opts);
                        });
                    }

                    function ranecCargarAsignaturas(gradoId, selectedId) {
                        $('#ranec_editar_asignatura').prop('disabled', true).html('<option>Cargando...</option>');
                        $('#ranec_editar_docente').val('');
                        $.get("{{ url('academica/faltas/asignaturas-por-grado') }}/" + gradoId, function (data) {
                            let opts = '<option value="">— Seleccionar asignatura —</option>';
                            data.forEach(a => {
                                const sel = (a.asignatura_id == selectedId) ? 'selected' : '';
                                opts += `<option value="${a.asignatura_id}" data-docente="${a.docente}" ${sel}>${a.asignatura}</option>`;
                            });
                            $('#ranec_editar_asignatura').prop('disabled', false).html(opts);
                            if (selectedId) {
                                $('#ranec_editar_docente').val($('#ranec_editar_asignatura option:selected').data('docente') || '');
                            }
                        });
                    }

                    // Cambio de grado dentro del modal Editar Registro Anecdótico
                    $(document).on('change', '#ranec_editar_grado', function () {
                        ranecCargarAlumnos($(this).val(), null);
                        ranecCargarAsignaturas($(this).val(), null);
                    });

                    $(document).on('change', '#ranec_editar_asignatura', function () {
                        $('#ranec_editar_docente').val($(this).find('option:selected').data('docente') || '');
                    });

                    // ── Abrir modal Editar Registro Anecdótico ──
                    $(document).on('click', '.btn-editar-registro-anecdotico', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();

                        const id = $(this).data('id');
                        const fecha = $(this).data('fecha');
                        const gradoId = $(this).data('grado');
                        const alumnoId = $(this).data('alumno');
                        const asigId = $(this).data('asignatura');
                        const detalle = $(this).data('detalle');

                        $('#formEditarRegistroAnecdotico').attr('action', "{{ url('academica/registros-anecdoticos') }}/" + id);
                        $('#ranec_editar_fecha').val(fecha);
                        $('#ranec_editar_detalle').val(detalle);
                        $('#ranec_editar_grado').val(gradoId);

                        ranecCargarAlumnos(gradoId, alumnoId);
                        ranecCargarAsignaturas(gradoId, asigId);

                        bootstrap.Modal.getInstance(document.getElementById('modalEditar'))?.hide();
                        setTimeout(function () {
                            new bootstrap.Modal(document.getElementById('modalEditarRegistroAnecdotico')).show();
                        }, 300);
                    });

                    // ── Abrir modal Editar Entrevista ──
                    $(document).on('click', '.btn-editar-entrevista', function (e) {
                        e.preventDefault();
                        e.stopPropagation();

                        const d = $(this).data();
                        const tipo = d.tipo; // 'Alumno' o 'Responsable'

                        if (tipo === 'Alumno') {
                            $('#formEditarEntrevistaAlumno').attr('action', "{{ url('academica/entrevistas/alumno') }}/" + d.id);
                            $('#edit_ent_al_fecha').val(d.fecha);
                            $('#edit_ent_al_colaborador').val(d.colaborador);
                            $('#edit_ent_al_motivo').val(d.motivo);
                            $('#edit_ent_al_obs').val(d.obs);

                            bootstrap.Modal.getInstance(document.getElementById('modalEditar'))?.hide();
                            setTimeout(function () {
                                new bootstrap.Modal(document.getElementById('modalEditarEntrevistaAlumno')).show();
                            }, 300);
                        } else {
                            $('#formEditarEntrevistaResponsable').attr('action', "{{ url('academica/entrevistas/responsable') }}/" + d.id);
                            $('#edit_ent_res_fecha').val(d.fecha);
                            $('#edit_ent_res_colaborador').val(d.colaborador);
                            $('#edit_ent_res_motivo').val(d.motivo);
                            $('#edit_ent_res_obs').val(d.obs);

                            // Select2 para testigos
                            $('#edit_ent_res_testigos').val(d.testigos).trigger('change');

                            bootstrap.Modal.getInstance(document.getElementById('modalEditar'))?.hide();
                            setTimeout(function () {
                                new bootstrap.Modal(document.getElementById('modalEditarEntrevistaResponsable')).show();
                            }, 300);
                        }
                    });

                    // Listener para los toggles (AJAX Update)
                    $(document).on('change', '.toggle-estado', function () {
                        let id = $(this).data('id');
                        let campo = $(this).data('campo');
                        let valor = $(this).is(':checked');

                        let bodyData = {};
                        bodyData[campo] = valor;

                        $.post("{{ url('academica/alumnos') }}/" + id + "/toggle", bodyData)
                            .done(function (response) {
                                // Opcional: mostrar un mini toast/notificación 
                                console.log('Actualizado correctamente');
                            })
                            .fail(function () {
                                alert("Error al actualizar el estado. Se recargará la página.");
                                window.location.reload();
                            });
                    });

                    // ── Tab Asistencia: 10 calendarios (Feb–Nov) ────────────────
                    // Se dispara al mostrar el tab de asistencia dentro del modalEditar

                    var _asistAlumnoId = null;
                    var _asistAnio = parseInt(new Date().getFullYear());
                    var _asistCache = {}; // cache por "alumnoId|anio"

                    // Detectar apertura del tab Asistencia
                    $(document).on('shown.bs.tab', 'button[data-bs-target="#tab-asistencia"]', function () {
                        if (_asistAlumnoId) {
                            cargarCalendariosAsistencia(_asistAlumnoId, _asistAnio);
                        }
                    });

                    // Cambio de año en el selector
                    $(document).on('change', '#asist-anio-sel', function () {
                        _asistAnio = parseInt($(this).val());
                        _asistCache = {}; // limpiar cache al cambiar año
                        if (_asistAlumnoId) {
                            cargarCalendariosAsistencia(_asistAlumnoId, _asistAnio);
                        }
                    });

                    function cargarCalendariosAsistencia(alumnoId, anio) {
                        const cacheKey = `${alumnoId}|${anio}`;
                        if (_asistCache[cacheKey]) {
                            renderCalendarios(_asistCache[cacheKey], anio);
                            return;
                        }

                        $('#asist-calendarios-wrap').html(
                            '<div class="spinner-asist"><div class="spinner-border spinner-border-sm me-2" role="status"></div>Cargando asistencia...</div>'
                        );

                        // Pedir todos los meses en paralelo (Feb=2 a Nov=11)
                        const meses = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
                        const promesas = meses.map(m =>
                            $.get("{{ url('academica/asistencias') }}/" + alumnoId + "/por-alumno", { mes: m, anio: anio })
                                .then(res => ({ mes: m, asistencias: res.asistencias || [] }))
                                .catch(() => ({ mes: m, asistencias: [] }))
                        );

                        $.when(...promesas).done(function (...resultados) {
                            // $.when con múltiples Deferreds: cuando hay MÁS de una promesa,
                            // cada argumento llega como [data, status, xhr].
                            // Con UNA sola promesa llega el objeto directamente.
                            // Mapeamos por índice para mantener el orden de `meses`.
                            const data = meses.map((m, i) => {
                                const raw = resultados.length === 1 ? resultados[0] : resultados[i];
                                const obj = Array.isArray(raw) ? raw[0] : raw;
                                return { mes: m, asistencias: obj?.asistencias || [] };
                            });
                            _asistCache[cacheKey] = data;
                            renderCalendarios(data, anio);
                        });
                    }

                    function renderCalendarios(mesesData, anio) {
                        const NOMBRES_MES = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        const FERIADOS = [
                            `${anio}-01-01`, `${anio}-03-01`, `${anio}-05-01`,
                            `${anio}-05-14`, `${anio}-05-15`, `${anio}-06-12`,
                            `${anio}-08-15`, `${anio}-09-29`, `${anio}-12-08`, `${anio}-12-25`
                        ];
                        const DIAS_H = ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'];

                        let html = '<div class="cal-asist-wrap">';

                        mesesData.forEach(function (item) {
                            const mes = item.mes;
                            const dMap = {};
                            (item.asistencias || []).forEach(a => { dMap[parseInt(a.dia)] = a.estado; });

                            const diasEnMes = new Date(anio, mes, 0).getDate();
                            const primerDia = new Date(anio, mes - 1, 1).getDay();

                            // Contadores para el resumen
                            let cp = 0, ca = 0, cj = 0, ct = 0;

                            html += `<div class="cal-asist-card">`;
                            html += `<div class="cal-asist-title">${NOMBRES_MES[mes]}</div>`;
                            html += `<div class="cal-asist-grid">`;

                            // Cabecera días semana
                            DIAS_H.forEach(d => {
                                html += `<div class="cal-dh">${d}</div>`;
                            });

                            // Celdas vacías antes del primer día
                            for (let i = 0; i < primerDia; i++) {
                                html += `<div class="cal-dc dc-vacio"></div>`;
                            }

                            for (let d = 1; d <= diasEnMes; d++) {
                                const diaSem = new Date(anio, mes - 1, d).getDay();
                                const esFinde = diaSem === 0 || diaSem === 6;
                                const fechaStr = `${anio}-${String(mes).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                                const esFer = FERIADOS.includes(fechaStr);
                                const estado = (dMap[d] || '').trim();

                                let cls = '', title = '';
                                if (esFinde) {
                                    cls = 'dc-finde';
                                } else if (esFer) {
                                    cls = 'dc-feriado'; title = 'Feriado';
                                } else if (estado === 'Presente') { cls = 'dc-presente'; cp++; title = 'Presente'; }
                                else if (estado === 'Ausente') { cls = 'dc-ausente'; ca++; title = 'Ausente'; }
                                else if (estado === 'Justificado') { cls = 'dc-justif'; cj++; title = 'Justificado'; }
                                else if (estado === 'Tardanza') { cls = 'dc-tardanza'; ct++; title = 'Tardanza'; }

                                html += `<div class="cal-dc ${cls}" title="${title}">${esFinde ? '' : d}</div>`;
                            }

                            html += `</div>`; // cal-asist-grid

                            // Resumen del mes
                            html += `<div class="cal-asist-resumen">`;
                            if (cp) html += `<span class="cal-res-badge cr-p">P&nbsp;${cp}</span>`;
                            if (ca) html += `<span class="cal-res-badge cr-a">A&nbsp;${ca}</span>`;
                            if (cj) html += `<span class="cal-res-badge cr-j">J&nbsp;${cj}</span>`;
                            if (ct) html += `<span class="cal-res-badge cr-t">T&nbsp;${ct}</span>`;
                            if (!cp && !ca && !cj && !ct) html += `<span style="color:#adb5bd;font-size:0.58rem">sin datos</span>`;
                            html += `</div>`;

                            html += `</div>`; // cal-asist-card
                        });

                        html += '</div>'; // cal-asist-wrap
                        $('#asist-calendarios-wrap').html(html);
                    }
                    // ── Fin Tab Asistencia ────────────────────────────────────────
                    // ── Tab Calendario de Exámenes ──────────────────────────────────
                    var _calExAlumnoId = null;
                    var _calExCache = {};

                    $(document).on('shown.bs.tab', 'button[data-bs-target="#tab-calendario-examenes"]', function () {
                        if (!_calExAlumnoId) return;
                        if (_calExCache[_calExAlumnoId]) {
                            renderCalendarioExamenes(_calExCache[_calExAlumnoId]);
                            return;
                        }
                        $.get("{{ url('academica/alumnos') }}/" + _calExAlumnoId + "/calendario-examenes")
                            .done(function (res) {
                                _calExCache[_calExAlumnoId] = res;
                                renderCalendarioExamenes(res);
                            })
                            .fail(function () {
                                $('#cal-examenes-wrap').html('<p class="text-danger text-center" style="font-size:0.75rem;">Error al cargar el calendario.</p>');
                            });
                    });

                    function renderCalendarioExamenes(res) {
                        var examenes = res.examenes || [];
                        if (examenes.length === 0) {
                            $('#cal-examenes-wrap').html('<p class="text-muted text-center mt-2" style="font-size:0.75rem;">Sin fechas de exámenes registradas para este grado.</p>');
                            return;
                        }

                        // Agrupar por etapa → tipo_prueba
                        var grupos = {};
                        examenes.forEach(function (e) {
                            var key = e.etapa;
                            if (!grupos[key]) grupos[key] = {};
                            if (!grupos[key][e.tipo_prueba]) grupos[key][e.tipo_prueba] = [];
                            grupos[key][e.tipo_prueba].push(e);
                        });

                        var html = '';
                        Object.keys(grupos).sort().forEach(function (etapa) {
                            html += '<div class="mb-2">';
                            html += '<div class="fw-bold text-white px-2 py-1 rounded-top mb-0" style="background:#1e3a5f;font-size:0.72rem;">Etapa: ' + etapa + '</div>';

                            Object.keys(grupos[etapa]).sort().forEach(function (tipo) {
                                html += '<div class="px-1 pb-1" style="border:1px solid #b0c8e8;border-top:none;border-radius:0 0 6px 6px;">';
                                html += '<p class="fw-bold mb-1 mt-1" style="font-size:0.68rem;color:#2d6bb5;">' + tipo + '</p>';
                                html += '<table class="table table-sm table-bordered mb-0" style="font-size:0.72rem;">';
                                html += '<thead class="table-light"><tr><th>Fecha</th><th>Asignatura 1</th><th>Asignatura 2</th><th>Asignatura 3</th></tr></thead><tbody>';
                                grupos[etapa][tipo].forEach(function (e) {
                                    html += '<tr>';
                                    html += '<td>' + e.fecha + '</td>';
                                    html += '<td>' + (e.asignatura1 || '—') + '</td>';
                                    html += '<td>' + (e.asignatura2 || '—') + '</td>';
                                    html += '<td>' + (e.asignatura3 || '—') + '</td>';
                                    html += '</tr>';
                                });
                                html += '</tbody></table></div>';
                            });

                            html += '</div>';
                        });

                        $('#cal-examenes-wrap').html(html);
                    }
                    // ── Fin Tab Calendario de Exámenes ───────────────────────────────

                    // ── Editar inscripción desde el historial del alumno ──────────
                    $(document).on('click', '.btn-editar-inscripcion', function () {
                        var ins = $(this).data('ins');

                        $('#formEditarInsAlumno').attr('action', "{{ url('inscripciones') }}/" + ins.id);

                        // Campos de sólo lectura
                        $('#ins_edit_alumno_cid').val(ins.alumno_cid);
                        var nombreAlumno = $('#edit_apellidos').val() + ', ' + $('#edit_nombres').val();
                        $('#ins_edit_alumno_nombre').val(nombreAlumno);

                        // Campos editables
                        $('#ins_edit_fecha').val(ins.fecha_raw || '');
                        $('#ins_edit_anio_lectivo').val(ins.anio_lectivo);
                        $('#ins_edit_grado_curso_id').val(ins.grado_curso_id);
                        $('#ins_edit_procede').val(ins.procede || '');
                        $('#ins_edit_fpago').val(ins.fpago || 'Mensual');
                        $('#ins_edit_firmante_rol').val(ins.firmante_rol || '');
                        $('#ins_edit_firmante_nombre').val(ins.firmante_nombre || '');
                        $('#ins_edit_monto_matricula').val(ins.monto_matricula || 0);
                        $('#ins_edit_monto_anualidad').val(ins.monto_anualidad || 0);
                        $('#ins_edit_aut_mochila').prop('checked', ins.aut_mochila === 'Sí' || ins.aut_mochila === 1);
                        $('#ins_edit_aut_foto').prop('checked', ins.aut_foto === 'Sí' || ins.aut_foto === 1);
                        $('#ins_edit_estado').val(ins.estado || 'Matriculado');
                        $('#ins_edit_fecha_baja').val(ins.fecha_baja ? ins.fecha_baja.substring(0, 10) : '');
                        $('#ins_edit_observaciones').val(ins.observaciones || '');

                        new bootstrap.Modal(document.getElementById('modalEditarInsAlumno')).show();
                    });
                    // ── Fin Editar Inscripción ────────────────────────────────────

                    // ── Botones WhatsApp de Responsables ─────────────────────────
                    $(document).on('click', '.btn-whatsapp', function () {
                        var sourceId = $(this).data('tel-source');
                        var tel = $('#' + sourceId).val().trim();

                        if (!tel) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Sin número de teléfono',
                                text: 'Debe registrar un número telefónico válido para usar esta función.',
                                confirmButtonText: 'Entendido',
                                confirmButtonColor: '#25D366'
                            });
                            return;
                        }

                        // Limpiar: quitar espacios, guiones, paréntesis
                        var telLimpio = tel.replace(/[\s\-\(\)]/g, '');

                        // Normalizar al formato internacional +595
                        if (telLimpio.startsWith('00595')) {
                            telLimpio = '+' + telLimpio.slice(2);
                        } else if (telLimpio.startsWith('595')) {
                            telLimpio = '+' + telLimpio;
                        } else if (telLimpio.startsWith('0')) {
                            telLimpio = '+595' + telLimpio.slice(1);
                        } else if (!telLimpio.startsWith('+')) {
                            telLimpio = '+595' + telLimpio;
                        }

                        window.open('https://wa.me/' + telLimpio, '_blank');
                    });
                    // ── Fin WhatsApp ──────────────────────────────────────────────

                } else {
                    alert("Error crítico: jQuery no se ha cargado. Revise app.blade.php");
                }
            };
        </script>
    @endpush
</x-app-layout>