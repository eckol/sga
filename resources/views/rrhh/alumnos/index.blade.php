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
                    <th width="150" class="text-center">Acciones</th>
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
                            {{-- DESPUÉS --}}
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
                        <td class="text-center">
                            <button type="button" class="btn btn-primary btn-xs py-0 px-1 btn-editar"
                                style="font-size: 0.65rem;" data-id="{{ $al->id }}" data-json='{{ json_encode($al) }}'>
                                Editar
                            </button>
                            <form action="{{ route('alumnos.destroy', $al->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs py-0 px-1" style="font-size: 0.65rem;"
                                    onclick="return confirm('¿Borrar?')">Borrar</button>
                            </form>
                            @php
                                $ultimaIns = $al->inscripciones->first();
                                $gradoActualNombre = $ultimaIns ? $ultimaIns->grado->gradocurso : 'No inscripto';
                                $gradoActualId = $ultimaIns ? $ultimaIns->grado->id : '';
                            @endphp

                            <button type="button" class="btn btn-success btn-xs py-0 px-1 btn-inscribir"
                                style="font-size: 0.65rem;" data-id="{{ $al->id }}" data-cid="{{ $al->cid }}"
                                data-nombre="{{ $al->apellidos }}, {{ $al->nombres }}" data-madre="{{ $al->cid_madre }}"
                                data-padre="{{ $al->cid_padre }}" data-encargado="{{ $al->cid_encargado }}"
                                data-grado-nombre="{{ $gradoActualNombre }}" data-grado-id="{{ $gradoActualId }}">
                                Inscribir
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

                // Listener para abrir modal de edición
                $(document).on('click', '.btn-editar', function () {
                    var d = $(this).data('json');
                    $('#formEditar').attr('action', "{{ url('rrhh/alumnos') }}/" + d.id);

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