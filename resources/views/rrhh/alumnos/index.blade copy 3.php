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
            /* Bootstrap Success color */
        }

        input:checked+.slider:before {
            transform: translateX(16px);
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Alumnos</h2>
    </x-slot>

    <div class="card card-body p-2 shadow-sm">
        <div class="d-flex justify-content-between mb-2">
            <h6 class="fw-bold text-secondary"></h6>
            <button class="btn btn-primary btn-sm" style="font-size: 0.7rem;" data-bs-toggle="modal"
                data-bs-target="#modalCrear">+ Nuevo Alumno</button>
        </div>

        <table id="tabla-alumnos" class="table table-sm table-hover table-bordered table-xs">
            <thead class="table-light">
                <tr>
                    <th width="50">ID</th>
                    <th>Apellidos</th>
                    <th>Nombres</th>
                    <th>Cédula Id.</th>
                    <th>Nacionalidad</th>
                    <th>Teléfono</th>
                    <th class="text-center">Gmaps</th>
                    <th>Grado/Curso</th>
                    <th class="text-center">Estado</th>
                    <th width="110" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alumnos as $al)
                    <tr>
                        <td>{{ $al->id }}</td>
                        <td>{{ $al->apellidos }}</td>
                        <td>{{ $al->nombres }}</td>
                        <td>{{ number_format($al->cid, 0, ',', '.') }}</td>
                        <td>{{ $al->nacionalidad->nacionalidad ?? 'N/A' }}</td>
                        <td>{{ $al->telefono ?? '-' }}</td>
                        <td class="text-center align-middle">
                            @php $gmaps = trim((string) $al->gmaps); @endphp
                            @if($gmaps !== '' && $gmaps !== null)
                                @php
                                    $gmap_url = str_starts_with($gmaps, 'http')
                                        ? $gmaps
                                        : 'https://' . $gmaps;
                                @endphp
                                <a href="{{ $gmap_url }}" target="_blank" class="text-danger" title="Ver ubicación">
                                    <i class="fas fa-map-marker-alt fa-lg"></i>
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        @php
                            $ultimaIns = $al->inscripciones->first();
                            $gradoActualNombre = $ultimaIns ? $ultimaIns->grado->gradocurso ?? '—' : '—';
                            $gradoActualId = $ultimaIns ? $ultimaIns->grado->id ?? '' : '';
                            $estadoActual = $ultimaIns ? $ultimaIns->estado : null;
                        @endphp
                        <td style="font-size:0.72rem">{{ $gradoActualNombre }}</td>
                        <td class="text-center">
                            @if($estadoActual === 'Matriculado')
                                <span class="badge bg-success" style="font-size:0.65rem">{{ $estadoActual }}</span>
                            @elseif($estadoActual)
                                <span class="badge bg-secondary" style="font-size:0.65rem">{{ $estadoActual }}</span>
                            @else
                                <span class="text-muted" style="font-size:0.7rem">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-warning btn-xs py-0 px-1 btn-editar"
                                title="Editar alumno"
                                style="font-size: 0.65rem;" data-id="{{ $al->id }}" data-json='{{ json_encode($al) }}'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('alumnos.destroy', $al->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs py-0 px-1"
                                    title="Eliminar alumno"
                                    style="font-size: 0.65rem;"
                                    onclick="return confirm('¿Eliminar este alumno?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            <button type="button" class="btn btn-success btn-xs py-0 px-1 btn-inscribir"
                                title="Inscribir alumno"
                                style="font-size: 0.65rem;" data-id="{{ $al->id }}" data-cid="{{ $al->cid }}"
                                data-nombre="{{ $al->apellidos }}, {{ $al->nombres }}" data-madre="{{ $al->cid_madre }}"
                                data-padre="{{ $al->cid_padre }}" data-encargado="{{ $al->cid_encargado }}"
                                data-grado-nombre="{{ $gradoActualNombre }}" data-grado-id="{{ $gradoActualId }}">
                                <i class="fas fa-user-graduate"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('rrhh.alumnos.modales')

    <script>
        window.onload = function () {
            if (window.jQuery) {

                // Inicializar DataTable
                $('#tabla-alumnos').DataTable({
                    "order": [[1, "asc"]], // Ordenar por apellido por defecto
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


                // Inicializar DataTable de faltas UNA SOLA VEZ (vacía).
                // Se usará su API (.clear().rows.add().draw()) para repoblarla sin destruirla.
                var dtFaltas = $('#tabla-faltas-alumno').DataTable({
                    "order": [[1, "desc"]],
                    "pageLength": 5,
                    "lengthMenu": [5, 10, 25],
                    "columns": [
                        { "title": "ID" },
                        { "title": "Fecha" },
                        { "title": "Indicador" },
                        { "title": "Grado/Curso" },
                        { "title": "Asignatura" },
                        { "title": "Ver", "orderable": false, "className": "text-center" }
                    ],
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

                // Listener para abrir modal de edición
                $(document).on('click', '.btn-editar', function () {
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

                    // Update la foto
                    let fotoPreview = document.getElementById('preview_foto_editar');
                    if (d['foto']) {
                        fotoPreview.src = "{{ asset('img/alumnos/') }}/" + d['foto'];
                    } else {
                        fotoPreview.src = "{{ asset('img/alumnos/alumno.jpg') }}";
                    }

                    // Limpiar campos de responsables y tabla de historial
                    $('#info_madre_nombre, #info_padre_nombre, #info_encargado_nombre').val('Cargando...');
                    $('#table-inscripciones-historial').html('<tr><td colspan="7" class="text-center">Cargando...</td></tr>');

                    // Limpiar DataTable de faltas mientras carga
                    dtFaltas.clear().draw();

                    // Cargar detalles vía AJAX
                    $.get("{{ url('academica/alumnos') }}/" + d.id + "/detalles")
                        .done(function (res) {
                            // Responsables
                            $('#info_madre_nombre').val(res.madre ? res.madre.nombre : 'No registrado');
                            $('#info_padre_nombre').val(res.padre ? res.padre.nombre : 'No registrado');
                            $('#info_encargado_nombre').val(res.encargado ? res.encargado.nombre : 'No registrado');

                            // Historial de Inscripciones
                            let html = '';
                            if (res.inscripciones && res.inscripciones.length > 0) {
                                res.inscripciones.forEach(ins => {
                                    html += `<tr>
                                        <td>${ins.id}</td>
                                        <td>${ins.fecha}</td>
                                        <td>${ins.anio_lectivo}</td>
                                        <td>${ins.grado_curso}</td>
                                        <td>${ins.firmante_nombre || ''}</td>
                                        <td>${ins.firmante_rol || ''}</td>
                                        <td>${ins.estado}</td>
                                    </tr>`;
                                });
                            } else {
                                html = '<tr><td colspan="7" class="text-center text-muted">Sin historial</td></tr>';
                            }
                            $('#table-inscripciones-historial').html(html);

                            // Repoblar DataTable de Faltas usando su API (sin destruir/recrear)
                            dtFaltas.clear();
                            if (res.faltas && res.faltas.length > 0) {
                                res.faltas.forEach(f => {
                                    var boton = `<button class="btn btn-sm btn-outline-info py-0 px-1 btn-ver-falta"
                                                    style="font-size:0.7rem;"
                                                    data-falta-id="${f.id}"
                                                    title="Ver detalle">
                                                    <i class="fas fa-eye"></i>
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
                        })
                        .fail(function () {
                            $('#info_madre_nombre, #info_padre_nombre, #info_encargado_nombre').val('Error al cargar');
                            $('#table-inscripciones-historial').html('<tr><td colspan="7" class="text-center text-danger">Error al cargar historial</td></tr>');
                            dtFaltas.clear().draw();
                        });

                    var myModal = new bootstrap.Modal(document.getElementById('modalEditar'));
                    myModal.show();
                });

                // Listener para abrir el modal de inscripción
                $(document).on('click', '.btn-inscribir', function () {
                    const d = $(this).data();

                    // Llenar datos básicos
                    $('#ins_alumno_cid').val(d.cid);
                    $('#ins_nombre_alumno').text(d.nombre);
                    $('#inscribir_grado_actual').val(d.gradoNombre);

                    // Lógica inteligente: Seleccionar el siguiente grado
                    if (d.gradoId) {
                        // Obtenemos la última letra (A o B) del grado actual
                        const seccionActual = d.gradoNombre.slice(-1);

                        // Buscamos en el select de grados el primero que sea mayor al ID actual y tenga la misma letra
                        let encontrado = false;
                        $('#select_grado_nuevo option').each(function () {
                            let idOpcion = $(this).val();
                            let textoOpcion = $(this).text();

                            // Si el ID es mayor y la sección coincide
                            if (parseInt(idOpcion) > parseInt(d.gradoId) && textoOpcion.endsWith(seccionActual)) {
                                $('#select_grado_nuevo').val(idOpcion);
                                encontrado = true;
                                return false; // Rompe el loop de each
                            }
                        });
                    }

                    var myModal = new bootstrap.Modal(document.getElementById('modalInscribir'));
                    myModal.show();
                });

            } else {
                alert("Error crítico: jQuery no se ha cargado. Revise app.blade.php");
            }
        };
    </script>

</x-app-layout>