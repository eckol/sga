{{-- Modal Crear --}}
<div class="modal fade" id="modalCrear" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('colaboradores.store') }}" method="POST" class="modal-content"
            enctype="multipart/form-data">
            @csrf
            <div class="modal-header p-2 bg-primary text-white">
                <h6 class="modal-title">Nuevo Colaborador</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-2">
                <div class="row g-2">
                    <div class="col-md-3 text-center d-flex flex-column align-items-center justify-content-center">
                        <img src="{{ asset('img/colaborador.jpg') }}" alt="Foto" id="preview_foto_crear"
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
                                    @foreach($ciudades as $c)
                                        <option value="{{ $c->id }}">{{ $c->ciudad }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mt-2">
                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Estado Civil</label>
                        <select name="estado_civil_id" class="form-select form-select-sm" required>
                            @foreach($estadosciviles as $ec)
                                <option value="{{ $ec->id }}">{{ $ec->estado_civil }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Tipo Colaborador</label>
                        <select name="tipo_colaborador_id" class="form-select form-select-sm" required>
                            @foreach($tipos as $t)
                                <option value="{{ $t->id }}">{{ $t->tipo_colaborador }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Teléfono</label>
                        <input type="text" name="telefono" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label mb-0 fw-bold">Gmaps Link</label>
                        <input type="text" name="ubicacion" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">Email Particular</label>
                        <input type="email" name="email_particular" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">Email Institucional</label>
                        <input type="email" name="email_institucional" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Passwd</label>
                        <input type="text" name="passwd" id="crear_passwd" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2 mt-1 d-flex flex-column align-items-center justify-content-center">
                        <label class="form-label mb-1 fw-bold text-center">Activo</label>
                        <label class="toggle">
                            <input type="hidden" name="activo" value="No">
                            <input type="checkbox" name="activo" id="crear_activo" value="Sí" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>

                <div class="row g-2 mt-2">
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">Título 1</label>
                        <input type="text" name="titulo1" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">Título 2</label>
                        <input type="text" name="titulo2" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">Título 3</label>
                        <input type="text" name="titulo3" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Años Serv.</label>
                        <input type="number" name="anios_servicio" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Seguro</label>
                        <input type="text" name="seguro" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label mb-0 fw-bold">G.S.</label>
                        <input type="text" name="gsangre" class="form-control form-control-sm" placeholder="O+">
                    </div>
                    <div class="col-md-2 mt-1 d-flex flex-column align-items-center justify-content-center">
                        <label class="form-label mb-1 fw-bold text-center">Enf. Cronica</label>
                        <label class="toggle">
                            <input type="hidden" name="enf_cronica" value="No">
                            <input type="checkbox" name="enf_cronica" value="Sí" id="crear_enf">
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Reloj</label>
                        <input type="text" name="reloj" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Pass MEC</label>
                        <input type="text" name="passwd_mec" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label mb-0 fw-bold">Observaciones</label>
                        <textarea name="observaciones" class="form-control form-control-sm" rows="1"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer p-1">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success btn-sm">Guardar Colaborador</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Editar --}}
<div class="modal fade" id="modalEditar" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="formEditar" method="POST" class="modal-content" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="modal-header p-2 bg-primary text-white">
                <h6 class="modal-title">Modificar Colaborador</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-2">
                <div class="row g-2">
                    <div class="col-md-3 text-center d-flex flex-column align-items-center justify-content-center">
                        <img src="{{ asset('img/colaborador.jpg') }}" alt="Foto" id="preview_foto_editar"
                            class="rounded-circle shadow mb-2" style="width: 125px; height: 125px; object-fit: cover;">
                        <input type="file" name="foto" class="form-control form-control-sm mt-1" accept="image/*"
                            onchange="previewFoto(this, 'preview_foto_editar')" style="font-size: 0.65rem;">
                    </div>
                    <div class="col-md-9">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label mb-0 fw-bold">Nombres</label>
                                <input type="text" name="nombres" id="edit_nombres" class="form-control form-control-sm"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-0 fw-bold">Apellidos</label>
                                <input type="text" name="apellidos" id="edit_apellidos"
                                    class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-0 fw-bold">Cédula Id.</label>
                                <input type="number" name="cid" id="edit_cid" class="form-control form-control-sm"
                                    required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-0 fw-bold">Fecha de Nac.</label>
                                <input type="date" name="fnac" id="edit_fnac" class="form-control form-control-sm"
                                    required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-0 fw-bold">Sexo</label>
                                <select name="sexo_id" id="edit_sexo_id" class="form-select form-select-sm" required>
                                    @foreach($sexos as $s)
                                        <option value="{{ $s->id }}">{{ $s->sexo }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-0 fw-bold">Nacionalidad</label>
                                <select name="nacionalidad_id" id="edit_nacionalidad_id"
                                    class="form-select form-select-sm" required>
                                    @foreach($nacionalidades as $n)
                                        <option value="{{ $n->id }}">{{ $n->nacionalidad }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-0 fw-bold">Dirección</label>
                                <input type="text" name="direccion" id="edit_direccion"
                                    class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-0 fw-bold">Barrio</label>
                                <input type="text" name="barrio" id="edit_barrio" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-0 fw-bold">Ciudad</label>
                                <select name="ciudad_id" id="edit_ciudad_id" class="form-select form-select-sm"
                                    required>
                                    @foreach($ciudades as $c)
                                        <option value="{{ $c->id }}">{{ $c->ciudad }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mt-2">
                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Estado Civil</label>
                        <select name="estado_civil_id" id="edit_estado_civil_id" class="form-select form-select-sm"
                            required>
                            @foreach($estadosciviles as $ec)
                                <option value="{{ $ec->id }}">{{ $ec->estado_civil }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Tipo Colaborador</label>
                        <select name="tipo_colaborador_id" id="edit_tipo_colaborador_id"
                            class="form-select form-select-sm" required>
                            @foreach($tipos as $t)
                                <option value="{{ $t->id }}">{{ $t->tipo_colaborador }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Teléfono</label>
                        <input type="text" name="telefono" id="edit_telefono" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label mb-0 fw-bold">Gmaps Link</label>
                        <input type="text" name="ubicacion" id="edit_ubicacion" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">Email Particular</label>
                        <input type="email" name="email_particular" id="edit_email_particular"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">Email Institucional</label>
                        <input type="email" name="email_institucional" id="edit_email_institucional"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Passwd</label>
                        <input type="text" name="passwd" id="edit_passwd" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2 mt-1 d-flex flex-column align-items-center justify-content-center">
                        <label class="form-label mb-1 fw-bold text-center">Activo</label>
                        <label class="toggle">
                            <input type="hidden" name="activo" value="No">
                            <input type="checkbox" name="activo" id="edit_activo" value="Sí">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>

                <div class="row g-2 mt-2">
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">Título 1</label>
                        <input type="text" name="titulo1" id="edit_titulo1" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">Título 2</label>
                        <input type="text" name="titulo2" id="edit_titulo2" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">Título 3</label>
                        <input type="text" name="titulo3" id="edit_titulo3" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Años Serv.</label>
                        <input type="number" name="anios_servicio" id="edit_anios_servicio"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Seguro</label>
                        <input type="text" name="seguro" id="edit_seguro" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label mb-0 fw-bold">G.S.</label>
                        <input type="text" name="gsangre" id="edit_gsangre" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2 mt-1 d-flex flex-column align-items-center justify-content-center">
                        <label class="form-label mb-1 fw-bold text-center">Enf. Cronica</label>
                        <label class="toggle">
                            <input type="hidden" name="enf_cronica" value="No">
                            <input type="checkbox" name="enf_cronica" value="Sí" id="edit_enf_cronica">
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Reloj</label>
                        <input type="text" name="reloj" id="edit_reloj" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Pass MEC</label>
                        <input type="text" name="passwd_mec" id="edit_passwd_mec" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label mb-0 fw-bold">Observaciones</label>
                        <textarea name="observaciones" id="edit_observaciones" class="form-control form-control-sm"
                            rows="1"></textarea>
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

<script>
    function previewFoto(input, imgId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById(imgId).src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>