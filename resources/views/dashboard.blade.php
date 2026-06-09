<x-app-layout>

    <style>
        /* ── Select Año Lectivo ── */
        .form-select-sm,
        .form-select-sm option {
            font-size: 0.75rem !important;
            padding-top: 0.2rem;
            padding-bottom: 0.2rem;
        }

        .form-select-sm {
            border-radius: 8px !important;
        }

        .year-selector-wrap {
            background: #fff;
            border-radius: 8px;
            padding: 6px 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .year-selector-wrap label {
            font-size: 0.75rem;
            white-space: nowrap;
        }

        /* ── Info Boxes ── */
        .info-box {
            border-radius: 8px;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .info-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 14px rgba(0, 0, 0, 0.16);
        }

        .info-box .icon-wrap {
            font-size: 1.2rem;
            opacity: 0.85;
            flex-shrink: 0;
        }

        .info-box .text-wrap {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
            min-width: 0;
        }

        .info-box .ciclo-label {
            font-size: 0.6rem;
            font-weight: 500;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .info-box .total-num {
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        /* ── Colores sólidos ── */
        .box-color-1 {
            background: #5c6bc0;
        }

        .box-color-2 {
            background: #e53935;
        }

        .box-color-3 {
            background: #039be5;
        }

        .box-color-4 {
            background: #43a047;
        }

        .box-color-5 {
            background: #fb8c00;
        }

        .box-color-total {
            background: #37474f;
        }

        /* ── Nuevos Colores ── */
        .box-asistencia {
            background: #2e7d32;
        }

        .box-cobranza {
            background: #f9a825;
        }

        .box-personal {
            background: #1565c0;
        }

        .box-examen {
            background: #455a64;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-3 px-4">


        {{-- Selector de Año Lectivo --}}
        <div class="year-selector-wrap mb-3">
            <i class="fas fa-calendar-alt text-primary" style="font-size: 0.85rem;"></i>
            <label class="fw-bold mb-0 text-secondary">Cantidad de alumnos por Ciclos - Año Lectivo:</label>
            <select id="selectAnio" class="form-select form-select-sm" style="max-width: 100px;">
                @foreach($anios as $anio)
                    <option value="{{ $anio }}" {{ $anio == $anioSeleccionado ? 'selected' : '' }}>{{ $anio }}</option>
                @endforeach
            </select>
        </div>

        {{-- Info Boxes — 6 columnas en desktop, 2 en tablet, 1 en móvil --}}
        <div class="row g-2" id="stats-wrap">

            @php $colors = ['box-color-1', 'box-color-2', 'box-color-3', 'box-color-4', 'box-color-5']; @endphp

            @foreach($statsPorCiclo as $i => $stat)
                <div class="col-6 col-sm-4 col-lg-2">
                    <div class="info-box {{ $colors[$i % count($colors)] }}">
                        <div class="icon-wrap">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="text-wrap">
                            <span class="ciclo-label">{{ $stat['ciclo'] }}</span>
                            <span class="total-num">{{ $stat['total'] }}</span>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Total --}}
            <div class="col-6 col-sm-4 col-lg-2">
                <div class="info-box box-color-total">
                    <div class="icon-wrap">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="text-wrap">
                        <span class="ciclo-label">Total Alumnos</span>
                        <span class="total-num">{{ $totalAlumnos }}</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- Nueva fila para DataTable y Gráfica --}}
        <div class="row g-3 mt-2 d-flex align-items-stretch">
            {{-- Datatable alumnos (Izquierda) --}}
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; overflow: hidden;">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="mb-0 fw-bold text-secondary">
                            <i class="fas fa-user-clock me-2 text-primary"></i>Últimos Alumnos Inscriptos
                        </h6>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="tableUltimosAlumnos"
                                style="font-size: 0.75rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">Apellidos</th>
                                        <th class="border-0">Nombres</th>
                                        <th class="border-0">Grado/Curso</th>
                                        <th class="border-0">Teléfono</th>
                                    </tr>
                                </thead>
                                <tbody class="border-top-0">
                                    @forelse($ultimosAlumnos as $insc)
                                        <tr>
                                            <td>{{ $insc->alumno->apellidos }}</td>
                                            <td>{{ $insc->alumno->nombres }}</td>
                                            <td>{{ $insc->grado->gradocurso ?? 'N/A' }}</td>
                                            <td>{{ $insc->alumno->telefono ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">No hay inscripciones
                                                recientes</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Gráfica de géneros (Derecha) --}}
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; overflow: hidden;">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="mb-0 fw-bold text-secondary">
                            <i class="fas fa-chart-pie me-2 text-primary"></i>Distribución por Género
                        </h6>
                    </div>
                    <div class="card-body d-flex flex-column" style="min-height: 250px;">
                        <div style="flex-grow: 1;">
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KPIs Row (Movido al final) --}}
        <div class="row g-2 mt-4">
            {{-- Asistencia --}}
            <div class="col-6 col-sm-3">
                <div class="info-box box-asistencia">
                    <div class="icon-wrap">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="text-wrap">
                        <span class="ciclo-label">Asistencia Hoy</span>
                        <span class="total-num">{{ $porcentajeAsistencia }}%</span>
                    </div>
                </div>
            </div>
            {{-- Cobranza --}}
            <div class="col-6 col-sm-3">
                <div class="info-box box-cobranza">
                    <div class="icon-wrap">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div class="text-wrap">
                        <span class="ciclo-label">Alumnos al Día</span>
                        <span class="total-num">{{ $alumnosAlDiaCount }}</span>
                    </div>
                </div>
            </div>
            {{-- Personal --}}
            <div class="col-6 col-sm-3">
                <div class="info-box box-personal">
                    <div class="icon-wrap">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div class="text-wrap">
                        <span class="ciclo-label">Personal Total</span>
                        <span class="total-num">{{ $totalColaboradores }}</span>
                    </div>
                </div>
            </div>
            {{-- Exámenes del día --}}
            <div class="col-6 col-sm-3">
                <div class="info-box box-examen">
                    <div class="icon-wrap">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="text-wrap">
                        <span class="ciclo-label">EXÁMENES DEL DÍA</span>
                        <span class="total-num">{{ $examenesHoyCount }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.getElementById('selectAnio').addEventListener('change', function () {
            const url = new URL(window.location.href);
            url.searchParams.set('anio', this.value);
            window.location.href = url.toString();
        });

        // Configuración de la gráfica de Géneros (Barras)
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('genderChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Masculinos (M)', 'Femeninos (F)'],
                    datasets: [{
                        label: 'Cantidad de Alumnos',
                        data: [{{ $countM }}, {{ $countF }}],
                        backgroundColor: ['#42a5f5', '#f06292'], // Azul para varones, Rosa para niñas
                        borderWidth: 1,
                        borderRadius: 5,
                        borderColor: ['#1e88e5', '#d81b60']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'x', // Barras verticales
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // Ocultar leyenda ya que los ejes lo indican
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return `Total: ${context.parsed.y}`;
                                }
                            }
                        }
                    }
                }
            });

            // Inicializar Datatable simple si se desea, aunque con 5 filas no es estrictamente necesario paginar
            // $('#tableUltimosAlumnos').DataTable({ ... });
        });
    </script>
</x-app-layout>