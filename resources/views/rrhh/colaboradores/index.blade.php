<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Colaboradores</h2>
    </x-slot>

    <div class="card card-body p-2 shadow-sm">
        <div class="d-flex justify-content-between mb-2">
            <h6 class="fw-bold text-secondary"></h6>
            <button class="btn btn-primary btn-sm" style="font-size: 0.7rem;" data-bs-toggle="modal"
                data-bs-target="#modalCrear">+ Nuevo Colaborador</button>
        </div>

        <table id="tabla-colaboradores" class="table table-sm table-hover table-bordered table-xs">
            <thead class="table-light">
                <tr>
                    <th width="50">ID</th>
                    <th>Apellidos</th>
                    <th>Nombres</th>
                    <th>Cédula Id.</th>
                    <th>Tipo</th>
                    <th>Teléfono</th>
                    <th width="150" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($colaboradores as $col)
                    <tr>
                        <td>{{ $col->id }}</td>
                        <td>{{ $col->apellidos }}</td>
                        <td>{{ $col->nombres }}</td>
                        <td>{{ number_format($col->cid, 0, ',', '.') }}</td>
                        <td>{{ $col->tipoColaborador->tipo_colaborador ?? 'N/A' }}</td>
                        <td>{{ $col->telefono ?? '-' }}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-primary btn-xs py-0 px-1 btn-editar"
                                style="font-size: 0.65rem;" data-id="{{ $col->id }}" data-json='{{ json_encode($col) }}'>
                                Editar
                            </button>
                            <form action="{{ route('colaboradores.destroy', $col->id) }}" method="POST" class="d-inline">
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

    @include('rrhh.colaboradores.modales')

    @push('scripts')
        <script>
            $(document).ready(function () {
                // Inicializar DataTable
                $('#tabla-colaboradores').DataTable({
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