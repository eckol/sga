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

    </div>

    <script>
        document.getElementById('selectAnio').addEventListener('change', function () {
            const url = new URL(window.location.href);
            url.searchParams.set('anio', this.value);
            window.location.href = url.toString();
        });
    </script>

</x-app-layout>