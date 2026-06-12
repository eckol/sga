<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ficha del Alumno - {{ $alumno->apellidos }}, {{ $alumno->nombres }}</title>
    <style>
        @page {
            size: 215.9mm 330.2mm;
            /* Folio */
            margin: 15mm;
        }

        body {
            font-family: 'Arial Narrow', Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.2;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .no-print-btn {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1000;
        }

        @media print {
            .no-print-btn {
                display: none;
            }
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        .school-name {
            font-size: 8pt;
            display: block;
        }

        .report-title {
            font-size: 11pt;
            font-weight: bold;
            margin-top: 2px;
            display: block;
        }

        .section {
            margin-bottom: 10px;
            clear: both;
        }

        .section-title {
            font-size: 10pt;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
            margin-bottom: 5px;
            padding-bottom: 2px;
            text-transform: uppercase;
        }

        .data-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 3px;
        }

        .data-item {
            margin-right: 15px;
            margin-bottom: 2px;
        }

        .label {
            font-weight: bold;
        }

        .photo-container {
            float: right;
            width: 80px;
            height: 100px;
            border: 0px solid #ccc;
            text-align: center;
            margin-left: 10px;
        }

        .photo-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
        }

        table th,
        table td {
            border: 1px solid #eee;
            padding: 3px;
            text-align: left;
        }

        table th {
            background-color: #f9f9f9;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            font-size: 7pt;
            text-align: right;
            color: #777;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
        }

        .separator {
            height: 1px;
            background: #eee;
            margin: 5px 0;
        }
    </style>
</head>

