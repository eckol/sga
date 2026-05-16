<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Sexos</h2>
    </x-slot>

    <div class="card card-body p-2">
        <div class="d-flex justify-content-between mb-2">
            <h6 class="fw-bold"></h6>
            <button class="btn btn-primary btn-sm" style="font-size: 0.7rem;" data-bs-toggle="modal"
                data-bs-target="#modalCrear">+ Nuevo Sexo</button>
        </div>

        <table id="tabla-sexos" class="table table-sm table-hover table-bordered table-xs">
            <thead class="table-light">
                <tr>
                    <th width="50">ID</th>
                    <th>Sexo</th>
                    <th width="150" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sexos as $sexo)
                    <tr>
                        <td>{{ $sexo->id }}</td>
                        <td>{{ $sexo->sexo }}</td>
                        <td class="text-center">
                            <button class="btn btn-primary btn-xs py-0 px-1" style="font-size: 0.65rem;">Editar</button>

                            <form action="{{ route('sexos.destroy', $sexo) }}" method="POST" class="d-inline">
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

    <script>
        $(document).ready(function () {
            $('#tabla-sexos').DataTable({
                "order": [[0, "asc"]], // Ordenar por la primera columna (ID) de forma ascendente
                "pageLength": 10,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json" // Traducción al español
                }
            });
        });
    </script>

    <div class="modal fade" id="modalCrear" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <form action="{{ route('sexos.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header p-2 bg-primary text-white">
                    <h6 class="modal-title">Nuevo Sexo</h6>
                </div>
                <div class="modal-body p-2">
                    <input type="text" name="sexo" class="form-control form-control-sm" placeholder="Nombre del sexo"
                        required>
                </div>
                <div class="modal-footer p-1">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>