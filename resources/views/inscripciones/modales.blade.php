{{-- Modal Editar Inscripción --}}
<div class="modal fade" id="modalEditarIns" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="formEditarIns" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header p-2 bg-primary text-white">
                <h6 class="modal-title">Modificar Inscripción</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-2">
                <div class="row g-2">
                    <div class="col-md-5">
                        <label class="form-label mb-0 fw-bold">Alumno</label>
                        <input type="text" id="edit_alumno_nombre" class="form-control form-control-sm bg-light"
                            readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">C.I. Alumno</label>
                        <input type="number" name="alumno_cid" id="edit_alumno_cid"
                            class="form-control form-control-sm bg-light" readonly>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Fecha</label>
                        <input type="date" name="fecha" id="edit_fecha" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-0 fw-bold">Año Lectivo</label>
                        <input type="number" name="anio_lectivo" id="edit_anio_lectivo"
                            class="form-control form-control-sm" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">Grado/Curso</label>
                        <select name="grado_curso_id" id="edit_grado_curso_id" class="form-select form-select-sm"
                            required>
                            @foreach($grados as $g)
                                <option value="{{ $g->id }}">{{ $g->gradocurso }} ({{ $g->turno }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">Procede</label>
                        <input type="text" name="procede" id="edit_procede" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">Forma de Pago</label>
                        <select name="fpago" id="edit_fpago" class="form-select form-select-sm" required>
                            <option value="Contado">Contado</option>
                            <option value="Mensual">Mensual</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">Firmante Rol</label>
                        <select name="firmante_rol" id="edit_firmante_rol" class="form-select form-select-sm" required>
                            <option value="Padre">Padre</option>
                            <option value="Madre">Madre</option>
                            <option value="Encargado">Encargado</option>
                            <option value="No especificado">No especificado</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">Firmante Nombre</label>
                        <input type="text" name="firmante_nombre" id="edit_firmante_nombre"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">Monto Matrícula (Gs)</label>
                        <input type="number" name="monto_matricula" id="edit_monto_matricula"
                            class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0 fw-bold">Monto Anualidad (Gs)</label>
                        <input type="number" name="monto_anualidad" id="edit_monto_anualidad"
                            class="form-control form-control-sm" required>
                    </div>

                    <div class="col-md-2 mt-1 d-flex flex-column align-items-center justify-content-center">
                        <label class="form-label mb-1 fw-bold text-center" style="font-size:0.7rem;">Aut.
                            Mochila</label>
                        <label class="toggle">
                            <input type="hidden" name="aut_mochila" value="No">
                            <input type="checkbox" name="aut_mochila" id="edit_aut_mochila" value="Sí">
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="col-md-2 mt-1 d-flex flex-column align-items-center justify-content-center">
                        <label class="form-label mb-1 fw-bold text-center" style="font-size:0.7rem;">Aut. Foto</label>
                        <label class="toggle">
                            <input type="hidden" name="aut_foto" value="No">
                            <input type="checkbox" name="aut_foto" id="edit_aut_foto" value="Sí">
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Estado</label>
                        <select name="estado" id="edit_estado" class="form-select form-select-sm" required>
                            <option value="Matriculado">MATRICULADO</option>
                            <option value="Egresado">EGRESADO</option>
                            <option value="Trasladado">TRASLADADO</option>
                            <option value="Abandono">ABANDONO</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0 fw-bold">Fecha Baja</label>
                        <input type="date" name="fecha_baja" id="edit_fecha_baja" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">Observaciones</label>
                        <input type="text" name="observaciones" id="edit_observaciones"
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