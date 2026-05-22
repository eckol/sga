<x-app-layout>
    <style>
        .grilla-horario th,
        .grilla-horario td {
            font-size: 0.7rem !important;
            padding: 0.15rem 0.25rem !important;
            vertical-align: middle;
            white-space: nowrap;
        }

        .grilla-horario thead th {
            background-color: #1e3a5f;
            color: #fff;
            text-align: center;
            font-weight: 600;
        }

        .grilla-horario thead th.th-dia {
            background-color: #0d2137;
        }

        /* ── Colores por día ── */
        .grilla-horario td.td-dia {
            font-weight: 700;
            text-align: center;
        }

        /* Lunes — azul acero */
        .dia-1 td.td-dia { background-color: #cfe2ff; color: #084298; }
        .dia-1 td.td-hora { background-color: #e8f1ff; color: #084298; }
        .dia-1 td { border-left-color: #84b3f5 !important; }

        /* Martes — verde salvia */
        .dia-2 td.td-dia { background-color: #d1e7dd; color: #0a4f30; }
        .dia-2 td.td-hora { background-color: #eaf4ef; color: #0a4f30; }
        .dia-2 td { border-left-color: #86c9a8 !important; }

        /* Miércoles — ámbar dorado */
        .dia-3 td.td-dia { background-color: #fff3cd; color: #6d4c00; }
        .dia-3 td.td-hora { background-color: #fffaed; color: #6d4c00; }
        .dia-3 td { border-left-color: #f5c842 !important; }

        /* Jueves — violeta suave */
        .dia-4 td.td-dia { background-color: #e2d9f3; color: #3b1a78; }
        .dia-4 td.td-hora { background-color: #f3effe; color: #3b1a78; }
        .dia-4 td { border-left-color: #b498e8 !important; }

        /* Viernes — rosa salmón */
        .dia-5 td.td-dia { background-color: #f8d7da; color: #7a1c24; }
        .dia-5 td.td-hora { background-color: #fdf0f1; color: #7a1c24; }
        .dia-5 td { border-left-color: #f0959e !important; }

        .select-asignatura {
            font-size: 0.68rem !important;
            padding: 0.1rem 0.2rem !important;
            border-radius: 5px !important;
            width: 100%;
            min-width: 90px;
            border: 1px solid #b0c8e8;
            background-color: #f8fbff;
            cursor: pointer;
            transition: border-color 0.2s, background-color 0.2s;
        }

        .select-asignatura:focus {
            border-color: #1e3a5f;
            outline: none;
            background-color: #fff;
        }

        .select-asignatura.guardado {
            background-color: #d4edda !important;
            border-color: #28a745 !important;
        }

        .select-asignatura.error {
            background-color: #f8d7da !important;
            border-color: #dc3545 !important;
        }

        .badge-guardando {
            font-size: 0.6rem;
            display: none;
        }

        .titulo-grilla {
            background-color: #1e3a5f;
            color: #fff;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0.4rem 0.75rem;
            border-radius: 6px 6px 0 0;
            letter-spacing: 0.05em;
        }

        .card-grilla {
            border: 1px solid #b0c8e8;
            border-radius: 0 0 6px 6px;
            overflow-x: auto;
        }

        .toast-horario {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            min-width: 220px;
            font-size: 0.78rem;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Horarios de Clase</h2>
    </x-slot>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- GRILLA 1: 7MO. GRADO A → 9NO. GRADO B                        --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="mt-3 mb-4">
        <div class="titulo-grilla">📚 3ER. CICLO E.E.B.</div>
        <div class="card-grilla">
            <table class="table table-sm table-bordered table-hover mb-0 grilla-horario">
                <thead>
                    <tr>
                        <th class="th-dia" style="width:60px">Día</th>
                        <th style="width:50px">Módulo</th>
                        <th style="width:55px">Inicio</th>
                        <th style="width:55px">Fin</th>
                        @foreach($grados as $gc)
                            <th>{{ $gc->gradocurso }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($dias as $numDia => $nombreDia)
                        @foreach($horas as $i => $hora)
                            <tr class="dia-{{ $numDia }}">
                                @if($i === 0)
                                    <td class="td-dia" rowspan="{{ count($horas) }}">{{ $nombreDia }}</td>
                                @endif
                                <td class="td-hora text-center">{{ $hora->modulo }}</td>
                                <td class="td-hora text-center">{{ substr($hora->hora_inicio, 0, 5) }}</td>
                                <td class="td-hora text-center">{{ substr($hora->hora_fin, 0, 5) }}</td>
                                @foreach($grados as $gc)
                                    @php
                                        $key     = "{$numDia}-{$hora->id}-{$gc->id}";
                                        $horario = $horarios[$key] ?? null;
                                    @endphp
                                    <td>
                                        @if($horario)
                                            <select
                                                class="select-asignatura"
                                                data-id="{{ $horario->id }}"
                                                data-url="{{ route('horarios.update', $horario->id) }}">
                                                <option value="">— Sin asignar —</option>
                                                @foreach($asignaturas as $asig)
                                                    <option value="{{ $asig->id }}"
                                                        {{ $horario->asignatura_id == $asig->id ? 'selected' : '' }}>
                                                        {{ $asig->abreviacion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <span class="text-muted" style="font-size:0.65rem">—</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- GRILLA 2: 1ER. CURSO A → 3ER. CURSO B                        --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="mb-4">
        <div class="titulo-grilla">🎓 NIVEL MEDIO</div>
        <div class="card-grilla">
            <table class="table table-sm table-bordered table-hover mb-0 grilla-horario">
                <thead>
                    <tr>
                        <th class="th-dia" style="width:60px">Día</th>
                        <th style="width:50px">Módulo</th>
                        <th style="width:55px">Inicio</th>
                        <th style="width:55px">Fin</th>
                        @foreach($cursos as $gc)
                            <th>{{ $gc->gradocurso }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($dias as $numDia => $nombreDia)
                        @foreach($horas as $i => $hora)
                            <tr class="dia-{{ $numDia }}">
                                @if($i === 0)
                                    <td class="td-dia" rowspan="{{ count($horas) }}">{{ $nombreDia }}</td>
                                @endif
                                <td class="td-hora text-center">{{ $hora->modulo }}</td>
                                <td class="td-hora text-center">{{ substr($hora->hora_inicio, 0, 5) }}</td>
                                <td class="td-hora text-center">{{ substr($hora->hora_fin, 0, 5) }}</td>
                                @foreach($cursos as $gc)
                                    @php
                                        $key     = "{$numDia}-{$hora->id}-{$gc->id}";
                                        $horario = $horarios[$key] ?? null;
                                    @endphp
                                    <td>
                                        @if($horario)
                                            <select
                                                class="select-asignatura"
                                                data-id="{{ $horario->id }}"
                                                data-url="{{ route('horarios.update', $horario->id) }}">
                                                <option value="">— Sin asignar —</option>
                                                @foreach($asignaturas as $asig)
                                                    <option value="{{ $asig->id }}"
                                                        {{ $horario->asignatura_id == $asig->id ? 'selected' : '' }}>
                                                        {{ $asig->abreviacion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <span class="text-muted" style="font-size:0.65rem">—</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Toast de notificación --}}
    <div class="toast-horario toast align-items-center text-white border-0" id="toastHorario" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toastMensaje">✔ Guardado correctamente.</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const toastEl  = document.getElementById('toastHorario');
            const toastMsg = document.getElementById('toastMensaje');

            function mostrarToast(mensaje, exito = true) {
                toastEl.classList.remove('bg-success', 'bg-danger');
                toastEl.classList.add(exito ? 'bg-success' : 'bg-danger');
                toastMsg.textContent = mensaje;
                const toast = new bootstrap.Toast(toastEl, { delay: 2500 });
                toast.show();
            }

            document.querySelectorAll('.select-asignatura').forEach(function (select) {
                select.addEventListener('change', function () {
                    const url          = this.dataset.url;
                    const asignaturaId = this.value;
                    const el           = this;

                    el.classList.remove('guardado', 'error');
                    el.disabled = true;

                    fetch(url, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ asignatura_id: asignaturaId }),
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            el.classList.add('guardado');
                            mostrarToast('✔ ' + data.message, true);
                            setTimeout(() => el.classList.remove('guardado'), 2000);
                        } else {
                            el.classList.add('error');
                            mostrarToast('✘ Error al guardar.', false);
                        }
                    })
                    .catch(() => {
                        el.classList.add('error');
                        mostrarToast('✘ Error de conexión.', false);
                    })
                    .finally(() => {
                        el.disabled = false;
                    });
                });
            });
        });
    </script>

</x-app-layout>
