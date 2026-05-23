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
            /* Bootstrap Success color */
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
                        <th class="text-center">Activo</th>
                        <th width="80" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alumnos as $al)
                        <tr>
                            <td class="text-center"></td>
                            <td>{{ $al->apellidos }}, {{ $al->nombres }}</td>
                            <td>{{ number_format($al->cid, 0, ',', '.') }}</td>
                            <td>{{ $al->sexo->sexo ?? 'N/A' }}</td>
                            <td>{{ $al->telefono ?? '-' }}</td>
                            <td class="text-center align-middle">
                                <label class="toggle mb-0" style="margin-top:0;">
                                    <input type="checkbox" class="toggle-estado" data-id="{{ $al->id }}" data-campo="activo" {{ $al->activo == 'Sí' ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-success btn-xs py-0 px-1 btn-editar"
                                    style="font-size: 0.65rem;" data-id="{{ $al->id }}" data-json='{{ json_encode($al) }}'>
                                    Editar
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

                } else {
                    alert("Error crítico: jQuery no se ha cargado. Revise app.blade.php");
                }
            };
        </script>
    @endpush
</x-app-layout>