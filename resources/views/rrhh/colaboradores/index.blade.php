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

        .dt-buttons .btn {
            font-size: 0.75rem;
            margin-right: 5px;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Colaboradores</h2>
    </x-slot>

    <div class="card card-body p-2 shadow-sm mb-3">
        <div class="row align-items-center">
            <div class="col-md-auto">
                <label for="filtro_estado" class="col-form-label col-form-label-sm fw-bold">Filtrar por Estado:</label>
            </div>
            <div class="col-md-3">
                <select id="filtro_estado" class="form-select form-select-sm">
                    <option value="Activo" selected>Colaboradores Activos</option>
                    <option value="Inactivo">Colaboradores Inactivos</option>
                    <option value="Todos">Todos los Colaboradores</option>
                </select>
            </div>
            <div class="col text-end">
                <button class="btn btn-primary btn-sm fw-bold shadow-sm" style="font-size: 0.7rem;"
                    data-bs-toggle="modal" data-bs-target="#modalCrear">
                    <i class="fas fa-plus-circle me-1"></i> Nuevo Colaborador
                </button>
            </div>
        </div>
    </div>

    <div class="card card-body p-2 shadow-sm">
        <table id="tabla-colaboradores" class="table table-sm table-hover table-bordered table-xs">
            <thead class="table-light">
                <tr>
                    <th width="50">ID</th>
                    <th>Apellidos</th>
                    <th>Nombres</th>
                    <th>Cédula Id.</th>
                    <th class="text-center">Estado</th>
                    <th>Tipo</th>
                    <th>Teléfono</th>
                    <th style="display:none;">Fecha Nac.</th>
                    <th style="display:none;">Email Inst.</th>
                    <th width="150" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($colaboradores as $col)
                    @php
                        $esActivo = $col->es_activo;
                    @endphp
                    <tr>
                        <td>{{ $col->id }}</td>
                        <td>{{ $col->apellidos }}</td>
                        <td>{{ $col->nombres }}</td>
                        <td>{{ $col->cid }}</td>
                        <td class="text-center">
                            @if($esActivo)
                                <span class="badge bg-success" style="font-size: 0.65rem;">Activo</span>
                            @else
                                <span class="badge bg-danger" style="font-size: 0.65rem;">Inactivo</span>
                            @endif
                        </td>
                        <td>{{ $col->tipoColaborador->tipo_colaborador ?? 'N/A' }}</td>
                        <td>{{ $col->telefono ?? '-' }}</td>
                        <td style="display:none;">{{ \Carbon\Carbon::parse($col->fnac)->format('d/m/Y') }}</td>
                        <td style="display:none;">{{ $col->email_institucional ?? '-' }}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-warning btn-xs py-0 px-1 btn-editar"
                                style="font-size: 0.65rem;" data-id="{{ $col->id }}" data-json='{{ json_encode($col) }}'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('colaboradores.destroy', $col->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs py-0 px-1" style="font-size: 0.65rem;"
                                    onclick="return confirm('¿Borrar?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('rrhh.colaboradores.modales')

    @push('scripts')
        <!-- Scripts de exportación -->
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

        <script>
            $(document).ready(function () {
                // Filtro personalizado para el estado
                $.fn.dataTable.ext.search.push(
                    function (settings, data, dataIndex) {
                        var filtro = $('#filtro_estado').val();
                        var estado = data[4]; // Índice de la columna Estado

                        if (filtro === 'Todos') return true;
                        return estado === filtro;
                    }
                );

                // Inicializar DataTable
                var table = $('#tabla-colaboradores').DataTable({
                    "order": [[1, "asc"]],
                    "pageLength": 25,
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
                    "dom": "<'row mb-2'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    "buttons": [
                        {
                            extend: 'excelHtml5',
                            text: '<i class="fas fa-file-excel"></i> Excel',
                            className: 'btn btn-success btn-sm',
                            title: 'Colegio Privado Santa Teresita - Luque, Paraguay',
                            messageTop: function () {
                                return 'Nómina de Colaboradores - Estado: ' + $('#filtro_estado').val();
                            },
                            exportOptions: {
                                columns: [0, 1, 2, 3, 7, 5, 6, 8] // ID, Apellidos, Nombres, CID, Fnac, Tipo, Tel, Email
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            text: '<i class="fas fa-file-pdf"></i> PDF',
                            className: 'btn btn-danger btn-sm',
                            pageSize: 'LEGAL',
                            orientation: 'landscape',
                            title: 'Colegio Privado Santa Teresita - Luque, Paraguay',
                            messageTop: function () {
                                return 'Nómina de Colaboradores - Estado: ' + $('#filtro_estado').val();
                            },
                            exportOptions: {
                                columns: [0, 1, 2, 3, 7, 5, 6, 8]
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
                                    doc.content[2].table.widths = ['5%', '15%', '15%', '10%', '10%', '15%', '10%', '20%'];
                                }
                            }
                        },
                        {
                            extend: 'print',
                            text: '<i class="fas fa-print"></i> Imprimir',
                            className: 'btn btn-secondary btn-sm',
                            title: 'Colegio Privado Santa Teresita - Luque, Paraguay',
                            messageTop: function () {
                                return '<div class="h4 mb-3">Nómina de Colaboradores - Estado: ' + $('#filtro_estado').val() + '</div>';
                            },
                            exportOptions: {
                                columns: [0, 1, 2, 3, 7, 5, 6, 8]
                            },
                            customize: function (win) {
                                $(win.document.body).css('text-align', 'center');
                                $(win.document.body).find('h1').css('text-align', 'center');
                                $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('margin', '0 auto')
                                    .css('width', '100%');
                                $(win.document.body).find('table th, table td').css('text-align', 'left');
                            }
                        }
                    ]
                });

                // Cambiar filtro al seleccionar el estado
                $('#filtro_estado').on('change', function () {
                    table.draw();
                });

                // Listener para editar
                $(document).on('click', '.btn-editar', function () {
                    var d = $(this).data('json');
                    $('#formEditar').attr('action', "{{ url('rrhh/colaboradores') }}/" + d.id);

                    // Mapeo dinámico
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
                        fotoPreview.src = "{{ asset('img/colaboradores/') }}/" + d['foto'];
                    } else {
                        fotoPreview.src = "{{ asset('img/colaborador.jpg') }}";
                    }

                    var myModal = new bootstrap.Modal(document.getElementById('modalEditar'));
                    myModal.show();
                });
            });
        </script>
    @endpush
</x-app-layout>