{{-- Modal Crear --}}
<div class="modal fade" id="modalCrear" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('periodos-laborales.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header p-2 bg-primary text-white">
                <h6 class="modal-title">Nuevo Período Laboral</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-2">
                <div class="row g-2">
                    <div class="col-md-12">
                        <label class="form-label mb-0 fw-bold">Cédula del Colaborador</label>
                        <input type="text" name="cid" class="form-control form-control-sm" required
                            placeholder="Ingrese CID para buscar">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">Fecha Ingreso</label>
                        <input type="date" name="fecha_ingreso" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">Fecha Egreso</label>
                        <input type="date" name="fecha_egreso" class="form-control form-control-sm">
                        <small class="text-muted" style="font-size: 0.6rem;">Dejar vacío si continúa activo</small>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label mb-0 fw-bold">Observación</label>
                        <textarea name="observacion" class="form-control form-control-sm" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer p-1">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success btn-sm">Registrar Período</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Editar --}}
<div class="modal fade" id="modalEditar" tabindex="-1">
    <div class="modal-dialog">
        <form id="formEditar" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header p-2 bg-primary text-white">
                <h6 class="modal-title">Modificar Período Laboral</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-2">
                <div class="row g-2">
                    <div class="col-md-12">
                        <label class="form-label mb-0 fw-bold">Colaborador</label>
                        <input type="text" id="edit_colaborador_nombre" class="form-control form-control-sm bg-light"
                            readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">Fecha Ingreso</label>
                        <input type="date" name="fecha_ingreso" id="edit_fecha_ingreso"
                            class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">Fecha Egreso</label>
                        <input type="date" name="fecha_egreso" id="edit_fecha_egreso"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label mb-0 fw-bold">Observación</label>
                        <textarea name="observacion" id="edit_observacion" class="form-control form-control-sm"
                            rows="2"></textarea>
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