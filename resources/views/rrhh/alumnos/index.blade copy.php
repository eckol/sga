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
                    $('#formEditar').attr('action', '/alumnos/' + d.id);

                    // Mapeo dinámico de campos al modal
                    Object.keys(d).forEach(key => {
                        $(`#edit_${key}`).val(d[key]);
                    });

                    var myModal = new bootstrap.Modal(document.getElementById('modalEditar'));
                    myModal.show();
                });

            } else {
                alert("Error crítico: jQuery no se ha cargado. Revise app.blade.php");
            }
        };
    </script>

</x-app-layout>