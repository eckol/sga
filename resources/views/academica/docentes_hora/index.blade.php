<x-app-layout>
    {{-- ═══════════════════════════════════════════════════════════════════════
    ESTILOS (Alineados con calendario_examenes)
    ═══════════════════════════════════════════════════════════════════════ --}}
    <style>
        :root {
            --cst-navy: #1e3a5f;
            --cst-dark: #0d2137;
            --cst-accent: #2d6bb5;
            --cst-light: #e8f1fb;
            --cst-border: #b0c8e8;
        }

        .form-select-sm,
        .form-control-sm {
            border-radius: 6px !important;
            font-size: 0.75rem !important;
            padding-top: 0.2rem;
            padding-bottom: 0.2rem;
        }

        .form-label-sm {
            font-size: 0.75rem !important;
            font-weight: 600;
            color: var(--cst-navy);
            margin-bottom: 0.25rem;
        }

        .btn-sm {
            font-size: 0.75rem !important;
        }

        .titulo-ciclo {
            background: linear-gradient(135deg, var(--cst-dark) 0%, var(--cst-navy) 100%);
            color: #fff;
            font-size: 0.82rem;
            font-weight: 700;
            padding: 0.5rem 1rem;
            border-radius: 8px 8px 0 0;
            letter-spacing: 0.05em;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-grilla {
            border: 1px solid var(--cst-border);
            border-top: none;
            border-radius: 0 0 8px 8px;
            overflow-x: auto;
            background: #fff;
            box-shadow: 0 2px 6px rgba(30, 58, 95, 0.07);
        }

        .grilla-docente th,
        .grilla-docente td {
            font-size: 0.72rem !important;
            padding: 0.22rem 0.4rem !important;
            vertical-align: middle;
        }

        .grilla-docente thead th {
            background-color: var(--cst-navy);
            color: #fff;
            font-weight: 600;
            text-align: center;
            white-space: nowrap;
        }

        .grilla-docente thead th.th-hora {
            background-color: var(--cst-dark);
            width: 80px;
        }

        .grilla-docente tbody td.td-hora {
            font-weight: 700;
            text-align: center;
            background-color: #f8fbff;
            color: var(--cst-navy);
            border-right: 2px solid #dee2e6;
        }

        .celda-item {
            min-height: 25px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .badge-grado {
            display: block;
            background-color: #e8f1ff;
            color: #084298;
            border: 1px solid #b0c8e8;
            border-radius: 4px;
            padding: 2px 4px;
            font-size: 0.65rem;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .badge-grado:hover {
            background-color: #cfe2ff;
        }

        .filter-card {
            background: #f4f8ff;
            border: 1px solid var(--cst-border);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .btn-export-group .btn {
            font-size: 0.75rem;
            padding: 0.3rem 0.7rem;
            font-weight: 600;
            letter-spacing: 0.03em;
        }

        .toast-custom {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            min-width: 230px;
            font-size: 0.78rem;
        }

        @media print {
            .titulo-ciclo {
                background: #1e3a5f !important;
                -webkit-print-color-adjust: exact;
            }

            .grilla-docente thead th {
                background: #1e3a5f !important;
                color: #fff !important;
                -webkit-print-color-adjust: exact;
            }

            .badge-grado {
                border: 1px solid #ccc !important;
                -webkit-print-color-adjust: exact;
            }

            .btn-export-group,
            .filter-card,
            .modal,
            nav,
            header {
                display: none !important;
            }
        }
    </style>

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                <i class="fas fa-chalkboard-teacher me-2 text-primary"></i>Horarios por Docente
            </h2>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-full mx-auto px-4">

            {{-- ═══════════ PANEL DE FILTROS + EXPORTACIÓN ═══════════ --}}
            <div class="filter-card shadow-sm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="select-docente" class="form-label-sm">
                            <i class="fas fa-user-tie me-1"></i>Docente
                        </label>
                        <select id="select-docente" class="form-select form-select-sm">
                            <option value="">— Seleccione un docente —</option>
                            @foreach($colaboradores as $col)
                                <option value="{{ $col->id }}">{{ $col->apellidos }}, {{ $col->nombres }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="input-asignatura" class="form-label-sm">
                            <i class="fas fa-book me-1"></i>Asignatura(s) que imparte
                        </label>
                        <input type="text" id="input-asignatura" class="form-control form-control-sm" readonly
                            placeholder="Seleccione un docente">
                    </div>

                    {{-- Separador visual --}}
                    <div class="col-md-1 text-center d-none d-md-block">
                        <div style="height:2.5rem; border-left:1px solid var(--cst-border); margin: auto;"></div>
                    </div>

                    {{-- Botones de exportación --}}
                    <div class="col-md-4">
                        <label class="form-label-sm d-block mb-1">
                            <i class="fas fa-file-export me-1"></i>Exportar:
                        </label>
                        <div class="d-flex gap-2 btn-export-group">
                            <button id="btnExportExcel" class="btn btn-success btn-sm shadow-sm" disabled>
                                <i class="fas fa-file-excel me-1"></i>Excel
                            </button>
                            <button id="btnExportPDF" class="btn btn-danger btn-sm shadow-sm" disabled>
                                <i class="fas fa-file-pdf me-1"></i>PDF
                            </button>
                            <button id="btnPrint" class="btn btn-secondary btn-sm shadow-sm" disabled>
                                <i class="fas fa-print me-1"></i>Imprimir
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══════════ GRILLA ═══════════ --}}
            <div id="container-grilla" style="display: none;" class="mb-4">
                <div class="titulo-ciclo">
                    <span><i class="fas fa-calendar-alt me-2 opacity-75"></i>CRONOGRAMA SEMANAL POR DOCENTE</span>
                    <span id="label-docente-nombre" class="badge bg-light text-dark"
                        style="font-size: 0.72rem; padding: 4px 10px; border-radius: 20px;"></span>
                </div>
                <div class="card-grilla shadow-sm">
                    <table class="table table-sm table-bordered table-hover mb-0 grilla-docente" id="tabla-print">
                        <thead>
                            <tr>
                                <th class="th-hora">Horas</th>
                                <th>Lunes</th>
                                <th>Martes</th>
                                <th>Miércoles</th>
                                <th>Jueves</th>
                                <th>Viernes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $dias = [1, 2, 3, 4, 5]; @endphp
                            @foreach($horas as $hora)
                                <tr>
                                    <td class="td-hora">{{ $hora->modulo }}</td>
                                    @foreach($dias as $dia)
                                        <td id="celda-{{ $dia }}-{{ $hora->id }}">
                                            <div class="celda-item" data-dia="{{ $dia }}" data-hora="{{ $hora->id }}"></div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Mensaje de espera --}}
            <div id="msg-espera" class="text-center py-5 text-muted shadow-sm bg-white border rounded">
                <i class="fas fa-user-tie fa-3x mb-3 opacity-25"></i>
                <p class="mb-0">Seleccione un docente para visualizar su horario de clase.</p>
            </div>

            {{-- Modal para cambiar Grado/Curso --}}
            <div class="modal fade" id="modalEditarGrado" tabindex="-1">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content shadow-lg">
                        <div class="modal-header py-2"
                            style="background: linear-gradient(135deg, var(--cst-dark), var(--cst-navy)); color:#fff;">
                            <h6 class="modal-title" style="font-size: 0.82rem;">Cambiar Grado/Curso</h6>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                style="filter:invert(1)"></button>
                        </div>
                        <div class="modal-body py-3">
                            <input type="hidden" id="edit-horario-id">
                            <label class="form-label-sm">Nuevo Grado/Curso</label>
                            <select id="select-edit-grado" class="form-select form-select-sm">
                                <option value="">— Sin asignar —</option>
                                @foreach($gradosCursos as $gc)
                                    <option value="{{ $gc->id }}">{{ $gc->gradocurso }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer py-2">
                            <button type="button" class="btn btn-sm btn-secondary"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" id="btn-guardar-cambio"
                                class="btn btn-sm btn-primary">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Toast --}}
    <div class="toast-custom toast align-items-center text-white border-0" id="toastApp" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const selectDocente = document.getElementById('select-docente');
                const inputAsignatura = document.getElementById('input-asignatura');
                const containerGrilla = document.getElementById('container-grilla');
                const msgEspera = document.getElementById('msg-espera');
                const labelDocenteNombre = document.getElementById('label-docente-nombre');

                const btnExcel = document.getElementById('btnExportExcel');
                const btnPDF = document.getElementById('btnExportPDF');
                const btnPrint = document.getElementById('btnPrint');

                const toastEl = document.getElementById('toastApp');
                const toastMessage = document.getElementById('toastMessage');
                const toast = new bootstrap.Toast(toastEl);

                function showToast(msg, type = 'success') {
                    toastEl.classList.remove('bg-success', 'bg-danger');
                    toastEl.classList.add('bg-' + (type === 'success' ? 'success' : 'danger'));
                    toastMessage.textContent = msg;
                    toast.show();
                }

                function cargarHorario() {
                    const docenteId = selectDocente.value;
                    if (!docenteId) {
                        containerGrilla.style.display = 'none';
                        msgEspera.style.display = 'block';
                        inputAsignatura.value = '';
                        btnExcel.disabled = btnPDF.disabled = btnPrint.disabled = true;
                        return;
                    }

                    msgEspera.innerHTML = '<div class="spinner-border text-primary" role="status"></div><p class="mt-2">Cargando...</p>';

                    fetch(`{{ route('academica.docentes-hora.data') }}?colaborador_id=${docenteId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                inputAsignatura.value = data.asignaturas || 'N/A';
                                labelDocenteNombre.textContent = selectDocente.options[selectDocente.selectedIndex].text;
                                document.querySelectorAll('.celda-item').forEach(celda => celda.innerHTML = '');

                                Object.keys(data.mapa).forEach(dia => {
                                    Object.keys(data.mapa[dia]).forEach(horaId => {
                                        const celda = document.querySelector(`.celda-item[data-dia="${dia}"][data-hora="${horaId}"]`);
                                        if (celda) {
                                            data.mapa[dia][horaId].forEach(item => {
                                                const badge = document.createElement('div');
                                                badge.className = 'badge-grado';
                                                badge.style.textAlign = 'center';
                                                badge.innerHTML = `<strong>${item.grado_curso}</strong><br><small style="opacity: 0.85;">${item.asignatura}</small>`;
                                                badge.dataset.id = item.id;
                                                badge.dataset.gradoid = item.grado_curso_id;
                                                badge.addEventListener('click', function () {
                                                    document.getElementById('edit-horario-id').value = this.dataset.id;
                                                    document.getElementById('select-edit-grado').value = this.dataset.gradoid;
                                                    new bootstrap.Modal(document.getElementById('modalEditarGrado')).show();
                                                });
                                                celda.appendChild(badge);
                                            });
                                        }
                                    });
                                });

                                containerGrilla.style.display = 'block';
                                msgEspera.style.display = 'none';
                                btnExcel.disabled = btnPDF.disabled = btnPrint.disabled = false;
                            } else {
                                showToast(data.message, 'danger');
                            }
                        }).catch(() => showToast('Error de conexión', 'danger'))
                        .finally(() => {
                            msgEspera.innerHTML = '<i class="fas fa-user-tie fa-3x mb-3 opacity-25"></i><p class="mb-0">Seleccione un docente.</p>';
                        });
                }

                selectDocente.addEventListener('change', cargarHorario);

                document.getElementById('btn-guardar-cambio').addEventListener('click', function () {
                    const id = document.getElementById('edit-horario-id').value;
                    const gradoId = document.getElementById('select-edit-grado').value;
                    const btn = this;
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

                    fetch(`{{ route('academica.docentes-hora.update') }}`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ id: id, grado_curso_id: gradoId })
                    }).then(response => response.json()).then(data => {
                        if (data.success) {
                            showToast(data.message);
                            bootstrap.Modal.getInstance(document.getElementById('modalEditarGrado')).hide();
                            cargarHorario();
                        } else showToast(data.message, 'danger');
                    }).finally(() => { btn.disabled = false; btn.textContent = 'Guardar'; });
                });

                // ── EXPORTACIÓN ──
                btnExcel.addEventListener('click', function () {
                    const table = document.getElementById('tabla-print');
                    const wb = XLSX.utils.table_to_sheet(table);
                    const workbook = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(workbook, wb, "Horario");
                    XLSX.writeFile(workbook, `Horario_${labelDocenteNombre.textContent.replace(/ /g, '_')}.xlsx`);
                });

                btnPDF.addEventListener('click', function () {
                    const table = document.getElementById('tabla-print');
                    const rows = [];
                    table.querySelectorAll('thead tr').forEach(tr => {
                        const fila = [];
                        tr.querySelectorAll('th').forEach(th => fila.push({ text: th.innerText, style: 'thdr' }));
                        rows.push(fila);
                    });
                    table.querySelectorAll('tbody tr').forEach(tr => {
                        const fila = [];
                        tr.querySelectorAll('td').forEach(td => fila.push({ text: td.innerText, style: 'tcell' }));
                        rows.push(fila);
                    });

                    const docDef = {
                        pageOrientation: 'landscape',
                        content: [
                            { text: 'Horario por Docente - Santa Teresita', style: 'titulo', margin: [0, 0, 0, 10] },
                            { text: 'Docente: ' + labelDocenteNombre.textContent, style: 'subtitulo', margin: [0, 0, 0, 5] },
                            { text: 'Asignatura(s): ' + inputAsignatura.value, style: 'subtitulo', margin: [0, 0, 0, 15] },
                            { table: { headerRows: 1, widths: ['*', '*', '*', '*', '*', '*'], body: rows } }
                        ],
                        styles: {
                            titulo: { fontSize: 14, bold: true, color: '#1e3a5f' },
                            subtitulo: { fontSize: 10, bold: true, margin: [0, 5, 0, 5] },
                            thdr: { fontSize: 8, bold: true, color: '#fff', fillColor: '#1e3a5f', alignment: 'center' },
                            tcell: { fontSize: 8, alignment: 'center' }
                        }
                    };
                    pdfMake.createPdf(docDef).download(`Horario_${labelDocenteNombre.textContent.replace(/ /g, '_')}.pdf`);
                });

                btnPrint.addEventListener('click', () => window.print());
            });
        </script>
    @endpush
</x-app-layout>