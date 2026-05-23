<x-app-layout>

    <style>
        .grilla-docentes th,
        .grilla-docentes td {
            font-size: 0.7rem !important;
            padding: 0.15rem 0.25rem !important;
            vertical-align: middle;
            white-space: nowrap;
        }

        .grilla-docentes thead th {
            background-color: #1e3a5f;
            color: #fff;
            text-align: center;
            font-weight: 600;
        }

        .grilla-docentes thead th.th-asignatura {
            background-color: #0d2137;
            text-align: left;
        }

        .grilla-docentes tbody tr:nth-child(odd) td {
            background-color: #f8fbff;
        }

        .grilla-docentes tbody tr:hover td {
            background-color: #e8f1ff;
        }

        .td-asignatura {
            font-weight: 600;
            color: #1e3a5f;
            max-width: 180px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .select-docente {
            font-size: 0.68rem !important;
            padding: 0.1rem 0.2rem !important;
            border-radius: 5px !important;
            width: 100%;
            min-width: 110px;
            border: 1px solid #b0c8e8;
            background-color: #f8fbff;
            cursor: pointer;
            transition: border-color 0.2s, background-color 0.2s;
        }

        .select-docente:focus {
            border-color: #1e3a5f;
            outline: none;
            background-color: #fff;
        }

        .select-docente.guardado {
            background-color: #d4edda !important;
            border-color: #28a745 !important;
        }

        .select-docente.error {
            background-color: #f8d7da !important;
            border-color: #dc3545 !important;
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

        .toast-docente {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            min-width: 220px;
            font-size: 0.78rem;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Docentes por Asignatura y Grado/Curso</h2>
    </x-slot>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- GRILLA 1: 3ER. CICLO E.E.B. (7MO. GRADO A → 9NO. GRADO B)   --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="mt-3 mb-4">
        <div class="titulo-grilla">📚 3ER. CICLO E.E.B.</div>
        <div class="card-grilla">
            <table class="table table-sm table-bordered table-hover mb-0 grilla-docentes">
                <thead>
                    <tr>
                        <th class="th-asignatura" style="min-width:150px">Asignatura</th>
                        @foreach($grados as $gc)
                            <th>{{ $gc->gradocurso }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($asignaturasGrado as $asig)
                        <tr>
                            <td class="td-asignatura" title="{{ $asig->asignatura }}">{{ $asig->asignatura }}</td>
                            @foreach($grados as $gc)
                                @php
                                    $reg = $mapa[$asig->id][$gc->id] ?? null;
                                @endphp
                                <td>
                                    <select
                                        class="select-docente"
                                        data-asignatura="{{ $asig->id }}"
                                        data-grado="{{ $gc->id }}"
                                        data-url="{{ route('academica.docentes-asignatura.update', [$asig->id, $gc->id]) }}">
                                        <option value="">— Sin asignar —</option>
                                        @foreach($colaboradores as $col)
                                            <option value="{{ $col->id }}"
                                                {{ $reg && $reg->colaborador_id == $col->id ? 'selected' : '' }}>
                                                {{ $col->apellidos }}, {{ $col->nombres }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- GRILLA 2: NIVEL MEDIO (1ER. CURSO A → 3ER. CURSO B)          --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="mb-4">
        <div class="titulo-grilla">🎓 NIVEL MEDIO</div>
        <div class="card-grilla">
            <table class="table table-sm table-bordered table-hover mb-0 grilla-docentes">
                <thead>
                    <tr>
                        <th class="th-asignatura" style="min-width:150px">Asignatura</th>
                        @foreach($cursos as $gc)
                            <th>{{ $gc->gradocurso }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($asignaturasCurso as $asig)
                        <tr>
                            <td class="td-asignatura" title="{{ $asig->asignatura }}">{{ $asig->asignatura }}</td>
                            @foreach($cursos as $gc)
                                @php
                                    $reg = $mapa[$asig->id][$gc->id] ?? null;
                                @endphp
                                <td>
                                    <select
                                        class="select-docente"
                                        data-asignatura="{{ $asig->id }}"
                                        data-grado="{{ $gc->id }}"
                                        data-url="{{ route('academica.docentes-asignatura.update', [$asig->id, $gc->id]) }}">
                                        <option value="">— Sin asignar —</option>
                                        @foreach($colaboradores as $col)
                                            <option value="{{ $col->id }}"
                                                {{ $reg && $reg->colaborador_id == $col->id ? 'selected' : '' }}>
                                                {{ $col->apellidos }}, {{ $col->nombres }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Toast de notificación --}}
    <div class="toast-docente toast align-items-center text-white border-0" id="toastDocente" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toastMensaje">✔ Guardado correctamente.</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const toastEl  = document.getElementById('toastDocente');
            const toastMsg = document.getElementById('toastMensaje');

            function mostrarToast(mensaje, exito = true) {
                toastEl.classList.remove('bg-success', 'bg-danger');
                toastEl.classList.add(exito ? 'bg-success' : 'bg-danger');
                toastMsg.textContent = mensaje;
                const toast = new bootstrap.Toast(toastEl, { delay: 2500 });
                toast.show();
            }

            document.querySelectorAll('.select-docente').forEach(function (select) {
                select.addEventListener('change', function () {
                    const url          = this.dataset.url;
                    const colaboradorId = this.value;
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
                        body: JSON.stringify({ colaborador_id: colaboradorId }),
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
