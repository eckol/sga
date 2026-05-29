{{-- Modal Crear --}}
<div class="modal fade" id="modalCrear" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('alumnos.store') }}" method="POST" class="modal-content" enctype="multipart/form-data">
            @csrf
            <div class="modal-header p-2 bg-primary text-white">
                <h6 class="modal-title">Nuevo Alumno</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-2">
                <div class="row g-2">
                    <div class="col-md-3 text-center d-flex flex-column align-items-center justify-content-center">
                        <img src="{{ asset('img/alumnos/alumno.jpg') }}" alt="Foto" id="preview_foto_crear"
                            class="rounded-circle shadow mb-2" style="width: 125px; height: 125px; object-fit: cover;">
                        <input type="file" name="foto" class="form-control form-control-sm mt-1" accept="image/*"
                            onchange="previewFoto(this, 'preview_foto_crear')" style="font-size: 0.65rem;">
                    </div>
                    <div class="col-md-9">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label mb-0 fw-bold">Nombres</label>
                                <input type="text" name="nombres" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-0 fw-bold">Apellidos</label>
                                <input type="text" name="apellidos" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-0 fw-bold">Cédula Id.</label>
                                <input type="number" name="cid" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-0 fw-bold">Fecha de Nac.</label>
                                <input type="date" name="fnac" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-0 fw-bold">Sexo</label>
                                <select name="sexo_id" class="form-select form-select-sm" required>
                                    @foreach($sexos as $s)
                                        <option value="{{ $s->id }}">{{ $s->sexo }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-0 fw-bold">Nacionalidad</label>
                                <select name="nacionalidad_id" class="form-select form-select-sm" required>
                                    @foreach($nacionalidades as $n)
                                        <option value="{{ $n->id }}">{{ $n->nacionalidad }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-0 fw-bold">Dirección</label>
                                <input type="text" name="direccion" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-0 fw-bold">Barrio</label>
                                <input type="text" name="barrio" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-0 fw-bold">Ciudad</label>
                                <select name="ciudad_id" class="form-select form-select-sm" required>
                                    <option value="">Seleccionar...</option>
                                    @foreach($ciudades as $c)
                                        <option value="{{ $c->id }}">{{ $c->ciudad }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-11">
                        <label class="form-label mb-0 fw-bold">Gmaps Link</label>
                        <input type="url" name="gmaps" id="crear_gmaps" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-primary btn-sm w-100" onclick="irGmaps('crear_gmaps')"
                            title="Ver ubicación"
                            style="height: calc(1.5em + 0.5rem + 2px); padding: 0.25rem 0.5rem;">Ir</button>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Teléfono</label>
                        <input type="text" name="telefono" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Email</label>
                        <input type="email" name="email" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Passwd</label>
                        <input type="text" name="passwd" id="crear_passwd" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-secondary btn-sm w-100 fw-bold"
                            onclick="generarPass('crear_passwd')" title="Generar Contraseña"
                            style="height: calc(1.5em + 0.5rem + 2px); padding: 0.25rem 0.5rem;"><i
                                class="fa-solid fa-wand-magic-sparkles"></i></button>
                    </div>

                    <div class="col-md-2 mt-1 d-flex flex-column align-items-center justify-content-center">
                        <label class="form-label mb-1 fw-bold text-center">Activo</label>
                        <label class="toggle">
                            <input type="hidden" name="activo" value="No">
                            <input type="checkbox" name="activo" id="crear_activo" value="Sí" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Vive Con</label>
                        <select name="vivecon_id" class="form-select form-select-sm" required>
                            @foreach($vivecon as $vc)
                                <option value="{{ $vc->id }}">{{ $vc->vive_con ?? $vc->vivecon }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label mb-0 fw-bold">Salud</label>
                        <input type="text" name="salud" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-5">
                        <label class="form-label mb-0 fw-bold">Observaciones</label>
                        <input type="text" name="observaciones" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Cédula Madre</label>
                        <input type="number" name="cid_madre" id="crear_cid_madre" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-primary btn-sm w-100"
                            onclick="abrirModalResponsableEditar('madres', 'crear_cid_madre')"
                            title="Editar">Editar</button>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Cédula Padre</label>
                        <input type="number" name="cid_padre" id="crear_cid_padre" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-primary btn-sm w-100"
                            onclick="abrirModalResponsableEditar('padres', 'crear_cid_padre')"
                            title="Editar">Editar</button>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Cédula Encargado</label>
                        <input type="number" name="cid_encargado" id="crear_cid_encargado"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-primary btn-sm w-100"
                            onclick="abrirModalResponsableEditar('encargados', 'crear_cid_encargado')"
                            title="Editar">Editar</button>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Parentesco</label>
                        <select name="parentesco_id" class="form-select form-select-sm">
                            <option value="">Seleccionar...</option>
                            @foreach($parentescos as $p)
                                <option value="{{ $p->id }}">{{ $p->parentesco ?? $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer p-1">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success btn-sm">Guardar Alumno</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Editar --}}
<div class="modal fade" id="modalEditar" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form id="formEditar" method="POST" class="modal-content" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="modal-header p-2 bg-primary text-white">
                <h6 class="modal-title">Modificar Alumno</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-2" style="font-size: 0.8rem;">
                <ul class="nav nav-tabs" id="modalAlumnoTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active py-1" id="datos-tab" data-bs-toggle="tab"
                            data-bs-target="#tab-datos" type="button">Datos Personales</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link py-1" id="responsables-tab" data-bs-toggle="tab"
                            data-bs-target="#tab-responsables" type="button">Responsables</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link py-1" id="inscripciones-tab" data-bs-toggle="tab"
                            data-bs-target="#tab-inscripciones" type="button">Inscripciones</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link py-1" id="asistencia-tab" data-bs-toggle="tab"
                            data-bs-target="#tab-asistencia" type="button">Asistencia</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link py-1" id="faltas-tab" data-bs-toggle="tab" data-bs-target="#tab-faltas"
                            type="button">Faltas</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link py-1" id="entrevistas-tab" data-bs-toggle="tab"
                            data-bs-target="#tab-entrevistas" type="button">Entrevistas</button>
                    </li>
                </ul>

                <div class="tab-content pt-2" id="modalAlumnoTabsContent">
                    {{-- Tab 1: Datos Personales --}}
                    <div class="tab-pane fade show active" id="tab-datos" role="tabpanel">
                        <div class="row g-2">
                            <div
                                class="col-md-3 text-center d-flex flex-column align-items-center justify-content-center">
                                <img src="{{ asset('img/alumnos/alumno.jpg') }}" alt="Foto" id="preview_foto_editar"
                                    class="rounded-circle shadow mb-2"
                                    style="width: 100px; height: 100px; object-fit: cover;">
                                <input type="file" name="foto" class="form-control form-control-xs mt-1"
                                    accept="image/*" onchange="previewFoto(this, 'preview_foto_editar')"
                                    style="font-size: 0.6rem;">
                            </div>
                            <div class="col-md-9">
                                <div class="row g-1">
                                    <div class="col-md-6">
                                        <label class="form-label mb-0 fw-bold">Nombres</label>
                                        <input type="text" name="nombres" id="edit_nombres"
                                            class="form-control form-control-sm" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label mb-0 fw-bold">Apellidos</label>
                                        <input type="text" name="apellidos" id="edit_apellidos"
                                            class="form-control form-control-sm" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label mb-0 fw-bold">Cédula Id.</label>
                                        <input type="number" name="cid" id="edit_cid"
                                            class="form-control form-control-sm" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label mb-0 fw-bold">Fecha de Nac.</label>
                                        <input type="date" name="fnac" id="edit_fnac"
                                            class="form-control form-control-sm" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label mb-0 fw-bold">Sexo</label>
                                        <select name="sexo_id" id="edit_sexo_id" class="form-select form-select-sm"
                                            required>
                                            @foreach($sexos as $s)
                                                <option value="{{ $s->id }}">{{ $s->sexo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label mb-0 fw-bold">Nacionalidad</label>
                                        <select name="nacionalidad_id" id="edit_nacionalidad_id"
                                            class="form-select form-select-sm" required>
                                            @foreach($nacionalidades as $n)
                                                <option value="{{ $n->id }}">{{ $n->nacionalidad }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label mb-0 fw-bold">Dirección</label>
                                        <input type="text" name="direccion" id="edit_direccion"
                                            class="form-control form-control-sm" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label mb-0 fw-bold">Barrio</label>
                                        <input type="text" name="barrio" id="edit_barrio"
                                            class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label mb-0 fw-bold">Ciudad</label>
                                        <select name="ciudad_id" id="edit_ciudad_id" class="form-select form-select-sm"
                                            required>
                                            <option value="">Seleccionar...</option>
                                            @foreach($ciudades as $c)
                                                <option value="{{ $c->id }}">{{ $c->ciudad }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label mb-0 fw-bold">Teléfono</label>
                                        <input type="text" name="telefono" id="edit_telefono"
                                            class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-11">
                                <label class="form-label mb-0 fw-bold">Gmaps Link</label>
                                <input type="url" name="gmaps" id="edit_gmaps" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-primary btn-sm w-100"
                                    onclick="irGmaps('edit_gmaps')" title="Ver ubicación"
                                    style="height: calc(1.5em + 0.5rem + 2px); padding: 0.25rem 0.5rem;">Ir</button>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label mb-0 fw-bold">Email</label>
                                <input type="email" name="email" id="edit_email" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-0 fw-bold">Passwd</label>
                                <input type="text" name="passwd" id="edit_passwd" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-secondary btn-sm w-100 fw-bold"
                                    onclick="generarPass('edit_passwd')" title="Generar Contraseña"
                                    style="height: calc(1.5em + 0.5rem + 2px); padding: 0.25rem 0.5rem;"><i
                                        class="fa-solid fa-wand-magic-sparkles"></i></button>
                            </div>

                            <div class="col-md-2 mt-1 d-flex flex-column align-items-center justify-content-center">
                                <label class="form-label mb-1 fw-bold text-center">Activo</label>
                                <label class="toggle">
                                    <input type="hidden" name="activo" value="No">
                                    <input type="checkbox" name="activo" id="edit_activo" value="Sí">
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label mb-0 fw-bold">Vive Con</label>
                                <select name="vivecon_id" id="edit_vivecon_id" class="form-select form-select-sm"
                                    required>
                                    @foreach($vivecon as $vc)
                                        <option value="{{ $vc->id }}">{{ $vc->vive_con ?? $vc->vivecon }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label mb-0 fw-bold">Salud</label>
                                <input type="text" name="salud" id="edit_salud" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-7">
                                <label class="form-label mb-0 fw-bold">Observaciones</label>
                                <input type="text" name="observaciones" id="edit_observaciones"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>

                    {{-- Tab 2: Responsables --}}
                    <div class="tab-pane fade" id="tab-responsables" role="tabpanel">
                        <div class="row g-2">
                            {{-- Madre --}}
                            <div class="col-md-12 border-bottom pb-1">
                                <div class="row g-1 text-primary">
                                    <div class="col-md-2">
                                        <label class="form-label mb-0 fw-bold">Madre CID</label>
                                        <input type="number" name="cid_madre" id="edit_cid_madre"
                                            class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-label mb-0 fw-bold">Nombre Madre</label>
                                        <input type="text" id="info_madre_nombre"
                                            class="form-control form-control-sm bg-light" readonly>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-primary btn-sm w-100"
                                            onclick="abrirModalResponsableEditar('madres', 'edit_cid_madre')"
                                            title="Editar" style="height: calc(1.5em + 0.5rem + 2px);">Editar</button>
                                    </div>
                                </div>
                            </div>
                            {{-- Padre --}}
                            <div class="col-md-12 border-bottom pb-1">
                                <div class="row g-1 text-success">
                                    <div class="col-md-2">
                                        <label class="form-label mb-0 fw-bold">Padre CID</label>
                                        <input type="number" name="cid_padre" id="edit_cid_padre"
                                            class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-label mb-0 fw-bold">Nombre Padre</label>
                                        <input type="text" id="info_padre_nombre"
                                            class="form-control form-control-sm bg-light" readonly>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-success btn-sm w-100"
                                            onclick="abrirModalResponsableEditar('padres', 'edit_cid_padre')"
                                            title="Editar" style="height: calc(1.5em + 0.5rem + 2px);">Editar</button>
                                    </div>
                                </div>
                            </div>
                            {{-- Encargado --}}
                            <div class="col-md-12 border-bottom pb-1">
                                <div class="row g-1 text-secondary">
                                    <div class="col-md-2">
                                        <label class="form-label mb-0 fw-bold">Encargado CID</label>
                                        <input type="number" name="cid_encargado" id="edit_cid_encargado"
                                            class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label mb-0 fw-bold">Nombre Encargado</label>
                                        <input type="text" id="info_encargado_nombre"
                                            class="form-control form-control-sm bg-light" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 fw-bold text-dark">Parentesco</label>
                                        <select name="parentesco_id" id="edit_parentesco_id"
                                            class="form-select form-select-sm">
                                            <option value="">Seleccionar...</option>
                                            @foreach($parentescos as $p)
                                                <option value="{{ $p->id }}">{{ $p->parentesco ?? $p->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-secondary btn-sm w-100"
                                            onclick="abrirModalResponsableEditar('encargados', 'edit_cid_encargado')"
                                            title="Editar" style="height: calc(1.5em + 0.5rem + 2px);">Editar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 3: Inscripciones --}}
                    <div class="tab-pane fade" id="tab-inscripciones" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover" style="font-size: 0.75rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Fecha</th>
                                        <th>Año</th>
                                        <th>Grado/Curso</th>
                                        <th>Firmante</th>
                                        <th>Rol</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody id="table-inscripciones-historial">
                                    {{-- Se cargará vía AJAX --}}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tab 4: Asistencia --}}
                    <div class="tab-pane fade" id="tab-asistencia" role="tabpanel">

                        <style>
                            /* ── Calendarios de asistencia ── */
                            .asist-anio-sel {
                                font-size: 0.72rem;
                                padding: 2px 8px;
                                border-radius: 6px;
                                border: 1px solid #ced4da;
                            }

                            .cal-asist-wrap {
                                display: grid;
                                grid-template-columns: repeat(5, minmax(0, 1fr));
                                gap: 6px;
                                margin-top: 6px;
                            }

                            .cal-asist-card {
                                border: 1px solid #dee2e6;
                                border-radius: 8px;
                                padding: 6px 4px 4px;
                                background: #fff;
                                min-width: 0;
                            }

                            .cal-asist-title {
                                text-align: center;
                                font-size: 0.68rem;
                                font-weight: 600;
                                color: #495057;
                                margin-bottom: 4px;
                                text-transform: uppercase;
                                letter-spacing: 0.03em;
                            }

                            .cal-asist-grid {
                                display: grid;
                                grid-template-columns: repeat(7, 1fr);
                                gap: 1px;
                            }

                            .cal-asist-grid .cal-dh {
                                text-align: center;
                                font-size: 0.52rem;
                                font-weight: 600;
                                color: #adb5bd;
                                padding-bottom: 2px;
                            }

                            .cal-asist-grid .cal-dc {
                                text-align: center;
                                font-size: 0.58rem;
                                font-weight: 500;
                                width: 100%;
                                max-width: 22px;
                                aspect-ratio: 1;
                                line-height: 22px;
                                border-radius: 50%;
                                margin: 0 auto;
                            }

                            .cal-dc.dc-presente {
                                background: #198754;
                                color: #fff;
                            }

                            .cal-dc.dc-ausente {
                                background: #dc3545;
                                color: #fff;
                            }

                            .cal-dc.dc-justif {
                                background: #ffc107;
                                color: #000;
                            }

                            .cal-dc.dc-tardanza {
                                background: #0dcaf0;
                                color: #000;
                            }

                            .cal-dc.dc-feriado {
                                background: #e9ecef;
                                color: #adb5bd;
                            }

                            .cal-dc.dc-finde {
                                color: #dee2e6;
                            }

                            .cal-dc.dc-vacio {
                                visibility: hidden;
                            }

                            .cal-asist-resumen {
                                display: flex;
                                justify-content: center;
                                gap: 4px;
                                margin-top: 4px;
                                font-size: 0.58rem;
                            }

                            .cal-res-badge {
                                padding: 1px 5px;
                                border-radius: 10px;
                                font-weight: 600;
                            }

                            .cr-p {
                                background: #d1e7dd;
                                color: #0a3622;
                            }

                            .cr-a {
                                background: #f8d7da;
                                color: #58151c;
                            }

                            .cr-j {
                                background: #fff3cd;
                                color: #664d03;
                            }

                            .cr-t {
                                background: #cff4fc;
                                color: #055160;
                            }

                            #tab-asistencia .spinner-asist {
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                padding: 20px;
                                color: #6c757d;
                                font-size: 0.8rem;
                            }
                        </style>

                        {{-- Selector de año lectivo --}}
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span style="font-size:0.72rem; color:#6c757d; width: 90px;">Año lectivo:</span>
                            <select id="asist-anio-sel" class="asist-anio-sel" style="width: 90px;">
                                @php $ay = date('Y'); @endphp
                                @foreach([$ay - 1, $ay, $ay + 1] as $yr)
                                    <option value="{{ $yr }}" {{ $yr == $ay ? 'selected' : '' }}>{{ $yr }}</option>
                                @endforeach
                            </select>
                            <span style="font-size:0.65rem; color:#adb5bd; margin-left:4px;">
                                <span
                                    style="display:inline-block;width:9px;height:9px;border-radius:50%;background:#198754"></span>
                                Presente &nbsp;
                                <span
                                    style="display:inline-block;width:9px;height:9px;border-radius:50%;background:#dc3545"></span>
                                Ausente &nbsp;
                                <span
                                    style="display:inline-block;width:9px;height:9px;border-radius:50%;background:#ffc107"></span>
                                Justif. &nbsp;
                                <span
                                    style="display:inline-block;width:9px;height:9px;border-radius:50%;background:#0dcaf0"></span>
                                Tard.
                            </span>
                        </div>

                        {{-- Contenedor de los 10 calendarios --}}
                        <div id="asist-calendarios-wrap">
                            <div class="spinner-asist">
                                <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                                Cargando asistencia...
                            </div>
                        </div>

                    </div>

                    {{-- Tab 5: Faltas --}}
                    <div class="tab-pane fade" id="tab-faltas" role="tabpanel">
                        <div class="table-responsive mt-1">
                            <table id="tabla-faltas-alumno" class="table table-sm table-bordered table-hover"
                                style="font-size: 0.75rem; width:100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 10px;">ID</th>
                                        <th style="width: 10px;">Fecha</th>
                                        <th>Indicador</th>
                                        <th style="width: 10px;">Grado/Curso</th>
                                        <th style="width: 30%;">Asignatura</th>
                                        <th class="text-center" style="width:50px;">Editar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Se cargará vía AJAX --}}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tab 6: Entrevistas --}}
                    <div class="tab-pane fade" id="tab-entrevistas" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mt-2 mb-1">
                            <h6 class="fw-bold text-secondary mb-0" style="font-size: 0.75rem;">Historial de Entrevistas
                            </h6>
                            <a href="{{ route('entrevistas.index') }}" class="btn btn-primary btn-xs py-1 px-2"
                                style="font-size: 0.65rem;">
                                Ir al Módulo <i class="fas fa-external-link-alt ms-1"></i>
                            </a>
                        </div>
                        <div class="table-responsive mt-1">
                            <table id="tabla-entrevistas-alumno" class="table table-sm table-bordered table-hover"
                                style="font-size: 0.75rem; width:100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 10px;">Fecha</th>
                                        <th style="width: 10px;">Tipo</th>
                                        <th style="width: 25%;">Atendido por</th>
                                        <th>Motivo</th>
                                        <th class="text-center" style="width:50px;">Editar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Se cargará vía AJAX --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer p-1">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-save me-1"></i>Actualizar
                    Cambios</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalInscribir" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('inscripciones.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-success text-white p-2">
                <h6 class="modal-title">Inscripción: <span id="ins_nombre_alumno"></span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <input type="hidden" name="alumno_cid" id="ins_alumno_cid">
                <input type="hidden" name="fecha" value="{{ date('Y-m-d') }}">

                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label">Año Lectivo</label>
                        <select name="anio_lectivo" class="form-select form-select-sm">
                            @foreach($anios as $a)
                                <option value="{{ $a }}" {{ $a > date('Y') ? 'selected' : '' }}>{{ $a }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-primary">Grado Actual</label>
                        <input type="text" id="inscribir_grado_actual" class="form-control form-control-sm bg-light"
                            readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Grado/Curso a Inscribir</label>
                        <select name="grado_curso_id" id="select_grado_nuevo" class="form-select form-select-sm"
                            required>
                            @foreach($grados as $g)
                                <option value="{{ $g->id }}">{{ $g->gradocurso }} ({{ $g->turno }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Quién firma el contrato</label>
                        <select name="firmante_rol" id="select_firmante" class="form-select form-select-sm" required>
                            <option value="Padre">Padre</option>
                            <option value="Madre">Madre</option>
                            <option value="Encargado">Encargado</option>
                        </select>
                    </div>
                    <div class="col-md-6 mt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="aut_mochila" value="Sí" id="mochila">
                            <label class="form-check-label" for="mochila">Autoriza revisión mochila</label>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="aut_foto" value="Sí" id="foto">
                            <label class="form-check-label" for="foto">Autoriza uso de imagen</label>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <label class="form-label mb-0 fw-bold">Estado</label>
                        <select name="estado" class="form-select form-select-sm">
                            <option value="Matriculado" selected>Matriculado</option>
                            <option value="Egresado">Egresado</option>
                            <option value="Trasladado">Trasladado</option>
                            <option value="Abandono">Abandono</option>
                        </select>
                    </div>
                    <div class="col-md-6 mt-2">
                        <label class="form-label mb-0 fw-bold">Fecha Baja</label>
                        <input type="date" name="fecha_baja" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-12 mt-2">
                        <label class="form-label mb-0 fw-bold">Observaciones</label>
                        <input type="text" name="observaciones" class="form-control form-control-sm"
                            placeholder="Opcional...">
                    </div>
                </div>
            </div>
            <div class="modal-footer p-1">
                <button type="submit" class="btn btn-success btn-sm w-100">Finalizar Inscripción e Imprimir</button>
            </div>
        </form>
    </div>
</div>


<script>
    function irGmaps(inputId) {
        let val = document.getElementById(inputId).value.trim();
        if (val) {
            if (!val.startsWith('http')) {
                val = 'https://' + val;
            }
            window.open(val, '_blank');
        } else {
            alert('El campo de Gmaps está vacío.');
        }
    }

    function generarPass(inputId) {
        const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        let pass = '';
        for (let i = 0; i < 6; i++) {
            pass += letters.charAt(Math.floor(Math.random() * letters.length));
        }
        const year = new Date().getFullYear().toString().slice(-2);
        pass += year;
        document.getElementById(inputId).value = pass;
    }

    function previewFoto(input, imgId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById(imgId).src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function abrirModalResponsableEditar(tipo, inputId) {
        const cid = document.getElementById(inputId).value;
        if (!cid) {
            Swal.fire('Atención', 'Debe ingresar un número de cédula.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Buscando...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        fetch(`/rrhh/responsables/${tipo}/buscar/${cid}`)
            .then(response => response.json())
            .then(res => {
                Swal.close();
                if (res.success) {
                    const d = res.data;
                    $('#formResponsableEditar').attr('action', `/rrhh/responsables/${tipo}/${d.id}`);
                    $('#res_edit_titulo').text('Modificar ' + tipo.slice(0, -1));
                    $('#res_edit_nombre').val(d.nombre);
                    $('#res_edit_cid').val(d.cid);
                    $('#res_edit_email').val(d.email);
                    $('#res_edit_tel1').val(d.telefono1);
                    $('#res_edit_tel2').val(d.telefono2);
                    $('#res_edit_direccion').val(d.direccion);
                    $('#res_edit_barrio').val(d.barrio);
                    $('#res_edit_ciudad_id').val(d.ciudad_id);
                    $('#res_edit_profesion').val(d.profesion);
                    $('#res_edit_trabajo').val(d.lugartrabajo);
                    $('#res_edit_ruc').val(d.ruc);
                    $('#res_edit_dv').val(d.dv);

                    var myModal = new bootstrap.Modal(document.getElementById('modalResponsableEditar'));
                    myModal.show();
                } else {
                    Swal.fire('No encontrado', 'No existe un registro con esa cédula en la tabla de ' + tipo, 'error');
                }
            })
            .catch(error => {
                Swal.close();
                console.error(error);
                Swal.fire('Error', 'Hubo un problema al buscar los datos.', 'error');
            });
    }

    // Función cargarEntrevistasTab removida (ahora se maneja vía DataTables API en index.blade.php)
</script>

{{-- ════════════════════════════════════════════════ --}}
{{-- Modales de Edición de Entrevistas (abiertos desde el tab Entrevistas del modal Alumno) --}}
{{-- ════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalEditarEntrevistaAlumno" tabindex="-1" style="z-index: 1085;">
    <div class="modal-dialog">
        <form id="formEditarEntrevistaAlumno" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header p-2 bg-warning text-dark">
                <h6 class="modal-title fw-bold" style="font-size: 0.85rem;"><i class="fas fa-edit me-1"></i>
                    Modificar Entrevista Alumno</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-2">
                <div class="row g-2" style="font-size: 0.75rem;">
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">Fecha</label>
                        <input type="date" name="fecha" id="edit_ent_al_fecha" class="form-control form-control-sm"
                            required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label mb-0 fw-bold">Entrevistador</label>
                        <select name="colaborador_id" id="edit_ent_al_colaborador" class="form-select form-select-sm"
                            required>
                            @foreach($colaboradores as $c)
                                <option value="{{ $c->id }}">{{ $c->apellidos }}, {{ $c->nombres }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label mb-0 fw-bold">Motivo Principal</label>
                        <input type="text" name="motivo" id="edit_ent_al_motivo" class="form-control form-control-sm"
                            required>
                    </div>
                    <div class="col-12">
                        <label class="form-label mb-0 fw-bold">Observaciones / Acuerdos</label>
                        <textarea name="observaciones" id="edit_ent_al_obs" class="form-control form-control-sm"
                            rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer p-1">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-warning btn-sm fw-bold text-dark">Actualizar Registro</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEditarEntrevistaResponsable" tabindex="-1" style="z-index: 1085;">
    <div class="modal-dialog">
        <form id="formEditarEntrevistaResponsable" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header p-2 bg-warning text-dark">
                <h6 class="modal-title fw-bold" style="font-size: 0.85rem;"><i class="fas fa-edit me-1"></i>
                    Modificar Acta de Responsables</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-2">
                <div class="row g-2" style="font-size: 0.75rem;">
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">Fecha</label>
                        <input type="date" name="fecha" id="edit_ent_res_fecha" class="form-control form-control-sm"
                            required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label mb-0 fw-bold">Atendido por</label>
                        <select name="colaborador_id" id="edit_ent_res_colaborador" class="form-select form-select-sm"
                            required>
                            @foreach($colaboradores as $c)
                                <option value="{{ $c->id }}">{{ $c->apellidos }}, {{ $c->nombres }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label mb-0 fw-bold">Testigos en Acta</label>
                        <select name="testigos[]" id="edit_ent_res_testigos"
                            class="form-select form-select-sm select2-modal" multiple style="width: 100%;">
                            @foreach($colaboradores as $c)
                                <option value="{{ $c->id }}">{{ $c->apellidos }}, {{ $c->nombres }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label mb-0 fw-bold">Motivo del Encuentro</label>
                        <input type="text" name="motivo" id="edit_ent_res_motivo" class="form-control form-control-sm"
                            required>
                    </div>
                    <div class="col-12">
                        <label class="form-label mb-0 fw-bold">Resoluciones / Acuerdos</label>
                        <textarea name="observaciones" id="edit_ent_res_obs" class="form-control form-control-sm"
                            rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer p-1">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-warning btn-sm fw-bold text-dark">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

{{-- ════════════════════════════════════════════════ --}}
{{-- Modal Editar Falta (abierto desde el tab Faltas del modal Alumno) --}}
{{-- ════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalEditarFalta" tabindex="-1" style="z-index: 1080;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header p-2 bg-warning">
                <h6 class="modal-title"><i class="fas fa-edit me-1"></i> Editar Falta</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="formEditarFalta" action="">
                @csrf
                @method('PUT')
                <div class="modal-body" style="font-size: 0.8rem;">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label form-label-sm fw-bold">Fecha <span
                                    class="text-danger">*</span></label>
                            <input type="date" name="fecha" id="falta_editar_fecha" class="form-control form-control-sm"
                                required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label form-label-sm fw-bold">Grado/Curso <span
                                    class="text-danger">*</span></label>
                            <select name="grado_curso_id" id="falta_editar_grado" class="form-select form-select-sm"
                                required>
                                <option value="">— Seleccionar —</option>
                                @foreach($grados as $g)
                                    <option value="{{ $g->id }}">{{ $g->gradocurso }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label form-label-sm fw-bold">Alumno <span
                                    class="text-danger">*</span></label>
                            <select name="alumno_id" id="falta_editar_alumno" class="form-select form-select-sm"
                                required>
                                <option value="">— Cargando —</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label form-label-sm fw-bold">Asignatura <span
                                    class="text-danger">*</span></label>
                            <select name="asignatura_id" id="falta_editar_asignatura" class="form-select form-select-sm"
                                required>
                                <option value="">— Cargando —</option>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label form-label-sm fw-bold">Docente</label>
                            <input type="text" id="falta_editar_docente" class="form-control form-control-sm" readonly
                                placeholder="(se completa automáticamente)">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label form-label-sm fw-bold">Tipo de Falta <span
                                    class="text-danger">*</span></label>
                            <select name="indicador_falta_id" id="falta_editar_indicador"
                                class="form-select form-select-sm" required>
                                <option value="">— Seleccionar —</option>
                                @foreach($indicadores as $ind)
                                    <option value="{{ $ind->id }}">{{ $ind->indicador_falta }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-1">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="fas fa-save me-1"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal para Editar Datos del Responsable --}}
<div class="modal fade" id="modalResponsableEditar" tabindex="-1" style="z-index: 1070;">
    <div class="modal-dialog modal-lg shadow-lg">
        <form id="formResponsableEditar" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header p-2 bg-primary text-white">
                <h6 class="modal-title" id="res_edit_titulo">Modificar Responsable</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 0.8rem;">
                <div class="row g-2">
                    <div class="col-md-8">
                        <label class="form-label mb-0 fw-bold">Nombre Completo</label>
                        <input type="text" name="nombre" id="res_edit_nombre" class="form-control form-control-sm"
                            required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">C.I.D.</label>
                        <input type="number" name="cid" id="res_edit_cid" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">Email</label>
                        <input type="email" name="email" id="res_edit_email" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Teléfono 1</label>
                        <input type="text" name="telefono1" id="res_edit_tel1" class="form-control form-control-sm"
                            required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Teléfono 2</label>
                        <input type="text" name="telefono2" id="res_edit_tel2" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label mb-0 fw-bold">Dirección</label>
                        <input type="text" name="direccion" id="res_edit_direccion" class="form-control form-control-sm"
                            required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">Barrio</label>
                        <input type="text" name="barrio" id="res_edit_barrio" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">Ciudad</label>
                        <select name="ciudad_id" id="res_edit_ciudad_id" class="form-select form-select-sm" required>
                            @foreach($ciudades as $c)
                                <option value="{{ $c->id }}">{{ $c->ciudad }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">Profesión</label>
                        <input type="text" name="profesion" id="res_edit_profesion"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label mb-0 fw-bold">Lugar de Trabajo</label>
                        <input type="text" name="lugartrabajo" id="res_edit_trabajo"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">RUC</label>
                        <input type="text" name="ruc" id="res_edit_ruc" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label mb-0 fw-bold">DV</label>
                        <input type="text" name="dv" id="res_edit_dv" maxlength="1"
                            class="form-control form-control-sm">
                    </div>
                </div>
            </div>
            <div class="modal-footer p-1">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success btn-sm">Actualizar Cambios</button>
            </div>
        </form>
    </div>
</div>