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
                    <div class="col-md-2 mt-1 d-flex flex-column align-items-center justify-content-center">
                        <label class="form-label mb-1 fw-bold text-center">Matriculado</label>
                        <label class="toggle">
                            <input type="hidden" name="matriculado" value="No">
                            <input type="checkbox" name="matriculado" id="crear_matriculado" value="Sí">
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

                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Cédula Madre</label>
                        <input type="number" name="cid_madre" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Cédula Padre</label>
                        <input type="number" name="cid_padre" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Cédula Encargado</label>
                        <input type="number" name="cid_encargado" class="form-control form-control-sm">
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
    <div class="modal-dialog modal-lg">
        <form id="formEditar" method="POST" class="modal-content" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="modal-header p-2 bg-primary text-white">
                <h6 class="modal-title">Modificar Alumno</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-2">
                <div class="row g-2">
                    <div class="col-md-3 text-center d-flex flex-column align-items-center justify-content-center">
                        <img src="{{ asset('img/alumnos/alumno.jpg') }}" alt="Foto" id="preview_foto_editar"
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
                        <input type="url" name="gmaps" id="edit_gmaps" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-primary btn-sm w-100" onclick="irGmaps('edit_gmaps')"
                            title="Ver ubicación"
                            style="height: calc(1.5em + 0.5rem + 2px); padding: 0.25rem 0.5rem;">Ir</button>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Teléfono</label>
                        <input type="text" name="telefono" id="edit_telefono" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
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
                    <div class="col-md-2 mt-1 d-flex flex-column align-items-center justify-content-center">
                        <label class="form-label mb-1 fw-bold text-center">Matriculado</label>
                        <label class="toggle">
                            <input type="hidden" name="matriculado" value="No">
                            <input type="checkbox" name="matriculado" id="edit_matriculado" value="Sí">
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Vive Con</label>
                        <select name="vivecon_id" id="edit_vivecon_id" class="form-select form-select-sm" required>
                            @foreach($vivecon as $vc)
                                <option value="{{ $vc->id }}">{{ $vc->vive_con ?? $vc->vivecon }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label mb-0 fw-bold">Salud</label>
                        <input type="text" name="salud" id="edit_salud" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-5">
                        <label class="form-label mb-0 fw-bold">Observaciones</label>
                        <input type="text" name="observaciones" id="edit_observaciones"
                            class="form-control form-control-sm">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Cédula Madre</label>
                        <input type="number" name="cid_madre" id="edit_cid_madre" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Cédula Padre</label>
                        <input type="number" name="cid_padre" id="edit_cid_padre" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Cédula Encargado</label>
                        <input type="number" name="cid_encargado" id="edit_cid_encargado"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Parentesco</label>
                        <select name="parentesco_id" id="edit_parentesco_id" class="form-select form-select-sm">
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
                <button type="submit" class="btn btn-success btn-sm">Actualizar Cambios</button>
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
</script>