<body onload="window.print()">
    <button class="no-print-btn" onclick="window.print()">Imprimir / Guardar PDF</button>

    <div class="header">
        <span class="school-name">Escuela Básica N° 1138 Privada Subvencionada "Santa Teresita" - Luque, Paraguay</span>
        <span class="report-title">FICHA DEL ALUMNO</span>
    </div>

    <!-- DATOS DEL ALUMNO -->
    <div class="section">
        <div class="photo-container">
            @if($alumno->foto)
                <img src="{{ asset('img/alumnos/' . $alumno->foto) }}" alt="Foto">
            @else
                <img src="{{ asset('img/alumnos/alumno.jpg') }}" alt="Sin Foto">
            @endif
        </div>
        <div class="section-title">Datos del Alumno</div>
        <div class="data-row">
            <div class="data-item"><span class="label">ID:</span> {{ $alumno->id }}</div>
            <div class="data-item"><span class="label">Apellidos:</span> {{ $alumno->apellidos }}</div>
            <div class="data-item"><span class="label">Nombres:</span> {{ $alumno->nombres }}</div>
        </div>
        <div class="data-row">
            <div class="data-item"><span class="label">C.I. N°:</span> {{ $alumno->cid }}</div>
            <div class="data-item"><span class="label">Nacionalidad:</span>
                {{ $alumno->nacionalidad->nacionalidad ?? 'N/A' }}</div>
            <div class="data-item"><span class="label">F. Nac.:</span>
                {{ $alumno->fnac ? \Carbon\Carbon::parse($alumno->fnac)->format('d/m/Y') : 'N/A' }}</div>
            <div class="data-item"><span class="label">Sexo:</span> {{ $alumno->sexo->sexo ?? 'N/A' }}</div>
        </div>
        <div class="data-row">
            <div class="data-item"><span class="label">Dirección:</span> {{ $alumno->direccion }}</div>
            <div class="data-item"><span class="label">Barrio:</span> {{ $alumno->barrio }}</div>
            <div class="data-item"><span class="label">Ciudad:</span> {{ $alumno->ciudad->ciudad ?? 'N/A' }}</div>
        </div>
        <div class="data-row">
            <div class="data-item"><span class="label">Teléfono:</span> {{ $alumno->telefono }}</div>
            <div class="data-item"><span class="label">Email:</span> {{ $alumno->email }}</div>
            <div class="data-item"><span class="label">Contraseña:</span> {{ $alumno->passwd }}</div>
            <div class="data-item"><span class="label">Activo:</span> {{ $alumno->activo }}</div>
        </div>
        <div class="data-row">
            <div class="data-item"><span class="label">Vive con:</span> {{ $alumno->vivecon->vive_con ?? 'N/A' }}</div>
            <div class="data-item"><span class="label">Salud:</span> {{ $alumno->salud }}</div>
        </div>
        <div class="data-row">
            <div class="data-item"><span class="label">Observaciones:</span> {{ $alumno->observaciones }}</div>
        </div>
    </div>

    <!-- DATOS DE TUTORES -->
    <div class="section">
        <div class="section-title">Datos de Responsables / Tutores</div>
        <div class="grid-3">
            <!-- MADRE -->
            <div class="tutor-box">
                <div class="label" style="text-decoration: underline; margin-bottom: 3px;">MADRE</div>
                @if($alumno->madre)
                    <div><span class="label">ID:</span> {{ $alumno->madre->id }}</div>
                    <div><span class="label">Nombre:</span> {{ $alumno->madre->nombre }}</div>
                    <div><span class="label">C.I.:</span> {{ $alumno->madre->cid }}</div>
                    <div><span class="label">Dir.:</span> {{ $alumno->madre->direccion }}</div>
                    <div><span class="label">Bario:</span> {{ $alumno->madre->barrio }}</div>
                    <div><span class="label">Ciudad:</span> {{ $alumno->madre->ciudad->ciudad ?? 'N/A' }}</div>
                    <div><span class="label">Tels:</span> {{ $alumno->madre->telefono1 }} / {{ $alumno->madre->telefono2 }}
                    </div>
                    <div><span class="label">Email:</span> {{ $alumno->madre->email }}</div>
                    <div><span class="label">Prof.:</span> {{ $alumno->madre->profesion }}</div>
                    <div><span class="label">Trabajo:</span> {{ $alumno->madre->lugartrabajo }}</div>
                    <div><span class="label">RUC/DV:</span> {{ $alumno->madre->ruc }} - {{ $alumno->madre->dv }}</div>
                @else
                    <div style="font-style: italic;">No registrada</div>
                @endif
            </div>

            <!-- PADRE -->
            <div class="tutor-box">
                <div class="label" style="text-decoration: underline; margin-bottom: 3px;">PADRE</div>
                @if($alumno->padre)
                    <div><span class="label">ID:</span> {{ $alumno->padre->id }}</div>
                    <div><span class="label">Nombre:</span> {{ $alumno->padre->nombre }}</div>
                    <div><span class="label">C.I.:</span> {{ $alumno->padre->cid }}</div>
                    <div><span class="label">Dir.:</span> {{ $alumno->padre->direccion }}</div>
                    <div><span class="label">Bario:</span> {{ $alumno->padre->barrio }}</div>
                    <div><span class="label">Ciudad:</span> {{ $alumno->padre->ciudad->ciudad ?? 'N/A' }}</div>
                    <div><span class="label">Tels:</span> {{ $alumno->padre->telefono1 }} / {{ $alumno->padre->telefono2 }}
                    </div>
                    <div><span class="label">Email:</span> {{ $alumno->padre->email }}</div>
                    <div><span class="label">Prof.:</span> {{ $alumno->padre->profesion }}</div>
                    <div><span class="label">Trabajo:</span> {{ $alumno->padre->lugartrabajo }}</div>
                    <div><span class="label">RUC/DV:</span> {{ $alumno->padre->ruc }} - {{ $alumno->padre->dv }}</div>
                @else
                    <div style="font-style: italic;">No registrado</div>
                @endif
            </div>

            <!-- ENCARGADO -->
            <div class="tutor-box">
                <div class="label" style="text-decoration: underline; margin-bottom: 3px;">ENCARGADO</div>
                @if($alumno->encargado)
                    <div><span class="label">ID:</span> {{ $alumno->encargado->id }}</div>
                    <div><span class="label">Nombre:</span> {{ $alumno->encargado->nombre }}</div>
                    <div><span class="label">C.I.:</span> {{ $alumno->encargado->cid }}</div>
                    <div><span class="label">Dir.:</span> {{ $alumno->encargado->direccion }}</div>
                    <div><span class="label">Bario:</span> {{ $alumno->encargado->barrio }}</div>
                    <div><span class="label">Ciudad:</span> {{ $alumno->encargado->ciudad->ciudad ?? 'N/A' }}</div>
                    <div><span class="label">Tels:</span> {{ $alumno->encargado->telefono1 }} /
                        {{ $alumno->encargado->telefono2 }}
                    </div>
                    <div><span class="label">Email:</span> {{ $alumno->encargado->email }}</div>
                    <div><span class="label">Prof.:</span> {{ $alumno->encargado->profesion }}</div>
                    <div><span class="label">Trabajo:</span> {{ $alumno->encargado->lugartrabajo }}</div>
                    <div><span class="label">RUC/DV:</span> {{ $alumno->encargado->ruc }} - {{ $alumno->encargado->dv }}
                    </div>
                @else
                    <div style="font-style: italic;">No registrado</div>
                @endif
            </div>
        </div>
    </div>

    <!-- ÚLTIMA INSCRIPCIÓN -->
    <div class="section">
        <div class="section-title">Datos Última Inscripción</div>
        @php $ultima = $alumno->inscripciones->first(); @endphp
        @if($ultima)
            <div class="data-row">
                <div class="data-item"><span class="label">ID:</span> {{ $ultima->id }}</div>
                <div class="data-item"><span class="label">Fecha:</span>
                    {{ $ultima->fecha ? \Carbon\Carbon::parse($ultima->fecha)->format('d/m/Y') : '' }}</div>
                <div class="data-item"><span class="label">Grado/Curso:</span> {{ $ultima->grado->gradocurso ?? '' }}</div>
                <div class="data-item"><span class="label">Firmante:</span> {{ $ultima->firmante_nombre }}
                    ({{ $ultima->firmante_rol }})</div>
            </div>
        @else
            <div style="font-style: italic;">Sin inscripciones registradas</div>
        @endif
    </div>

    <!-- ASISTENCIA -->
    <div class="section">
        <div class="section-title">Resumen de Asistencia Mensual (Año Lectivo {{ date('Y') }})</div>
        <table>
            <thead>
                <tr>
                    <th>Mes</th>
                    <th style="text-align:center">Presente</th>
                    <th style="text-align:center">Ausencia</th>
                    <th style="text-align:center">Ausencia Just.</th>
                    <th style="text-align:center">Llegada Tardía</th>
                    <th style="text-align:center">Total Días</th>
                </tr>
            </thead>
            <tbody>
                @foreach($asistenciaMensual as $mes => $datos)
                    <tr>
                        <td>{{ $mes }}</td>
                        <td style="text-align:center">{{ $datos['Presente'] }}</td>
                        <td style="text-align:center">{{ $datos['Ausente'] }}</td>
                        <td style="text-align:center">{{ $datos['Justificado'] }}</td>
                        <td style="text-align:center">{{ $datos['Tardanza'] }}</td>
                        <td style="text-align:center; font-weight:bold;">{{ array_sum($datos) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- REGISTRO ANECDÓTICO -->
    <div class="section">
        <div class="section-title">Registro Anecdótico</div>
        @if($alumno->registrosAnecdoticos->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th width="30">ID</th>
                        <th width="70">Fecha</th>
                        <th width="150">Asignatura / Grado</th>
                        <th>Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alumno->registrosAnecdoticos as $reg)
                        <tr>
                            <td>{{ $reg->id }}</td>
                            <td>{{ $reg->fecha ? \Carbon\Carbon::parse($reg->fecha)->format('d/m/Y') : '' }}</td>
                            <td>{{ $reg->asignatura->asignatura ?? 'N/A' }} - {{ $reg->gradoCurso->gradocurso ?? 'N/A' }}</td>
                            <td>{{ $reg->detalle }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="font-style: italic; font-size: 8pt;">Sin registros anecdóticos.</div>
        @endif
    </div>

    <!-- ENTREVISTAS -->
    <div class="section">
        <div class="section-title">Entrevistas (Alumno y Responsables)</div>
        @php
            $todasEntrevistas = collect();
            foreach ($alumno->entrevistasAlumnos as $ea) {
                $todasEntrevistas->push(['fecha' => $ea->fecha, 'tipo' => 'Alumno', 'colab' => $ea->entrevistador, 'motivo' => $ea->motivo, 'obs' => $ea->observaciones]);
            }
            foreach ($alumno->entrevistasResponsables as $er) {
                $todasEntrevistas->push(['fecha' => $er->fecha, 'tipo' => 'Responsable', 'colab' => $er->entrevistador, 'motivo' => $er->motivo, 'obs' => $er->observaciones]);
            }
            $todasEntrevistas = $todasEntrevistas->sortByDesc('fecha');
        @endphp

        @if($todasEntrevistas->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th width="70">Fecha</th>
                        <th width="80">Tipo</th>
                        <th width="150">Colaborador</th>
                        <th width="150">Motivo</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($todasEntrevistas as $ent)
                        <tr>
                            <td>{{ $ent['fecha'] ? \Carbon\Carbon::parse($ent['fecha'])->format('d/m/Y') : '' }}</td>
                            <td>{{ $ent['tipo'] }}</td>
                            <td>{{ $ent['colab'] ? $ent['colab']->apellidos . ', ' . $ent['colab']->nombres : 'N/A' }}</td>
                            <td>{{ $ent['motivo'] }}</td>
                            <td>{{ $ent['obs'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="font-style: italic; font-size: 8pt;">Sin entrevistas registradas.</div>
        @endif
    </div>

    <div class="footer">
        Generado el: {{ date('d/m/Y H:i') }} - SGA Santa Teresita
    </div>
</body>

</html>