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
        select[name="tabla-inscripciones_length"] {
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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Inscripciones</h2>
    </x-slot>

    <div class="card card-body p-2 shadow-sm">
        <div class="d-flex justify-content-between mb-2">
            <h6 class="fw-bold text-secondary">Listado de Inscripciones</h6>
        </div>

        <table id="tabla-inscripciones" class="table table-sm table-hover table-bordered table-xs w-100">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th width="60">Fecha</th>
                    <th>Año</th>
                    <th width="70">C.I. Alumno</th>
                    <th>Alumno</th>
                    <th>Grado/Curso</th>
                    <th>Firmante</th>
                    <th>Rol</th>
                    <th width="100" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inscripciones as $ins)
                    <tr>
                        <td>{{ $ins->id }}</td>
                        <td data-sort="{{ $ins->fecha }}">{{ \Carbon\Carbon::parse($ins->fecha)->format('d/m/Y') }}</td>
                        <td class="text-center">{{ $ins->anio_lectivo }}</td>
                        <td>{{ number_format((int) $ins->alumno_cid, 0, ',', '.') }}</td>
                        <td>{{ $ins->alumno->apellidos ?? '' }}, {{ $ins->alumno->nombres ?? '' }}</td>
                        <td>{{ $ins->grado->gradocurso ?? '' }}</td>
                        <td>{{ $ins->firmante_nombre }}</td>
                        <td>{{ $ins->firmante_rol }}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-primary btn-xs py-0 px-1 btn-editar"
                                style="font-size: 0.65rem;" data-id="{{ $ins->id }}" data-json='{{ json_encode($ins) }}'>
                                Editar
                            </button>

                            <form action="{{ route('inscripciones.destroy', $ins->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs py-0 px-1" style="font-size: 0.65rem;"
                                    onclick="return confirm('¿Borrar Inscripción permanentemente?')">Borrar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('inscripciones.modales')

    <script>
        window.onload = function () {
            if (window.jQuery) {

                $('#tabla-inscripciones').DataTable({
                    "order": [[1, "desc"]], // Ordenar por fecha descendiente
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

                $(document).on('click', '.btn-editar', function () {
                    var d = $(this).data('json');
                    $('#formEditarIns').attr('action', "{{ url('inscripciones') }}/" + d.id);

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

                    // Mostrar info de lectura extra
                    if (d.alumno) {
                        $('#edit_alumno_nombre').val(d.alumno.apellidos + ', ' + d.alumno.nombres);
                    }

                    var myModal = new bootstrap.Modal(document.getElementById('modalEditarIns'));
                    myModal.show();
                });

            } else {
                alert("Error crítico: jQuery no se ha cargado. Revise app.blade.php");
            }
        };
    </script>
</x-app-layout>