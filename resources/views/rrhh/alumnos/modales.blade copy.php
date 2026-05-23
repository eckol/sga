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
                            onclick="verResponsable('madres', 'crear_cid_madre')" title="Ver">Ver</button>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Cédula Padre</label>
                        <input type="number" name="cid_padre" id="crear_cid_padre" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-primary btn-sm w-100"
                            onclick="verResponsable('padres', 'crear_cid_padre')" title="Ver">Ver</button>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Cédula Encargado</label>
                        <input type="number" name="cid_encargado" id="crear_cid_encargado"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-primary btn-sm w-100"
                            onclick="verResponsable('encargados', 'crear_cid_encargado')" title="Ver">Ver</button>
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

                    <div class="col-md-7">
                        <label class="form-label mb-0 fw-bold">Observaciones</label>
                        <input type="text" name="observaciones" id="edit_observaciones"
                            class="form-control form-control-sm">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Cédula Madre</label>
                        <input type="number" name="cid_madre" id="edit_cid_madre" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-primary btn-sm w-100"
                            onclick="verResponsable('madres', 'edit_cid_madre')" title="Ver">Ver</button>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Cédula Padre</label>
                        <input type="number" name="cid_padre" id="edit_cid_padre" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-primary btn-sm w-100"
                            onclick="verResponsable('padres', 'edit_cid_padre')" title="Ver">Ver</button>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Cédula Encargado</label>
                        <input type="number" name="cid_encargado" id="edit_cid_encargado"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-primary btn-sm w-100"
                            onclick="verResponsable('encargados', 'edit_cid_encargado')" title="Ver">Ver</button>
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

    function verResponsable(tipo, inputId) {
        const cid = document.getElementById(inputId).value;
        if (!cid) {
            Swal.fire('Atención', 'Debe ingresar un número de cédula.', 'warning');
            return;
        }

        // Mostrar cargando
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
                    $('#res_ver_titulo').text('Datos de ' + tipo.slice(0, -1));
                    $('#res_ver_nombre').val(d.nombre);
                    $('#res_ver_cid').val(d.cid);
                    $('#res_ver_email').val(d.email);
                    $('#res_ver_tel1').val(d.telefono1);
                    $('#res_ver_tel2').val(d.telefono2);
                    $('#res_ver_direccion').val(d.direccion);
                    $('#res_ver_barrio').val(d.barrio);
                    $('#res_ver_profesion').val(d.profesion);
                    $('#res_ver_trabajo').val(d.lugartrabajo);
                    $('#res_ver_ruc').val(d.ruc + (d.dv ? '-' + d.dv : ''));

                    var myModal = new bootstrap.Modal(document.getElementById('modalResponsableVer'));
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
</script>

{{-- Modal para Visualizar Datos del Responsable --}}
<div class="modal fade" id="modalResponsableVer" tabindex="-1" style="z-index: 1060;">
    <div class="modal-dialog modal-lg shadow-lg">
        <div class="modal-content">
            <div class="modal-header p-2 bg-danger text-white">
                <h6 class="modal-title" id="res_ver_titulo">Datos del Responsable</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 0.8rem;">
                <div class="row g-2">
                    <div class="col-md-8">
                        <label class="form-label mb-0 fw-bold">Nombre Completo</label>
                        <input type="text" id="res_ver_nombre" class="form-control form-control-sm bg-light" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">C.I.D.</label>
                        <input type="text" id="res_ver_cid" class="form-control form-control-sm bg-light" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">Email</label>
                        <input type="text" id="res_ver_email" class="form-control form-control-sm bg-light" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Teléfono 1</label>
                        <input type="text" id="res_ver_tel1" class="form-control form-control-sm bg-light" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Teléfono 2</label>
                        <input type="text" id="res_ver_tel2" class="form-control form-control-sm bg-light" readonly>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label mb-0 fw-bold">Dirección</label>
                        <input type="text" id="res_ver_direccion" class="form-control form-control-sm bg-light"
                            readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">Barrio</label>
                        <input type="text" id="res_ver_barrio" class="form-control form-control-sm bg-light" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">Profesión</label>
                        <input type="text" id="res_ver_profesion" class="form-control form-control-sm bg-light"
                            readonly>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label mb-0 fw-bold">Lugar de Trabajo</label>
                        <input type="text" id="res_ver_trabajo" class="form-control form-control-sm bg-light" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">RUC</label>
                        <input type="text" id="res_ver_ruc" class="form-control form-control-sm bg-light" readonly>
                    </div>
                </div>
            </div>
            <div class="modal-footer p-1">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>