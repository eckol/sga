<x-app-layout>

    <style>
        .form-select-sm,
        .form-select-sm option {
            font-size: 0.75rem !important;
            padding-top: 0.2rem;
            padding-bottom: 0.2rem;
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            font-size: 0.75rem !important;
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input,
        .form-select-sm,
        .form-control-sm {
            border-radius: 8px !important;
        }

        .page-link {
            padding: 0.25rem 0.5rem !important;
            font-size: 0.75rem !important;
        }

        select[name="tablaAvisos_length"] {
            border-radius: 5px !important;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Avisos y Circulares</h2>
    </x-slot>

    <div class="card card-body p-2 shadow-sm">
        <div class="d-flex justify-content-between mb-2">
            <h6 class="fw-bold text-secondary"></h6>
            <button class="btn btn-primary btn-sm fw-bold shadow-sm" style="font-size: 0.7rem;" data-bs-toggle="modal"
                data-bs-target="#modalNuevoAviso">
                <i class="fas fa-plus-circle me-1"></i>Nuevo Aviso
            </button>
        </div>

        <table id="tablaAvisos" class="table table-sm table-hover table-bordered table-xs">
            <thead class="table-light">
                <tr>
                    <th>Fecha</th>
                    <th>Título</th>
                    <th>Destino</th>
                    <th>Enviado por</th>
                    <th>Estado</th>
                    <th class="text-center">Enviados</th>
                    <th class="text-center">Acc.</th>
                </tr>
            </thead>
            <tbody>
                @foreach($avisos as $aviso)
                    <tr>
                        <td>{{ $aviso->fecha->format('d/m/Y') }}</td>
                        <td>{{ $aviso->titulo }}</td>
                        <td>{{ $aviso->destino_label }}</td>
                        <td>
                            @if($aviso->colaborador)
                                {{ $aviso->colaborador->apellidos }}, {{ $aviso->colaborador->nombres }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $badge = match ($aviso->estado) {
                                    'pendiente' => 'secondary',
                                    'procesando' => 'warning',
                                    'enviado' => 'success',
                                    'error' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $badge }}">{{ ucfirst($aviso->estado) }}</span>
                        </td>
                        <td class="text-center">{{ $aviso->total_enviados }}</td>
                        <td class="text-center">
                            <button class="btn btn-info btn-xs py-0 px-1 btn-ver-aviso" style="font-size:0.65rem;"
                                data-id="{{ $aviso->id }}" title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- MODAL: Nuevo Aviso --}}
    <div class="modal fade" id="modalNuevoAviso" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#1e3a5f; color:#fff;">
                    <h6 class="modal-title"><i class="fas fa-bullhorn me-2"></i>Nuevo Aviso / Circular</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formNuevoAviso" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label form-label-sm">Fecha <span class="text-danger">*</span></label>
                                <input type="date" name="fecha" id="fecha" class="form-control form-control-sm"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label form-label-sm">Título / Asunto del mail <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="titulo" class="form-control form-control-sm"
                                    placeholder="Ej: Reunión de padres — 3er Ciclo" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label form-label-sm">Mensaje (opcional)</label>
                                <textarea name="mensaje" class="form-control form-control-sm" rows="4"
                                    placeholder="Texto del aviso. Puede dejarse vacío si solo adjunta una imagen o PDF."></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label form-label-sm">Adjunto (imagen o PDF, máx. 5 MB)</label>
                                <input type="file" name="archivo_adjunto" id="archivoAdjunto"
                                    class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.gif,.pdf">
                                <div id="previewAdjunto" class="mt-2"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label form-label-sm">Destino <span
                                        class="text-danger">*</span></label>
                                <select name="destino_tipo" id="destinoTipo" class="form-select form-select-sm"
                                    required>
                                    <option value="colegio_completo">Todo el Colegio</option>
                                    <option value="ciclo">Por Ciclo</option>
                                    <option value="grado_curso">Por Grado/Curso</option>
                                </select>
                            </div>
                            <div class="col-md-8" id="destinoIdWrapper" style="display:none;">
                                <div id="selectorCiclo" style="display:none;">
                                    <label class="form-label form-label-sm">Ciclo <span
                                            class="text-danger">*</span></label>
                                    <select name="destino_id" id="destinoCicloId" class="form-select form-select-sm">
                                        <option value="">-- Seleccione --</option>
                                        @foreach($ciclos as $ciclo)
                                            <option value="{{ $ciclo->id }}">{{ $ciclo->ciclo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="selectorGrado" style="display:none;">
                                    <label class="form-label form-label-sm">Grado / Curso <span
                                            class="text-danger">*</span></label>
                                    <select name="destino_id" id="destinoGradoId" class="form-select form-select-sm">
                                        <option value="">-- Seleccione --</option>
                                        @foreach($gradosCursos as $gc)
                                            <option value="{{ $gc->id }}">{{ $gc->gradocurso }}
                                                ({{ $gc->turno === 'M' ? 'Mañana' : 'Tarde' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer py-1">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-sm fw-bold" id="btnEnviarAviso">
                        <i class="fas fa-paper-plane me-1"></i>Enviar Aviso
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL: Ver Aviso --}}
    <div class="modal fade" id="modalVerAviso" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#1e3a5f; color:#fff;">
                    <h6 class="modal-title" id="verAvisoTitulo">Detalle del Aviso</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="verAvisoCuerpo">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function () {
            if (window.jQuery) {

                $('#tablaAvisos').DataTable({
                    "order": [[0, "desc"]],
                    "pageLength": 10,
                    "language": {
                        "search": "Buscar:",
                        "lengthMenu": "Mostrar _MENU_ registros",
                        "paginate": { "next": "Siguiente", "previous": "Anterior" },
                        "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                        "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                        "infoFiltered": "(filtrado de _MAX_ registros)",
                        "zeroRecords": "No se encontraron registros",
                        "emptyTable": "No hay avisos registrados"
                    },
                    "dom": "<'row mb-2'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
                });

                $('#destinoTipo').on('change', function () {
                    const val = $(this).val();
                    $('#destinoIdWrapper').toggle(val !== 'colegio_completo');
                    $('#selectorCiclo').toggle(val === 'ciclo');
                    $('#selectorGrado').toggle(val === 'grado_curso');
                });

                $('#archivoAdjunto').on('change', function () {
                    const file = this.files[0];
                    const preview = $('#previewAdjunto');
                    preview.html('');
                    if (!file) return;
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = e => preview.html('<img src="' + e.target.result + '" class="img-thumbnail" style="max-height:150px;">');
                        reader.readAsDataURL(file);
                    } else {
                        preview.html('<span class="text-muted small"><i class="fas fa-file-pdf text-danger me-1"></i>' + file.name + '</span>');
                    }
                });

                $('#btnEnviarAviso').on('click', function () {
                    const formData = new FormData($('#formNuevoAviso')[0]);
                    const tipo = $('#destinoTipo').val();
                    formData.delete('destino_id');
                    if (tipo === 'ciclo') {
                        formData.set('destino_id', $('#destinoCicloId').val());
                    } else if (tipo === 'grado_curso') {
                        formData.set('destino_id', $('#destinoGradoId').val());
                    }
                    $.ajax({
                        url: '{{ route("academica.avisos.store") }}',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (res) {
                            $('#modalNuevoAviso').modal('hide');
                            Swal.fire('Enviado', res.message, 'success').then(() => location.reload());
                        },
                        error: function (xhr) {
                            const errors = xhr.responseJSON && xhr.responseJSON.errors;
                            const msg = errors ? Object.values(errors).flat().join('<br>') : 'Error al enviar el aviso.';
                            Swal.fire('Error', msg, 'error');
                        }
                    });
                });

                $(document).on('click', '.btn-ver-aviso', function () {
                    const id = $(this).data('id');
                    $('#verAvisoCuerpo').html('<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>');
                    $('#modalVerAviso').modal('show');
                    $.get('{{ url("academica/avisos") }}/' + id, function (data) {
                        const ext = data.archivo_url ? data.archivo_url.split('.').pop().toLowerCase() : null;
                        const imgs = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                        const adjuntoHtml = data.archivo_url
                            ? (imgs.includes(ext)
                                ? '<img src="' + data.archivo_url + '" class="img-fluid rounded mt-2" style="max-height:300px;">'
                                : '<a href="' + data.archivo_url + '" target="_blank" class="btn btn-sm btn-outline-danger mt-2"><i class="fas fa-file-pdf me-1"></i>Ver PDF adjunto</a>')
                            : '<span class="text-muted">Sin adjunto</span>';
                        $('#verAvisoTitulo').text(data.aviso.titulo);
                        $('#verAvisoCuerpo').html(
                            '<dl class="row mb-0" style="font-size:0.8rem;">' +
                            '<dt class="col-sm-3">Fecha</dt><dd class="col-sm-9">' + data.aviso.fecha + '</dd>' +
                            '<dt class="col-sm-3">Destino</dt><dd class="col-sm-9">' + data.destino_label + '</dd>' +
                            '<dt class="col-sm-3">Enviado por</dt><dd class="col-sm-9">' + data.colaborador + '</dd>' +
                            '<dt class="col-sm-3">Estado</dt><dd class="col-sm-9">' + data.aviso.estado + ' (' + data.aviso.total_enviados + ' correos)</dd>' +
                            '<dt class="col-sm-3">Mensaje</dt><dd class="col-sm-9">' + (data.aviso.mensaje ? data.aviso.mensaje.replace(/\n/g, '<br>') : '<em>Sin mensaje</em>') + '</dd>' +
                            '<dt class="col-sm-3">Adjunto</dt><dd class="col-sm-9">' + adjuntoHtml + '</dd>' +
                            '</dl>'
                        );
                    });
                });

            } else {
                alert("Error crítico: jQuery no se ha cargado. Revise app.blade.php");
            }
        };
    </script>

</x-app-layout>