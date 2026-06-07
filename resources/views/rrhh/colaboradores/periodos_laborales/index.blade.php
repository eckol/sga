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

        .btn-xs {
            padding: 1px 5px;
            font-size: 0.7rem;
            line-height: 1.5;
            border-radius: 3px;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Historial de Períodos Laborales</h2>
    </x-slot>

    <div class="card card-body p-2 shadow-sm">
        <div class="d-flex justify-content-between mb-2">
            <h6 class="fw-bold text-secondary"></h6>
            <button class="btn btn-primary btn-sm fw-bold shadow-sm" style="font-size: 0.7rem;" data-bs-toggle="modal"
                data-bs-target="#modalCrear"><i class="fas fa-plus-circle me-1"></i> Nuevo Período Laboral</button>
        </div>

        <table id="tabla-periodos" class="table table-sm table-hover table-bordered table-xs">
            <thead class="table-light">
                <tr>
                    <th width="50">ID</th>
                    <th>Colaborador / CID</th>
                    <th class="text-center">Ingreso</th>
                    <th class="text-center">Egreso</th>
                    <th class="text-center">Antigüedad</th>
                    <th>Observación</th>
                    <th width="120" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($periodos as $p)
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td>
                            <div class="fw-bold text-primary">{{ $p->colaborador->apellidos }}, {{ $p->colaborador->nombres }}</div>
                            <div class="text-muted small">CID: {{ number_format($p->colaborador->cid, 0, ',', '.') }}</div>
                        </td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($p->fecha_ingreso)->format('d/m/Y') }}</td>
                        <td class="text-center">
                            @if($p->fecha_egreso)
                                {{ \Carbon\Carbon::parse($p->fecha_egreso)->format('d/m/Y') }}
                            @else
                                <span class="badge bg-success text-white" style="font-size: 0.6rem;">Activo</span>
                            @endif
                        </td>
                        <td class="text-center"><small class="fw-bold text-dark">{{ $p->antiguedad }}</small></td>
                        <td><small>{{ $p->observacion ?? '-' }}</small></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-warning btn-xs btn-editar"
                                data-id="{{ $p->id }}" data-json='{{ json_encode($p) }}'
                                data-colaborador="{{ $p->colaborador->apellidos }}, {{ $p->colaborador->nombres }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('periodos-laborales.destroy', $p->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs"
                                    onclick="return confirm('¿Eliminar este período?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('rrhh.colaboradores.periodos_laborales.modales')

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#tabla-periodos').DataTable({
                    "order": [[1, "asc"]],
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
                    var colaborador = $(this).data('colaborador');
                    $('#formEditar').attr('action', "{{ url('rrhh/periodos-laborales') }}/" + d.id);
                    $('#edit_colaborador_nombre').val(colaborador);
                    $('#edit_fecha_ingreso').val(d.fecha_ingreso);
                    $('#edit_fecha_egreso').val(d.fecha_egreso);
                    $('#edit_observacion').val(d.observacion);

                    var myModal = new bootstrap.Modal(document.getElementById('modalEditar'));
                    myModal.show();
                });
            });
        </script>
    @endpush
</x-app-layout>
