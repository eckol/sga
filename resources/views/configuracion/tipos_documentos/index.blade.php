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
        select[name="tabla-tipos-documentos_length"] {
            border-radius: 5px !important;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Tipos de Documentos</h2>
    </x-slot>

    <div class="card card-body p-2">
        <div class="d-flex justify-content-between mb-2">
            <h6 class="fw-bold"></h6>
            <button class="btn btn-primary btn-sm" style="font-size: 0.7rem;" data-bs-toggle="modal"
                data-bs-target="#modalCrear">+ Nuevo Tipo de Documento</button>
        </div>

        <table id="tabla-tipos-documentos" class="table table-sm table-hover table-bordered table-xs">
            <thead class="table-light">
                <tr>
                    <th width="50">ID</th>
                    <th>Tipo de Documento</th>
                    <th width="150" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tipos_documentos as $tipo)
                    <tr>
                        <td>{{ $tipo->id }}</td>
                        <td>{{ $tipo->tipo_documento }}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-warning btn-xs py-0 px-1 btn-editar"
                                style="font-size: 0.65rem;" data-id="{{ $tipo->id }}"
                                data-tipo_documento="{{ $tipo->tipo_documento }}">
                                <i class="fas fa-edit"></i>
                            </button>

                            <form action="{{ route('tipos-documentos.destroy', $tipo->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs py-0 px-1" style="font-size: 0.65rem;"
                                    onclick="return confirm('¿Borrar?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalCrear" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <form action="{{ route('tipos-documentos.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header p-2 bg-primary text-white">
                    <h6 class="modal-title">Nuevo Tipo de Documento</h6>
                </div>
                <div class="modal-body p-2">
                    <label class="form-label mb-0 fw-bold">Tipo de Documento</label>
                    <input type="text" name="tipo_documento" class="form-control form-control-sm"
                        placeholder="Nombre del tipo de documento" required>
                </div>
                <div class="modal-footer p-1">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalEditarTipoDocumento" tabindex="-1">
        <div class="modal-dialog">
            <form id="formEditar" method="POST" class="modal-content">
                @csrf @method('PATCH')
                <div class="modal-header p-2 bg-primary text-white">
                    <h6 class="modal-title">Modificar Tipo de Documento</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Tipo de Documento</label>
                        <input type="text" name="tipo_documento" id="edit_tipo_documento"
                            class="form-control form-control-sm" required>
                    </div>
                </div>
                <div class="modal-footer p-1">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-sm">Actualizar Cambios</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        window.onload = function () {
            if (window.jQuery) {
                var table = $('#tabla-tipos-documentos').DataTable({
                    "order": [[0, "asc"]],
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

                $(document).on('click', '.btn-editar', function (e) {
                    e.preventDefault();
                    var id = $(this).data('id');
                    var tipo_documento = $(this).data('tipo_documento');

                    $('#formEditar').attr('action', '/tipos-documentos/' + id);
                    $('#edit_tipo_documento').val(tipo_documento);

                    var myModal = new bootstrap.Modal(document.getElementById('modalEditarTipoDocumento'));
                    myModal.show();
                });
            }
        };
    </script>

</x-app-layout>