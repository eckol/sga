{{-- Modal Crear --}}
<div class="modal fade" id="modalCrear" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('alumnos.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header p-2 bg-primary text-white">
                <h6 class="modal-title">Nuevo Alumno</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-2">
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">Nombres</label>
                        <input type="text" name="nombres" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">Apellidos</label>
                        <input type="text" name="apellidos" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">Cédula Id.</label>
                        <input type="number" name="cid" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">Fecha de Nac.</label>
                        <input type="date" name="fnac" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">Sexo</label>
                        <select name="sexo_id" class="form-select form-select-sm" required>
                            @foreach($sexos as $s)
                                <option value="{{ $s->id }}">{{ $s->sexo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">Nacionalidad</label>
                        <select name="nacionalidad_id" class="form-select form-select-sm" required>
                            @foreach($nacionalidades as $n)
                                <option value="{{ $n->id }}">{{ $n->nacionalidad }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">Ciudad</label>
                        <select name="ciudad_id" class="form-select form-select-sm" required>
                            @foreach($ciudades as $c)
                                <option value="{{ $c->id }}">{{ $c->ciudad }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label mb-0 fw-bold">Dirección</label>
                        <input type="text" name="direccion" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">Teléfono</label>
                        <input type="text" name="telefono" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">Email</label>
                        <input type="email" name="email" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">Vive Con</label>
                        <select name="vivecon_id" class="form-select form-select-sm" required>
                            @foreach($vivecon as $vc)
                                <option value="{{ $vc->id }}">{{ $vc->vive_con }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">Gmaps Link</label>
                        <input type="url" name="gmaps" class="form-control form-control-sm">
                    </div>
                </div>
            </div>
            <div class="modal-footer p-1">
                <button type="submit" class="btn btn-success btn-sm">Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Editar --}}
<div class="modal fade" id="modalEditar" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="formEditar" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header p-2 bg-primary text-white">
                <h6 class="modal-title">Modificar Alumno</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-2">
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">Nombres</label>
                        <input type="text" name="nombres" id="edit_nombres" class="form-control form-control-sm"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">Apellidos</label>
                        <input type="text" name="apellidos" id="edit_apellidos" class="form-control form-control-sm"
                            required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Cédula Id.</label>
                        <input type="number" name="cid" id="edit_cid" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Fecha de Nac.</label>
                        <input type="date" name="fnac" id="edit_fnac" class="form-control form-control-sm" required>
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
                        <select name="nacionalidad_id" id="edit_nacionalidad_id" class="form-select form-select-sm"
                            required>
                            @foreach($nacionalidades as $n)
                                <option value="{{ $n->id }}">{{ $n->nacionalidad }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">Dirección</label>
                        <input type="text" name="direccion" id="edit_direccion" class="form-control form-control-sm"
                            required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Barrio</label>
                        <input type="text" name="barrio" id="edit_barrio" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Ciudad</label>
                        <select name="ciudad_id" id="edit_ciudad_id" class="form-select form-select-sm" required>
                            @foreach($ciudades as $c)
                                <option value="{{ $c->id }}">{{ $c->ciudad }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-11">
                        <label class="form-label mb-0 fw-bold">Gmaps Link</label>
                        <input type="url" name="gmaps" id="edit_gmaps" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label mb-0 fw-bold">Ir</label>
                        <input type="text" name="#" id="#" class="form-control form-control-sm">
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
                        <input type="passwd" name="passwd" id="edit_passwd" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label mb-0 fw-bold">Generar</label>
                        <input type="text" name="#" id="#" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Activo</label>
                        <input type="text" name="activo" id="edit_activo" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Matriculado</label>
                        <input type="text" name="matriculado" id="edit_matriculado"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Vive Con</label>
                        <select name="vivecon_id" id="edit_vivecon_id" class="form-select form-select-sm" required>
                            @foreach($vivecon as $vc)
                                <option value="{{ $vc->id }}">{{ $vc->vive_con }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
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
                        <input type="text" name="madre_cid" id="edit_madre_cid" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Cédula Padre</label>
                        <input type="text" name="padre_cid" id="edit_padre_cid" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Cédula Encargado</label>
                        <input type="text" name="encargado_cid" id="edit_encargado_cid"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Parentesco</label>
                        <input type="text" name="parentezco_id" id="edit_parentezco_id"
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