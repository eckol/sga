{{--
|--------------------------------------------------------------------------
| resources/views/academica/asistencia/modales.blade.php
|--------------------------------------------------------------------------
| Modal de ficha del alumno con tabs:
|   1. Datos básicos + responsables
|   2. Asistencia — calendario mensual con navegación
|   3. Resumen anual
|--------------------------------------------------------------------------
--}}

<div class="modal fade" id="modalAlumnoAsist" tabindex="-1" style="z-index: 1060;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header p-2 bg-primary text-white">
                <h6 class="modal-title">
                    <i class="fas fa-user-graduate me-1"></i>
                    <span id="modal-alumno-nombre">—</span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- Campos ocultos de contexto --}}
            <input type="hidden" id="masist_alumno_id">

            <div class="modal-body p-2" style="font-size: 0.78rem;">

                {{-- Tabs --}}
                <ul class="nav nav-tabs" id="modalAlumnoAsistTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active py-1" data-bs-toggle="tab"
                            data-bs-target="#masist-tab-datos" type="button">
                            <i class="fas fa-id-card me-1"></i>Datos
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link py-1" data-bs-toggle="tab"
                            data-bs-target="#masist-tab-asistencia" type="button">
                            <i class="fas fa-calendar-check me-1"></i>Asistencia
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link py-1" data-bs-toggle="tab"
                            data-bs-target="#masist-tab-resumen" type="button">
                            <i class="fas fa-chart-bar me-1"></i>Resumen anual
                        </button>
                    </li>
                </ul>

                <div class="tab-content pt-2">

                    {{-- ── Tab 1: Datos básicos ── --}}
                    <div class="tab-pane fade show active" id="masist-tab-datos">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label mb-0 fw-bold">Madre / Tutora</label>
                                <input type="text" id="masist_madre"
                                    class="form-control form-control-sm bg-light" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-0 fw-bold">Padre / Tutor</label>
                                <input type="text" id="masist_padre"
                                    class="form-control form-control-sm bg-light" readonly>
                            </div>
                            <div class="col-md-12 mt-1">
                                <label class="form-label mb-0 fw-bold">Historial de inscripciones</label>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered table-hover"
                                        style="font-size:0.7rem;">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Año</th>
                                                <th>Grado/Curso</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody id="masist_inscripciones">
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">Cargando...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Tab 2: Asistencia mensual con calendario ── --}}
                    <div class="tab-pane fade" id="masist-tab-asistencia">

                        {{-- Navegación de meses --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <button id="masist-btn-prev" type="button"
                                class="btn btn-outline-secondary btn-sm py-0 px-2"
                                style="font-size:0.7rem;">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <span id="masist-cal-titulo" class="fw-bold" style="font-size:0.8rem;">—</span>
                            <button id="masist-btn-next" type="button"
                                class="btn btn-outline-secondary btn-sm py-0 px-2"
                                style="font-size:0.7rem;">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>

                        {{-- Calendario --}}
                        <div id="masist-cal-container" class="py-1">
                            <p class="text-muted text-center">Cargando...</p>
                        </div>

                        {{-- Leyenda --}}
                        <div class="mt-2 d-flex gap-3" style="font-size:0.65rem; color:#6c757d;">
                            <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#198754;margin-right:3px"></span>Presente</span>
                            <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#dc3545;margin-right:3px"></span>Ausente</span>
                            <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#ffc107;margin-right:3px"></span>Justificado</span>
                            <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#0dcaf0;margin-right:3px"></span>Tardanza</span>
                        </div>
                    </div>

                    {{-- ── Tab 3: Resumen anual ── --}}
                    <div class="tab-pane fade" id="masist-tab-resumen">
                        <div id="masist-resumen">
                            <p class="text-muted text-center py-2">Cargando...</p>
                        </div>
                    </div>

                </div>{{-- end tab-content --}}
            </div>

            <div class="modal-footer p-1">
                <button type="button" class="btn btn-secondary btn-sm"
                    data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>
