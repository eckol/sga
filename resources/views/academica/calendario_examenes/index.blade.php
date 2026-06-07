<x-app-layout>

    {{-- ═══════════════════════════════════════════════════════════════════════
         ESTILOS
    ═══════════════════════════════════════════════════════════════════════ --}}
    <style>
        /* ── Variables ── */
        :root {
            --cst-navy:    #1e3a5f;
            --cst-dark:    #0d2137;
            --cst-accent:  #2d6bb5;
            --cst-light:   #e8f1fb;
            --cst-border:  #b0c8e8;
        }

        /* ── Tipografía y base ── */
        .form-select-sm, .form-control-sm { border-radius: 6px !important; }

        /* ── Encabezado de bloque ── */
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
        .titulo-ciclo .badge-etapa {
            font-size: 0.7rem;
            font-weight: 400;
            opacity: 0.9;
            background: rgba(255,255,255,0.15);
            padding: 2px 8px;
            border-radius: 20px;
        }

        /* ── Card contenedor de grilla ── */
        .card-grilla {
            border: 1px solid var(--cst-border);
            border-top: none;
            border-radius: 0 0 8px 8px;
            overflow-x: auto;
            background: #fff;
            box-shadow: 0 2px 6px rgba(30,58,95,0.07);
        }

        /* ── Tabla ── */
        .grilla-calendario th,
        .grilla-calendario td {
            font-size: 0.72rem !important;
            padding: 0.22rem 0.4rem !important;
            vertical-align: middle;
        }
        .grilla-calendario thead th {
            background-color: var(--cst-navy);
            color: #fff;
            font-weight: 600;
            text-align: center;
            white-space: nowrap;
            position: sticky;
            top: 0;
            z-index: 1;
        }
        .grilla-calendario tbody tr:nth-child(even) { background-color: #f7faff; }

        /* Exportación via SheetJS + pdfMake, sin DataTables */

        /* ── Celdas editables ── */
        .celda-examen {
            cursor: pointer;
            transition: background-color 0.12s, transform 0.08s;
            min-width: 92px;
            text-align: center;
        }
        .celda-examen:hover {
            background-color: var(--cst-light) !important;
            transform: scale(1.015);
            z-index: 2;
            position: relative;
        }
        .celda-examen.celda-vacia { background-color: #fafafa; }
        .celda-examen .icono-agregar {
            font-size: 0.65rem;
            color: #b0c8e8;
            display: none;
        }
        .celda-examen:hover .icono-agregar { display: inline; }

        /* ── Badges de asignaturas ── */
        .asig-badge {
            display: inline-block;
            padding: 0.1rem 0.28rem;
            border-radius: 4px;
            border: 1px solid rgba(0,0,0,0.07);
            font-weight: 600;
            font-size: 0.66rem;
            line-height: 1.4;
        }
        .asig-bg-1  { background:#ffdce5!important; color:#7a1c24; }
        .asig-bg-2  { background:#dcefff!important; color:#084298; }
        .asig-bg-3  { background:#dcffe5!important; color:#0a4f30; }
        .asig-bg-4  { background:#fffac1!important; color:#6d4c00; }
        .asig-bg-5  { background:#ebdfff!important; color:#3b1a78; }
        .asig-bg-6  { background:#ffeadc!important; color:#85422d; }
        .asig-bg-7  { background:#dcfff7!important; color:#087a6d; }
        .asig-bg-8  { background:#ffdcf7!important; color:#7a1c5d; }
        .asig-bg-9  { background:#dcedff!important; color:#004a99; }
        .asig-bg-10 { background:#fff3dc!important; color:#7a581c; }
        .asig-bg-11 { background:#f1ffe5!important; color:#3e5f08; }
        .asig-bg-12 { background:#e5ebff!important; color:#2e3b7a; }
        .asig-bg-13 { background:#ffdfdc!important; color:#7a2e2a; }
        .asig-bg-14 { background:#dcfffb!important; color:#087a74; }
        .asig-bg-15 { background:#f3dcff!important; color:#5d2a7a; }
        .asig-bg-16 { background:#f7ffe5!important; color:#4e5f08; }
        .asig-bg-17 { background:#ffdce9!important; color:#7a1c3e; }
        .asig-bg-18 { background:#dcefff!important; color:#085d99; }
        .asig-bg-19 { background:#f8fbff!important; color:#666;    }
        .asig-bg-20 { background:#f0f0f0!important; color:#444;    }

        /* ── Modal ── */
        .modal-header-cst { background: linear-gradient(135deg, var(--cst-dark), var(--cst-navy)); color:#fff; }
        .modal-header-cst .btn-close { filter: invert(1); }

        /* ── Toast ── */
        .toast-cal {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            min-width: 230px;
            font-size: 0.78rem;
        }

        /* ── Sección ── */
        .tabla-grilla-seccion { margin-bottom: 2rem; }

        /* ── Panel de botones de exportación ── */
        .btn-export-group .btn {
            font-size: 0.75rem;
            padding: 0.3rem 0.7rem;
            font-weight: 600;
            letter-spacing: 0.03em;
        }

        /* ── Modal agregar bloque ── */
        .modal-header-add { background: linear-gradient(135deg, #0a4f30, #1a7a50); color:#fff; }
        .modal-header-add .btn-close { filter: invert(1); }

        /* ── Badge ciclo seleccionado en filtros ── */
        .filter-card {
            background: #f4f8ff;
            border: 1px solid var(--cst-border);
            border-radius: 8px;
        }

        /* ── Tabla de print: forzar colores ── */
        @media print {
            .titulo-ciclo { background: #1e3a5f !important; -webkit-print-color-adjust: exact; }
            .grilla-calendario thead th { background: #1e3a5f !important; color:#fff !important; -webkit-print-color-adjust: exact; }
            .asig-badge { border: 1px solid #ccc !important; -webkit-print-color-adjust: exact; }
            .btn-export-group, .filter-card, .modal, nav, header { display: none !important; }
            .tabla-grilla-seccion { page-break-inside: avoid; }
        }
    </style>

    {{-- ═══════════ CABECERA ═══════════ --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                <i class="fas fa-calendar-alt me-2 text-primary"></i>Calendario de Pruebas Escritas
            </h2>
            {{-- Botón Agregar nuevo bloque de calendario --}}
            <button class="btn btn-success btn-sm fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAgregarBloque">
                <i class="fas fa-plus-circle me-1"></i> Nuevo Bloque de Calendario
            </button>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-full mx-auto px-4">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show py-2 px-3 mb-3 shadow-sm" style="font-size:0.85rem">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- ═══════════ PANEL DE FILTROS + EXPORTACIÓN ═══════════ --}}
            <div class="filter-card p-3 mb-4 shadow-sm">
                <div class="row g-3 align-items-end">

                    {{-- Filtros --}}
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted mb-1">
                            <i class="fas fa-layer-group me-1"></i>Ciclo:
                        </label>
                        <select id="filterCiclo" class="form-select form-select-sm">
                            <option value="">Todos los Ciclos</option>
                            <option value="1er. Ciclo E.E.B.">1er. Ciclo E.E.B.</option>
                            <option value="2do. Ciclo E.E.B.">2do. Ciclo E.E.B.</option>
                            <option value="3er. Ciclo E.E.B." selected>3er. Ciclo E.E.B.</option>
                            <option value="Nivel Medio">Nivel Medio</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted mb-1">
                            <i class="fas fa-flag me-1"></i>Etapa:
                        </label>
                        <select id="filterEtapa" class="form-select form-select-sm">
                            <option value="">Todas</option>
                            <option value="1ra." selected>1ra.</option>
                            <option value="2da.">2da.</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted mb-1">
                            <i class="fas fa-tag me-1"></i>Tipo:
                        </label>
                        <select id="filterTipo" class="form-select form-select-sm">
                            <option value="">Todos</option>
                            <option value="Parcial" selected>Parcial</option>
                            <option value="Cierre">Cierre</option>
                        </select>
                    </div>

                    {{-- Separador visual --}}
                    <div class="col-md-1 text-center d-none d-md-block">
                        <div style="height:2rem; border-left:1px solid var(--cst-border); margin: auto;"></div>
                    </div>

                    {{-- Botones de exportación --}}
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-muted mb-1">
                            <i class="fas fa-download me-1"></i>Exportar / Imprimir:
                        </label>
                        <div class="d-flex gap-2 flex-wrap btn-export-group">
                            <button id="btnExportExcel" class="btn btn-success btn-sm shadow-sm">
                                <i class="fas fa-file-excel me-1"></i>Excel
                            </button>
                            <button id="btnExportPDF" class="btn btn-danger btn-sm shadow-sm">
                                <i class="fas fa-file-pdf me-1"></i>PDF
                            </button>
                            <button id="btnPrint" class="btn btn-secondary btn-sm shadow-sm">
                                <i class="fas fa-print me-1"></i>Imprimir
                            </button>
                            <small class="text-muted align-self-center ms-2">
                                <i class="fas fa-info-circle"></i> Exporta las secciones visibles
                            </small>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ═══════════ GRILLAS PIVOTADAS ═══════════ --}}
            @php
                /*
                 * ── Mapa completo de ciclos → IDs de grados_cursos ──
                 * Verificado con gc.csv:
                 *   Nivel Inicial     ciclo_id=1  IDs 1-6   (Jardin/PreEscolar - NO aparece en calendarios)
                 *   1er. Ciclo EEB    ciclo_id=2  IDs 7-12
                 *   2do. Ciclo EEB    ciclo_id=3  IDs 13-18
                 *   3er. Ciclo EEB    ciclo_id=4  IDs 19-24
                 *   Nivel Medio       ciclo_id=5  IDs 25-30
                 */
                $ciclosConfig = [
                    '1er. Ciclo E.E.B.' => [
                        7  => '1ER. GRADO A',
                        8  => '1ER. GRADO B',
                        9  => '2DO. GRADO A',
                        10 => '2DO. GRADO B',
                        11 => '3ER. GRADO A',
                        12 => '3ER. GRADO B',
                    ],
                    '2do. Ciclo E.E.B.' => [
                        13 => '4TO. GRADO A',
                        14 => '4TO. GRADO B',
                        15 => '5TO. GRADO A',
                        16 => '5TO. GRADO B',
                        17 => '6TO. GRADO A',
                        18 => '6TO. GRADO B',
                    ],
                    '3er. Ciclo E.E.B.' => [
                        19 => '7MO. GRADO A',
                        20 => '7MO. GRADO B',
                        21 => '8VO. GRADO A',
                        22 => '8VO. GRADO B',
                        23 => '9NO. GRADO A',
                        24 => '9NO. GRADO B',
                    ],
                    'Nivel Medio' => [
                        25 => '1ER. CURSO BC A',
                        26 => '1ER. CURSO BC B',
                        27 => '2DO. CURSO BC A',
                        28 => '2DO. CURSO BC B',
                        29 => '3ER. CURSO BC A',
                        30 => '3ER. CURSO BC B',
                    ],
                ];

                // Construir pivote: [ciclo][etapa][tipo][fecha][grado_id] = $examen
                $pivot = [];
                foreach ($examenes as $ex) {
                    $pivot[$ex->ciclo][$ex->etapa][$ex->tipo_prueba][$ex->fecha][$ex->grado_curso_id] = $ex;
                }

                // Variable para pasar el mapa al JS via @json //es imposible usar array literal en @json()
                $ciclosConfigJs = $ciclosConfig;
            @endphp

            @foreach ($ciclosConfig as $cicloLabel => $gradosDelCiclo)
                @php
                    $etapasTipos = [];
                    if (isset($pivot[$cicloLabel])) {
                        foreach ($pivot[$cicloLabel] as $etapa => $tipos) {
                            foreach ($tipos as $tipo => $fechas) {
                                $etapasTipos[] = ['etapa' => $etapa, 'tipo' => $tipo];
                            }
                        }
                    }
                    $cicloSlug = Str::slug($cicloLabel);
                @endphp

                @foreach ($etapasTipos as $idx => $et)
                    @php
                        $etapa = $et['etapa'];
                        $tipo  = $et['tipo'];
                        $fechasDelBloque = isset($pivot[$cicloLabel][$etapa][$tipo])
                            ? collect(array_keys($pivot[$cicloLabel][$etapa][$tipo]))->sort()->values()
                            : collect();
                        $tableId = 'dt-' . $cicloSlug . '-' . $idx;
                    @endphp

                    <div class="tabla-grilla-seccion"
                         data-ciclo="{{ $cicloLabel }}"
                         data-etapa="{{ $etapa }}"
                         data-tipo="{{ $tipo }}">

                        <div class="titulo-ciclo">
                            <span>
                                <i class="fas fa-graduation-cap me-2 opacity-75"></i>{{ $cicloLabel }}
                            </span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge-etapa">
                                    Etapa: {{ $etapa }} &nbsp;|&nbsp; {{ $tipo }}
                                </span>
                                {{-- Botón Editar Bloque --}}
                                <button class="btn btn-sm btn-bloque-editar py-0 px-2"
                                    style="background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.35); color:#fff; font-size:0.68rem; border-radius:5px; line-height:1.6;"
                                    data-ciclo="{{ $cicloLabel }}"
                                    data-etapa="{{ $etapa }}"
                                    data-tipo="{{ $tipo }}"
                                    data-fechas="{{ collect(array_keys($pivot[$cicloLabel][$etapa][$tipo]))->sort()->values()->implode(',') }}"
                                    title="Editar fechas de este bloque">
                                    <i class="fas fa-calendar-edit me-1"></i>Editar bloque
                                </button>
                                {{-- Botón Eliminar Bloque --}}
                                <button class="btn btn-sm btn-bloque-eliminar py-0 px-2"
                                    style="background:rgba(220,53,69,0.55); border:1px solid rgba(220,53,69,0.7); color:#fff; font-size:0.68rem; border-radius:5px; line-height:1.6;"
                                    data-ciclo="{{ $cicloLabel }}"
                                    data-etapa="{{ $etapa }}"
                                    data-tipo="{{ $tipo }}"
                                    data-fechas="{{ collect(array_keys($pivot[$cicloLabel][$etapa][$tipo]))->sort()->values()->implode(',') }}"
                                    title="Eliminar todas las filas de este bloque">
                                    <i class="fas fa-trash-alt me-1"></i>Eliminar bloque
                                </button>
                            </div>
                        </div>

                        <div class="card-grilla">
                            <div class="table-responsive">
                                <table id="{{ $tableId }}" class="table table-sm table-bordered table-hover grilla-calendario mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width:90px">Fecha</th>
                                            @foreach ($gradosDelCiclo as $gradoId => $gradoLabel)
                                                <th class="text-center">{{ $gradoLabel }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($fechasDelBloque as $fecha)
                                            <tr>
                                                <td class="text-center fw-semibold" style="white-space:nowrap;">
                                                    {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
                                                </td>
                                                @foreach ($gradosDelCiclo as $gradoId => $gradoLabel)
                                                    @php
                                                        $ex = $pivot[$cicloLabel][$etapa][$tipo][$fecha][$gradoId] ?? null;
                                                    @endphp
                                                    <td class="celda-examen {{ $ex ? '' : 'celda-vacia' }}"
                                                        data-id="{{ $ex->id ?? '' }}"
                                                        data-fecha="{{ $fecha }}"
                                                        data-etapa="{{ $etapa }}"
                                                        data-tipo="{{ $tipo }}"
                                                        data-grado="{{ $gradoId }}"
                                                        data-grado-label="{{ $gradoLabel }}"
                                                        data-asig1="{{ $ex->asignatura1 ?? '' }}"
                                                        data-asig2="{{ $ex->asignatura2 ?? '' }}"
                                                        data-asig3="{{ $ex->asignatura3 ?? '' }}">
                                                        @if($ex && $ex->asignatura1)
                                                            <span class="asig-badge asig-bg-{{ ($ex->asignatura1 % 20) + 1 }}">
                                                                {{ $ex->asignatura1_rel->abreviacion ?? '?' }}
                                                            </span>
                                                        @endif
                                                        @if($ex && $ex->asignatura2)
                                                            <br>
                                                            <span class="asig-badge asig-bg-{{ ($ex->asignatura2 % 20) + 1 }}">
                                                                {{ $ex->asignatura2_rel->abreviacion ?? '?' }}
                                                            </span>
                                                        @endif
                                                        @if($ex && $ex->asignatura3)
                                                            <br>
                                                            <span class="asig-badge asig-bg-{{ ($ex->asignatura3 % 20) + 1 }}">
                                                                {{ $ex->asignatura3_rel->abreviacion ?? '?' }}
                                                            </span>
                                                        @endif
                                                        @if(!$ex || (!$ex->asignatura1 && !$ex->asignatura2 && !$ex->asignatura3))
                                                            <i class="fas fa-plus icono-agregar"></i>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ count($gradosDelCiclo) + 1 }}" class="text-center text-muted py-3">
                                                    <i class="fas fa-inbox me-1"></i> Sin registros para este bloque.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach

        </div>{{-- /max-w-full --}}
    </div>{{-- /py-4 --}}


    {{-- ══════════════════════════════════════════════════════════════════════
         MODAL: CREAR / EDITAR / ELIMINAR por celda
    ══════════════════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="modalCelda" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg">
                <div class="modal-header modal-header-cst py-2">
                    <h6 class="modal-title" id="modalCeldaTitulo">
                        <i class="fas fa-calendar-alt me-1"></i> Examen
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label form-label-sm fw-bold text-muted">Fecha</label>
                            <div id="info_fecha" class="form-control form-control-sm bg-light"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label form-label-sm fw-bold text-muted">Etapa</label>
                            <div id="info_etapa" class="form-control form-control-sm bg-light"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label form-label-sm fw-bold text-muted">Tipo</label>
                            <div id="info_tipo" class="form-control form-control-sm bg-light"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label form-label-sm fw-bold text-muted">Grado / Curso</label>
                        <div id="info_grado" class="form-control form-control-sm bg-light"></div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label form-label-sm fw-bold">Asignatura 1</label>
                            <select id="cel_asig1" class="form-select form-select-sm">
                                <option value="">— Ninguna —</option>
                                @foreach($asignaturas as $a)
                                    <option value="{{ $a->id }}">{{ $a->asignatura }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label form-label-sm fw-bold">Asignatura 2</label>
                            <select id="cel_asig2" class="form-select form-select-sm">
                                <option value="">— Ninguna —</option>
                                @foreach($asignaturas as $a)
                                    <option value="{{ $a->id }}">{{ $a->asignatura }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label form-label-sm fw-bold">Asignatura 3</label>
                            <select id="cel_asig3" class="form-select form-select-sm">
                                <option value="">— Ninguna —</option>
                                @foreach($asignaturas as $a)
                                    <option value="{{ $a->id }}">{{ $a->asignatura }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-1 d-flex justify-content-between">
                    <button type="button" class="btn btn-danger btn-sm" id="btnEliminarCelda" style="display:none !important">
                        <i class="fas fa-trash me-1"></i> Eliminar
                    </button>
                    <div>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm" id="btnGuardarCelda">
                            <i class="fas fa-save me-1"></i> Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════════════════
         MODAL: AGREGAR NUEVO BLOQUE DE CALENDARIO
         Permite crear filas (fecha + grado_curso_id + etapa + tipo_prueba)
         para cualquier ciclo, incluyendo 1er y 2do Ciclo.
    ══════════════════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="modalAgregarBloque" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content shadow-lg">
                <div class="modal-header modal-header-add py-2">
                    <h6 class="modal-title">
                        <i class="fas fa-plus-circle me-1"></i> Nuevo Bloque de Calendario de Exámenes
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <div class="alert alert-info py-2 px-3 mb-3" style="font-size:0.8rem;">
                        <i class="fas fa-info-circle me-1"></i>
                        Seleccioná el <strong>Ciclo</strong>, la <strong>Etapa</strong>, el <strong>Tipo de Prueba</strong>
                        y las <strong>Fechas</strong> para las que querés crear el calendario de exámenes.
                        El sistema generará automáticamente las celdas para todos los grados del ciclo seleccionado.
                    </div>

                    <div class="row g-3 mb-4">
                        {{-- Ciclo --}}
                        <div class="col-md-3">
                            <label class="form-label form-label-sm fw-bold">
                                <i class="fas fa-layer-group me-1 text-primary"></i>Ciclo *
                            </label>
                            <select id="blq_ciclo" class="form-select form-select-sm">
                                <option value="">— Seleccionar —</option>
                                <option value="1er. Ciclo E.E.B.">1er. Ciclo E.E.B.</option>
                                <option value="2do. Ciclo E.E.B.">2do. Ciclo E.E.B.</option>
                                <option value="3er. Ciclo E.E.B.">3er. Ciclo E.E.B.</option>
                                <option value="Nivel Medio">Nivel Medio</option>
                            </select>
                        </div>
                        {{-- Etapa --}}
                        <div class="col-md-2">
                            <label class="form-label form-label-sm fw-bold">
                                <i class="fas fa-flag me-1 text-primary"></i>Etapa *
                            </label>
                            <select id="blq_etapa" class="form-select form-select-sm">
                                <option value="">— Seleccionar —</option>
                                <option value="1ra.">1ra.</option>
                                <option value="2da.">2da.</option>
                            </select>
                        </div>
                        {{-- Tipo --}}
                        <div class="col-md-2">
                            <label class="form-label form-label-sm fw-bold">
                                <i class="fas fa-tag me-1 text-primary"></i>Tipo de Prueba *
                            </label>
                            <select id="blq_tipo" class="form-select form-select-sm">
                                <option value="">— Seleccionar —</option>
                                <option value="Parcial">Parcial</option>
                                <option value="Cierre">Cierre</option>
                            </select>
                        </div>
                        {{-- Botón agregar fecha --}}
                        <div class="col-md-5 d-flex align-items-end">
                            <div class="w-100">
                                <label class="form-label form-label-sm fw-bold">
                                    <i class="fas fa-calendar-plus me-1 text-primary"></i>Agregar fecha al bloque
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="date" id="blq_fecha_nueva" class="form-control form-control-sm">
                                    <button class="btn btn-outline-primary btn-sm" id="btnAgregarFechaBloque">
                                        <i class="fas fa-plus me-1"></i>Agregar Fecha
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tabla preview de fechas y grados seleccionados --}}
                    <div id="previewBloqueContainer" style="display:none;">
                        <div class="titulo-ciclo mb-0" style="border-radius:6px 6px 0 0;" id="previewBloqueLabel">
                            Vista previa
                        </div>
                        <div class="card-grilla">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered grilla-calendario mb-0" id="previewBloqueTable">
                                    <thead id="previewBloqueHead"></thead>
                                    <tbody id="previewBloqueBody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Mensaje vacío --}}
                    <div id="previewBloqueVacio" class="text-center text-muted py-3" style="font-size:0.82rem;">
                        <i class="fas fa-hand-point-up me-1"></i>
                        Seleccioná el ciclo, etapa, tipo y agregá al menos una fecha para ver la vista previa.
                    </div>

                </div>
                <div class="modal-footer py-2 d-flex justify-content-between align-items-center">
                    <small class="text-muted" id="blq_resumen_fechas">0 fechas seleccionadas</small>
                    <div>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success btn-sm fw-bold" id="btnCrearBloque">
                            <i class="fas fa-save me-1"></i> Crear Bloque
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════════════════
         MODAL: EDITAR BLOQUE DE CALENDARIO
         Permite agregar o quitar fechas de un bloque existente
    ══════════════════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="modalEditarBloque" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content shadow-lg">
                <div class="modal-header modal-header-add py-2">
                    <h6 class="modal-title">
                        <i class="fas fa-calendar-edit me-1"></i> Editar Bloque de Calendario
                        <small class="ms-2 opacity-75" id="editBlq_subtitulo"></small>
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <div class="alert alert-warning py-2 px-3 mb-3" style="font-size:0.8rem;">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Podés <strong>agregar nuevas fechas</strong> al bloque o <strong>quitar fechas existentes</strong>.
                        Al quitar una fecha se eliminarán <strong>todas las celdas de esa fecha</strong> (incluyendo las asignaturas cargadas).
                    </div>

                    {{-- Fechas actuales del bloque --}}
                    <div class="mb-3">
                        <label class="form-label form-label-sm fw-bold text-muted">
                            <i class="fas fa-calendar-check me-1 text-success"></i>Fechas actuales del bloque
                        </label>
                        <div id="editBlq_fechasActuales" class="d-flex flex-wrap gap-2 p-2"
                            style="min-height:2.5rem; background:#f8fff8; border:1px solid #b8dfc8; border-radius:6px;">
                        </div>
                    </div>

                    {{-- Agregar nueva fecha --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-5 d-flex align-items-end">
                            <div class="w-100">
                                <label class="form-label form-label-sm fw-bold">
                                    <i class="fas fa-calendar-plus me-1 text-primary"></i>Agregar nueva fecha al bloque
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="date" id="editBlq_fecha_nueva" class="form-control form-control-sm">
                                    <button class="btn btn-outline-primary btn-sm" id="btnEditBlqAgregarFecha">
                                        <i class="fas fa-plus me-1"></i>Agregar Fecha
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Fechas a agregar (nuevas) --}}
                    <div id="editBlq_nuevasFechasContainer" style="display:none;">
                        <label class="form-label form-label-sm fw-bold text-muted">
                            <i class="fas fa-calendar-plus me-1 text-primary"></i>Fechas nuevas a agregar
                        </label>
                        <div id="editBlq_nuevasFechas" class="d-flex flex-wrap gap-2 p-2 mb-3"
                            style="min-height:2.5rem; background:#f0f7ff; border:1px solid #b0c8e8; border-radius:6px;">
                        </div>
                    </div>

                    {{-- Fechas a quitar --}}
                    <div id="editBlq_quitarContainer" style="display:none;">
                        <label class="form-label form-label-sm fw-bold text-muted">
                            <i class="fas fa-calendar-times me-1 text-danger"></i>Fechas marcadas para eliminar
                        </label>
                        <div id="editBlq_fechasAQuitar" class="d-flex flex-wrap gap-2 p-2 mb-3"
                            style="min-height:2.5rem; background:#fff5f5; border:1px solid #f5c6cb; border-radius:6px;">
                        </div>
                    </div>

                </div>
                <div class="modal-footer py-2 d-flex justify-content-between align-items-center">
                    <small class="text-muted" id="editBlq_resumen"></small>
                    <div>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm fw-bold" id="btnGuardarEditBloque">
                            <i class="fas fa-save me-1"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════════════════
         MODAL: CONFIRMAR ELIMINACIÓN DE BLOQUE COMPLETO
    ══════════════════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="modalEliminarBloque" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content shadow-lg">
                <div class="modal-header py-2" style="background:linear-gradient(135deg,#7a1c24,#b02a37); color:#fff;">
                    <h6 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-1"></i> Eliminar Bloque de Calendario
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1)"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2" style="font-size:0.9rem;">Estás por eliminar el bloque:</p>
                    <div class="alert alert-danger py-2 px-3 mb-3" style="font-size:0.85rem;">
                        <strong id="elimBlq_detalle"></strong>
                    </div>
                    <p class="mb-1" style="font-size:0.85rem;">
                        Esto eliminará <strong>todas las filas del bloque</strong> (todas las fechas y todas las asignaturas cargadas).
                    </p>
                    <p class="text-danger fw-bold mb-0" style="font-size:0.82rem;">
                        <i class="fas fa-exclamation-circle me-1"></i>Esta acción no se puede deshacer.
                    </p>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger btn-sm fw-bold" id="btnConfirmarEliminarBloque">
                        <i class="fas fa-trash-alt me-1"></i> Sí, Eliminar Bloque
                    </button>
                </div>
            </div>
        </div>
    </div>


    {{-- Toast --}}
    <div class="toast-cal toast align-items-center text-white border-0" id="toastCal" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toastCalMsg">✔ Guardado correctamente.</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>


    @push('scripts')
    {{-- Librerías de exportación --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const CSRF    = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const URL_BASE = '{{ url("academica/calendario-examenes") }}';

        // ── Mapa de ciclos → IDs de grados (debe coincidir con el PHP de arriba) ──
        const CICLOS_GRADOS = @json($ciclosConfigJs);

        // ─────────────────────────────────────────────────────────────────────
        // 1. Tablas HTML puras — sin DataTables para preservar data-* y el DOM
        // ─────────────────────────────────────────────────────────────────────
        // (La exportación se maneja directamente con SheetJS y pdfMake)

        // ─────────────────────────────────────────────────────────────────────
        // 2. BOTONES DE EXPORTACIÓN (actúan sobre las secciones visibles)
        // ─────────────────────────────────────────────────────────────────────

        /** Recoge todas las tablas de secciones visibles */
        function getTablesVisibles() {
            return document.querySelectorAll('.tabla-grilla-seccion:not([style*="display: none"]) .grilla-calendario');
        }

        /** Construye un workbook Excel con SheetJS a partir de las tablas visibles */
        document.getElementById('btnExportExcel').addEventListener('click', function () {
            const tablas = getTablesVisibles();
            if (!tablas.length) { toast('No hay secciones visibles para exportar.', false); return; }

            // Usamos el botón Excel de la primera DataTable visible (incluye todas las columnas)
            // Estrategia: crear una tabla temporal aplanada con todos los bloques
            let wb = XLSX.utils.book_new();

            tablas.forEach(function (table) {
                const seccion = table.closest('.tabla-grilla-seccion');
                const nombreHoja = (seccion.dataset.ciclo + ' ' + seccion.dataset.etapa + ' ' + seccion.dataset.tipo)
                    .substring(0, 31); // Excel max 31 chars
                const ws = XLSX.utils.table_to_sheet(table, { raw: true });
                XLSX.utils.book_append_sheet(wb, ws, nombreHoja);
            });

            XLSX.writeFile(wb, 'Calendario_Examenes.xlsx');
        });

        /** Exportar a PDF con pdfmake */
        document.getElementById('btnExportPDF').addEventListener('click', function () {
            const tablas = getTablesVisibles();
            if (!tablas.length) { toast('No hay secciones visibles para exportar.', false); return; }

            const contenido = [];
            contenido.push({
                text: 'Calendario de Pruebas Escritas – Colegio Privado Santa Teresita',
                style: 'titulo',
                margin: [0, 0, 0, 10]
            });

            tablas.forEach(function (table) {
                const seccion = table.closest('.tabla-grilla-seccion');
                contenido.push({
                    text: seccion.dataset.ciclo + ' | Etapa: ' + seccion.dataset.etapa + ' | ' + seccion.dataset.tipo,
                    style: 'subtitulo',
                    margin: [0, 8, 0, 4]
                });

                // Leer filas de la tabla
                const rows = [];
                table.querySelectorAll('thead tr').forEach(tr => {
                    const fila = [];
                    tr.querySelectorAll('th').forEach(th => fila.push({ text: th.innerText.trim(), style: 'thdr' }));
                    rows.push(fila);
                });
                table.querySelectorAll('tbody tr').forEach(tr => {
                    const fila = [];
                    tr.querySelectorAll('td').forEach(td => fila.push({ text: td.innerText.trim(), style: 'tcell' }));
                    rows.push(fila);
                });

                if (rows.length > 1) {
                    const colWidths = rows[0].map((_, i) => i === 0 ? 55 : '*');
                    contenido.push({ table: { headerRows: 1, widths: colWidths, body: rows }, margin: [0, 0, 0, 12] });
                }
            });

            const docDef = {
                pageOrientation: 'landscape',
                pageSize: 'A4',
                content: contenido,
                styles: {
                    titulo:   { fontSize: 13, bold: true, color: '#1e3a5f' },
                    subtitulo:{ fontSize: 9,  bold: true, color: '#0d2137', fillColor: '#e8f1fb' },
                    thdr:     { fontSize: 7,  bold: true, color: '#fff', fillColor: '#1e3a5f', alignment: 'center' },
                    tcell:    { fontSize: 7,  color: '#333', alignment: 'center' },
                },
                defaultStyle: { font: 'Roboto' }
            };

            pdfMake.createPdf(docDef).download('Calendario_Examenes.pdf');
        });

        /** Imprimir */
        document.getElementById('btnPrint').addEventListener('click', function () {
            window.print();
        });

        // ─────────────────────────────────────────────────────────────────────
        // 3. FILTROS DE CICLO / ETAPA / TIPO
        // ─────────────────────────────────────────────────────────────────────
        function applyFilters() {
            const ciclo = document.getElementById('filterCiclo').value;
            const etapa = document.getElementById('filterEtapa').value;
            const tipo  = document.getElementById('filterTipo').value;

            document.querySelectorAll('.tabla-grilla-seccion').forEach(function (sec) {
                const show = (!ciclo || sec.dataset.ciclo === ciclo)
                          && (!etapa || sec.dataset.etapa === etapa)
                          && (!tipo  || sec.dataset.tipo  === tipo);
                sec.style.display = show ? '' : 'none';
            });
        }

        ['filterCiclo', 'filterEtapa', 'filterTipo'].forEach(id =>
            document.getElementById(id).addEventListener('change', applyFilters)
        );
        applyFilters();

        // ─────────────────────────────────────────────────────────────────────
        // 4. CLICK EN CELDA → MODAL EDITAR
        // ─────────────────────────────────────────────────────────────────────
        let celdaActual = null;

        document.addEventListener('click', function (e) {
            const celda = e.target.closest('.celda-examen');
            if (!celda) return;

            celdaActual = celda;
            const d = celda.dataset;

            document.getElementById('info_fecha').textContent =
                new Date(d.fecha + 'T00:00:00').toLocaleDateString('es-PY', { day:'2-digit', month:'2-digit', year:'numeric' });
            document.getElementById('info_etapa').textContent = d.etapa;
            document.getElementById('info_tipo').textContent  = d.tipo;
            document.getElementById('info_grado').textContent = d.gradoLabel;

            document.getElementById('cel_asig1').value = d.asig1 || '';
            document.getElementById('cel_asig2').value = d.asig2 || '';
            document.getElementById('cel_asig3').value = d.asig3 || '';

            const tieneRegistro = !!d.id;
            document.getElementById('modalCeldaTitulo').innerHTML = tieneRegistro
                ? '<i class="fas fa-edit me-1"></i> Editar Examen'
                : '<i class="fas fa-plus-circle me-1"></i> Agregar Examen';

            const btnElim = document.getElementById('btnEliminarCelda');
            btnElim.style.display = tieneRegistro ? 'inline-block' : 'none';

            new bootstrap.Modal(document.getElementById('modalCelda')).show();
        });

        // ─────────────────────────────────────────────────────────────────────
        // 5. GUARDAR (crear o actualizar celda)
        // ─────────────────────────────────────────────────────────────────────
        function colorClase(id) { return id ? 'asig-bg-' + ((parseInt(id) % 20) + 1) : ''; }

        function renderCelda(celda, examen) {
            const asignaturas = @json($asignaturas->keyBy('id'));

            celda.dataset.id   = examen ? examen.id : '';
            celda.dataset.asig1 = examen ? (examen.asignatura1 || '') : '';
            celda.dataset.asig2 = examen ? (examen.asignatura2 || '') : '';
            celda.dataset.asig3 = examen ? (examen.asignatura3 || '') : '';

            let html = '';
            [examen?.asignatura1, examen?.asignatura2, examen?.asignatura3]
                .filter(Boolean)
                .forEach((id, i) => {
                    const a = asignaturas[id];
                    if (a) html += (i > 0 ? '<br>' : '') +
                        `<span class="asig-badge ${colorClase(id)}">${a.abreviacion}</span>`;
                });

            if (!html) {
                html = '<i class="fas fa-plus icono-agregar"></i>';
                celda.classList.add('celda-vacia');
            } else {
                celda.classList.remove('celda-vacia');
            }
            celda.innerHTML = html;
        }

        document.getElementById('btnGuardarCelda').addEventListener('click', function () {
            if (!celdaActual) return;
            const d   = celdaActual.dataset;
            const id  = d.id;
            const url = id ? `${URL_BASE}/${id}` : URL_BASE;

            fetch(url, {
                method: id ? 'PUT' : 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: JSON.stringify({
                    fecha:         d.fecha,
                    etapa:         d.etapa,
                    tipo_prueba:   d.tipo,
                    grado_curso_id: d.grado,
                    asignatura1:   document.getElementById('cel_asig1').value || null,
                    asignatura2:   document.getElementById('cel_asig2').value || null,
                    asignatura3:   document.getElementById('cel_asig3').value || null,
                }),
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('modalCelda')).hide();
                    renderCelda(celdaActual, data.examen);
                    toast('✔ ' + data.message);
                } else {
                    toast('✘ Error al guardar.', false);
                }
            })
            .catch(() => toast('✘ Error de conexión.', false));
        });

        // ─────────────────────────────────────────────────────────────────────
        // 6. ELIMINAR celda
        // ─────────────────────────────────────────────────────────────────────
        document.getElementById('btnEliminarCelda').addEventListener('click', function () {
            if (!celdaActual || !celdaActual.dataset.id) return;
            if (!confirm('¿Eliminar el registro de este examen?')) return;

            fetch(`${URL_BASE}/${celdaActual.dataset.id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('modalCelda')).hide();
                    renderCelda(celdaActual, null);
                    toast('✔ ' + data.message);
                } else {
                    toast('✘ Error al eliminar.', false);
                }
            })
            .catch(() => toast('✘ Error de conexión.', false));
        });

        // ─────────────────────────────────────────────────────────────────────
        // 7. MODAL AGREGAR NUEVO BLOQUE DE CALENDARIO
        // ─────────────────────────────────────────────────────────────────────
        let fechasBloque = []; // Array de strings 'YYYY-MM-DD'

        function renderPreviewBloque() {
            const ciclo = document.getElementById('blq_ciclo').value;
            const etapa = document.getElementById('blq_etapa').value;
            const tipo  = document.getElementById('blq_tipo').value;
            const container = document.getElementById('previewBloqueContainer');
            const vacio     = document.getElementById('previewBloqueVacio');
            const resumen   = document.getElementById('blq_resumen_fechas');

            resumen.textContent = fechasBloque.length + ' fecha(s) seleccionadas';

            if (!ciclo || !etapa || !tipo || fechasBloque.length === 0) {
                container.style.display = 'none';
                vacio.style.display     = '';
                return;
            }

            const grados = CICLOS_GRADOS[ciclo] || {};
            const gradoIds    = Object.keys(grados);
            const gradoLabels = Object.values(grados);

            // Encabezado
            document.getElementById('previewBloqueLabel').textContent =
                ciclo + '  |  Etapa: ' + etapa + '  |  ' + tipo;

            const thead = document.getElementById('previewBloqueHead');
            thead.innerHTML = '';
            let trHead = '<tr><th style="width:90px">Fecha</th>';
            gradoLabels.forEach(l => { trHead += `<th class="text-center" style="font-size:0.68rem">${l}</th>`; });
            trHead += '</tr>';
            thead.innerHTML = trHead;

            const tbody = document.getElementById('previewBloqueBody');
            tbody.innerHTML = '';
            const fechasSorted = [...fechasBloque].sort();
            fechasSorted.forEach(function (fecha) {
                let tr = `<tr>
                    <td class="text-center fw-semibold" style="white-space:nowrap;">
                        ${new Date(fecha + 'T00:00:00').toLocaleDateString('es-PY', {day:'2-digit',month:'2-digit',year:'numeric'})}
                        <button class="btn btn-link btn-sm p-0 ms-1 text-danger" data-rm-fecha="${fecha}" title="Quitar fecha">
                            <i class="fas fa-times-circle"></i>
                        </button>
                    </td>`;
                gradoIds.forEach(() => {
                    tr += `<td class="celda-examen celda-vacia text-center">
                               <i class="fas fa-plus icono-agregar"></i>
                           </td>`;
                });
                tr += '</tr>';
                tbody.innerHTML += tr;
            });

            // Botones quitar fecha
            tbody.querySelectorAll('[data-rm-fecha]').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const f = btn.dataset.rmFecha;
                    fechasBloque = fechasBloque.filter(x => x !== f);
                    renderPreviewBloque();
                });
            });

            container.style.display = '';
            vacio.style.display     = 'none';
        }

        // Actualizar preview al cambiar selects
        ['blq_ciclo', 'blq_etapa', 'blq_tipo'].forEach(id => {
            document.getElementById(id).addEventListener('change', renderPreviewBloque);
        });

        // Agregar fecha al bloque
        document.getElementById('btnAgregarFechaBloque').addEventListener('click', function () {
            const val = document.getElementById('blq_fecha_nueva').value;
            if (!val) { toast('Seleccioná una fecha.', false); return; }
            if (fechasBloque.includes(val)) { toast('Esa fecha ya fue agregada.', false); return; }
            fechasBloque.push(val);
            document.getElementById('blq_fecha_nueva').value = '';
            renderPreviewBloque();
        });

        // Resetear modal al cerrar
        document.getElementById('modalAgregarBloque').addEventListener('hidden.bs.modal', function () {
            fechasBloque = [];
            document.getElementById('blq_ciclo').value  = '';
            document.getElementById('blq_etapa').value  = '';
            document.getElementById('blq_tipo').value   = '';
            document.getElementById('blq_fecha_nueva').value = '';
            renderPreviewBloque();
        });

        // Crear bloque: hace un POST por cada combinación fecha × grado_curso_id
        document.getElementById('btnCrearBloque').addEventListener('click', async function () {
            const ciclo = document.getElementById('blq_ciclo').value;
            const etapa = document.getElementById('blq_etapa').value;
            const tipo  = document.getElementById('blq_tipo').value;

            if (!ciclo || !etapa || !tipo) { toast('Completá Ciclo, Etapa y Tipo.', false); return; }
            if (fechasBloque.length === 0) { toast('Agregá al menos una fecha.', false); return; }

            const grados   = CICLOS_GRADOS[ciclo] || {};
            const gradoIds = Object.keys(grados);
            if (!gradoIds.length) { toast('No hay grados configurados para ese ciclo.', false); return; }

            const btn = document.getElementById('btnCrearBloque');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Creando...';

            let errores = 0;
            const promises = [];

            fechasBloque.forEach(function (fecha) {
                gradoIds.forEach(function (gradoId) {
                    promises.push(
                        fetch(URL_BASE, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                            body: JSON.stringify({
                                fecha:          fecha,
                                etapa:          etapa,
                                tipo_prueba:    tipo,
                                grado_curso_id: parseInt(gradoId),
                                asignatura1:    null,
                                asignatura2:    null,
                                asignatura3:    null,
                            }),
                        })
                        .then(r => r.json())
                        .then(d => { if (!d.success) errores++; })
                        .catch(() => errores++)
                    );
                });
            });

            await Promise.all(promises);

            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save me-1"></i> Crear Bloque';

            if (errores === 0) {
                bootstrap.Modal.getInstance(document.getElementById('modalAgregarBloque')).hide();
                toast('✔ Bloque creado correctamente. Recargando...');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                toast(`✘ Se produjeron ${errores} error(es) al crear el bloque.`, false);
            }
        });

        // ─────────────────────────────────────────────────────────────────────
        // 8. EDITAR BLOQUE — abrir modal y gestionar fechas
        // ─────────────────────────────────────────────────────────────────────
        let editBlqData = {};       // { ciclo, etapa, tipo, fechasOriginales[], fechasNuevas[], fechasAQuitar[] }

        function formatFechaEs(ymd) {
            return new Date(ymd + 'T00:00:00').toLocaleDateString('es-PY', { day: '2-digit', month: '2-digit', year: 'numeric' });
        }

        function renderEditBloque() {
            const contActuales  = document.getElementById('editBlq_fechasActuales');
            const contNuevas    = document.getElementById('editBlq_nuevasFechas');
            const contAQuitar   = document.getElementById('editBlq_fechasAQuitar');
            const wrapNuevas    = document.getElementById('editBlq_nuevasFechasContainer');
            const wrapAQuitar   = document.getElementById('editBlq_quitarContainer');
            const resumen       = document.getElementById('editBlq_resumen');

            // Fechas actuales (verdes si no están marcadas para quitar, rojas si sí)
            contActuales.innerHTML = '';
            editBlqData.fechasOriginales.forEach(function (f) {
                const marcada = editBlqData.fechasAQuitar.includes(f);
                const badge = document.createElement('span');
                badge.style.cssText = `display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:0.78rem; font-weight:600; cursor:pointer;
                    background:${marcada ? '#f8d7da' : '#d1f0db'}; color:${marcada ? '#842029' : '#0a4f30'};
                    border:1px solid ${marcada ? '#f5c6cb' : '#a3d9b4'}; text-decoration:${marcada ? 'line-through' : 'none'};`;
                badge.innerHTML = `<i class="fas fa-${marcada ? 'undo-alt' : 'times'}" style="font-size:0.65rem;"></i> ${formatFechaEs(f)}`;
                badge.title = marcada ? 'Cancelar eliminación de esta fecha' : 'Marcar para eliminar';
                badge.addEventListener('click', function () {
                    if (marcada) {
                        editBlqData.fechasAQuitar = editBlqData.fechasAQuitar.filter(x => x !== f);
                    } else {
                        editBlqData.fechasAQuitar.push(f);
                    }
                    renderEditBloque();
                });
                contActuales.appendChild(badge);
            });
            if (!editBlqData.fechasOriginales.length) {
                contActuales.innerHTML = '<small class="text-muted">Sin fechas en este bloque.</small>';
            }

            // Fechas nuevas a agregar
            contNuevas.innerHTML = '';
            editBlqData.fechasNuevas.forEach(function (f) {
                const badge = document.createElement('span');
                badge.style.cssText = `display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:0.78rem; font-weight:600;
                    background:#dcefff; color:#084298; border:1px solid #b0c8e8; cursor:pointer;`;
                badge.innerHTML = `<i class="fas fa-times" style="font-size:0.65rem;"></i> ${formatFechaEs(f)}`;
                badge.title = 'Quitar esta fecha nueva';
                badge.addEventListener('click', function () {
                    editBlqData.fechasNuevas = editBlqData.fechasNuevas.filter(x => x !== f);
                    renderEditBloque();
                });
                contNuevas.appendChild(badge);
            });
            wrapNuevas.style.display = editBlqData.fechasNuevas.length ? '' : 'none';

            // Fechas a eliminar (resumen)
            contAQuitar.innerHTML = '';
            editBlqData.fechasAQuitar.forEach(function (f) {
                const span = document.createElement('span');
                span.style.cssText = `padding:3px 10px; border-radius:20px; font-size:0.78rem; font-weight:600;
                    background:#f8d7da; color:#842029; border:1px solid #f5c6cb;`;
                span.textContent = formatFechaEs(f);
                contAQuitar.appendChild(span);
            });
            wrapAQuitar.style.display = editBlqData.fechasAQuitar.length ? '' : 'none';

            // Resumen footer
            const partes = [];
            if (editBlqData.fechasNuevas.length)   partes.push(`+${editBlqData.fechasNuevas.length} a agregar`);
            if (editBlqData.fechasAQuitar.length)  partes.push(`−${editBlqData.fechasAQuitar.length} a eliminar`);
            resumen.textContent = partes.length ? partes.join('  ·  ') : 'Sin cambios pendientes';
        }

        // Abrir modal Editar Bloque
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-bloque-editar');
            if (!btn) return;
            e.stopPropagation();

            const fechasStr = btn.dataset.fechas || '';
            editBlqData = {
                ciclo:             btn.dataset.ciclo,
                etapa:             btn.dataset.etapa,
                tipo:              btn.dataset.tipo,
                fechasOriginales:  fechasStr ? fechasStr.split(',') : [],
                fechasNuevas:      [],
                fechasAQuitar:     [],
            };

            document.getElementById('editBlq_subtitulo').textContent =
                editBlqData.ciclo + ' | Etapa: ' + editBlqData.etapa + ' | ' + editBlqData.tipo;
            document.getElementById('editBlq_fecha_nueva').value = '';

            renderEditBloque();
            new bootstrap.Modal(document.getElementById('modalEditarBloque')).show();
        });

        // Reset al cerrar
        document.getElementById('modalEditarBloque').addEventListener('hidden.bs.modal', function () {
            editBlqData = {};
        });

        // Agregar fecha nueva
        document.getElementById('btnEditBlqAgregarFecha').addEventListener('click', function () {
            const val = document.getElementById('editBlq_fecha_nueva').value;
            if (!val) { toast('Seleccioná una fecha.', false); return; }
            if (editBlqData.fechasOriginales.includes(val)) { toast('Esa fecha ya existe en el bloque.', false); return; }
            if (editBlqData.fechasNuevas.includes(val))     { toast('Esa fecha ya fue agregada.', false); return; }
            editBlqData.fechasNuevas.push(val);
            document.getElementById('editBlq_fecha_nueva').value = '';
            renderEditBloque();
        });

        // Guardar cambios del bloque
        document.getElementById('btnGuardarEditBloque').addEventListener('click', async function () {
            const sinCambios = !editBlqData.fechasNuevas.length && !editBlqData.fechasAQuitar.length;
            if (sinCambios) { toast('No hay cambios para guardar.', false); return; }

            const btn = document.getElementById('btnGuardarEditBloque');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Guardando...';

            const grados   = CICLOS_GRADOS[editBlqData.ciclo] || {};
            const gradoIds = Object.keys(grados);
            let errores = 0;
            const promises = [];

            // 1. AGREGAR fechas nuevas (POST para cada fecha × grado)
            editBlqData.fechasNuevas.forEach(function (fecha) {
                gradoIds.forEach(function (gradoId) {
                    promises.push(
                        fetch(URL_BASE, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                            body: JSON.stringify({
                                fecha:          fecha,
                                etapa:          editBlqData.etapa,
                                tipo_prueba:    editBlqData.tipo,
                                grado_curso_id: parseInt(gradoId),
                                asignatura1:    null,
                                asignatura2:    null,
                                asignatura3:    null,
                            }),
                        })
                        .then(r => r.json())
                        .then(d => { if (!d.success) errores++; })
                        .catch(() => errores++)
                    );
                });
            });

            // 2. QUITAR fechas marcadas: buscar IDs en el DOM y hacer DELETE
            editBlqData.fechasAQuitar.forEach(function (fecha) {
                // Buscar todas las celdas de esa fecha/etapa/tipo en el DOM
                document.querySelectorAll(
                    `.celda-examen[data-fecha="${fecha}"][data-etapa="${editBlqData.etapa}"][data-tipo="${editBlqData.tipo}"]`
                ).forEach(function (celda) {
                    const id = celda.dataset.id;
                    if (!id) return; // celda vacía sin registro
                    promises.push(
                        fetch(`${URL_BASE}/${id}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                        })
                        .then(r => r.json())
                        .then(d => { if (!d.success) errores++; })
                        .catch(() => errores++)
                    );
                });
            });

            await Promise.all(promises);

            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save me-1"></i> Guardar Cambios';

            if (errores === 0) {
                bootstrap.Modal.getInstance(document.getElementById('modalEditarBloque')).hide();
                toast('✔ Bloque actualizado correctamente. Recargando...');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                toast(`✘ Se produjeron ${errores} error(es). Recargando de todas formas...`, false);
                setTimeout(() => window.location.reload(), 2000);
            }
        });


        // ─────────────────────────────────────────────────────────────────────
        // 9. ELIMINAR BLOQUE COMPLETO
        // ─────────────────────────────────────────────────────────────────────
        let elimBlqData = {};

        // Abrir modal Eliminar Bloque
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-bloque-eliminar');
            if (!btn) return;
            e.stopPropagation();

            elimBlqData = {
                ciclo:   btn.dataset.ciclo,
                etapa:   btn.dataset.etapa,
                tipo:    btn.dataset.tipo,
                fechas:  btn.dataset.fechas ? btn.dataset.fechas.split(',') : [],
            };

            document.getElementById('elimBlq_detalle').textContent =
                elimBlqData.ciclo + '  |  Etapa: ' + elimBlqData.etapa + '  |  ' + elimBlqData.tipo +
                '  (' + elimBlqData.fechas.length + ' fecha(s))';

            new bootstrap.Modal(document.getElementById('modalEliminarBloque')).show();
        });

        // Confirmar eliminación total del bloque
        document.getElementById('btnConfirmarEliminarBloque').addEventListener('click', async function () {
            const btn = document.getElementById('btnConfirmarEliminarBloque');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Eliminando...';

            let errores = 0;
            const promises = [];

            // Buscar TODAS las celdas del bloque en el DOM (por etapa + tipo, independientemente de la fecha)
            document.querySelectorAll(
                `.celda-examen[data-etapa="${elimBlqData.etapa}"][data-tipo="${elimBlqData.tipo}"]`
            ).forEach(function (celda) {
                // Filtrar solo las fechas de este bloque
                if (!elimBlqData.fechas.includes(celda.dataset.fecha)) return;
                const id = celda.dataset.id;
                if (!id) return;
                promises.push(
                    fetch(`${URL_BASE}/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    })
                    .then(r => r.json())
                    .then(d => { if (!d.success) errores++; })
                    .catch(() => errores++)
                );
            });

            await Promise.all(promises);

            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-trash-alt me-1"></i> Sí, Eliminar Bloque';

            bootstrap.Modal.getInstance(document.getElementById('modalEliminarBloque')).hide();

            if (errores === 0) {
                toast('✔ Bloque eliminado correctamente. Recargando...');
            } else {
                toast(`✘ ${errores} error(es) al eliminar. Recargando...`, false);
            }
            setTimeout(() => window.location.reload(), 1500);
        });


        // ─────────────────────────────────────────────────────────────────────
        // 10. HELPER TOAST
        // ─────────────────────────────────────────────────────────────────────
        function toast(msg, ok = true) {
            const el  = document.getElementById('toastCal');
            const txt = document.getElementById('toastCalMsg');
            el.classList.remove('bg-success', 'bg-danger');
            el.classList.add(ok ? 'bg-success' : 'bg-danger');
            txt.textContent = msg;
            new bootstrap.Toast(el, { delay: 3000 }).show();
        }

    }); // DOMContentLoaded
    </script>
    @endpush

</x-app-layout>