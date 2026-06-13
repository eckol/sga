<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Contrato de Matrícula – {{ $alumno->apellidos }}, {{ $alumno->nombres }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9.5pt;
            color: #111;
            line-height: 1.45;
        }

        /* ── Encabezado institucional ── */
        .encabezado {
            text-align: center;
            margin-bottom: 6pt;
            border-bottom: 2px solid #1a1a1a;
            padding-bottom: 6pt;
        }

        .encabezado .institucion-nombre {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
        }

        .encabezado .institucion-sub {
            font-size: 9pt;
            margin-top: 2pt;
        }

        /* ── Título del documento ── */
        .titulo-contrato {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1pt;
            margin: 8pt 0 6pt 0;
            text-decoration: underline;
        }

        /* ── Bloque introductorio ── */
        .intro {
            text-align: justify;
            margin-bottom: 6pt;
        }

        /* ── Cláusulas ── */
        .clausulas {
            margin-bottom: 8pt;
        }

        .clausula {
            text-align: justify;
            margin-bottom: 5pt;
        }

        .clausula-num {
            font-weight: bold;
        }

        /* ── Sección de firmas ── */
        .firmas {
            margin-top: 20pt;
            width: 100%;
        }

        .firmas table {
            width: 100%;
            border-collapse: collapse;
        }

        .firma-col {
            width: 48%;
            text-align: center;
            vertical-align: top;
        }

        .firma-linea {
            border-top: 1px solid #111;
            margin: 0 20pt;
            margin-top: 30pt;
        }

        .firma-nombre {
            font-weight: bold;
            font-size: 9pt;
            margin-top: 3pt;
        }

        .firma-datos {
            font-size: 8.5pt;
        }

        /* ── Autorizaciones ── */
        .autorizaciones {
            margin-top: 16pt;
            border-top: 1px dashed #555;
            padding-top: 10pt;
        }

        .autorizacion-bloque {
            margin-bottom: 12pt;
        }

        .autorizacion-bloque .aut-titulo {
            font-weight: bold;
            font-size: 9.5pt;
            margin-bottom: 3pt;
        }

        .autorizacion-bloque .aut-texto {
            text-align: justify;
            font-size: 9pt;
        }

        .checkbox-sim {
            display: inline-block;
            width: 11pt;
            height: 11pt;
            border: 1.5px solid #111;
            vertical-align: middle;
            margin-right: 4pt;
            text-align: center;
            line-height: 11pt;
            font-size: 9pt;
        }

        .checkbox-sim.checked::after {
            content: '✓';
        }

        /* ── Firma final de autorizaciones ── */
        .firma-final {
            margin-top: 20pt;
            text-align: center;
        }

        .firma-final .firma-linea-final {
            border-top: 1px solid #111;
            width: 60%;
            margin: 30pt auto 0 auto;
        }

        /* ── Utilitarios ── */
        .bold {
            font-weight: bold;
        }

        .upper {
            text-transform: uppercase;
        }

        .small {
            font-size: 8.5pt;
        }

        .text-center {
            text-align: center;
        }

        .mt-4 {
            margin-top: 4pt;
        }

        .mt-8 {
            margin-top: 8pt;
        }

        @page {
            margin: 15mm 18mm 15mm 18mm;
            size: A4 portrait;
        }
    </style>
</head>

<body>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- ENCABEZADO --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div class="encabezado">
        <div class="institucion-nombre">Congregación de Hermanas Dominicas del Santísimo Sacramento</div>
        <div class="institucion-sub">
            Escuela Básica N° 1138 Privada Subvencionada y Colegio Privado "Santa Teresita"
        </div>
    </div>

    <div class="titulo-contrato">Contrato de Matrícula para Servicios Educacionales</div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- INTRO: datos de partes --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div class="intro">
        En la ciudad de <span class="bold">Luque</span>, en fecha
        <span class="bold">{{ \Carbon\Carbon::parse($inscripcion->fecha)->isoFormat('D [de] MMMM [de] Y') }}</span>,
        el/la Señor/a
        <span class="bold upper">{{ $firmante->nombre }}</span>,
        Cédula de Identidad N° <span class="bold">{{ number_format($firmante->cid, 0, ',', '.') }}</span>,
        de profesión <span class="bold">{{ $firmante->profesion ?? '—' }}</span>,
        domiciliado en <span class="bold">{{ $firmante->direccion ?? '—' }}</span>,
        <span class="bold">{{ $firmante->ciudad->ciudad ?? '—' }}</span>,
        quien en nombre y representación (Art. 40 inc. b) C.C. y Art. 71 y 106 C.N.A., de su hijo/a
        <span class="bold upper">{{ $alumno->nombres }} {{ $alumno->apellidos }}</span>,
        Cédula de Identidad N° <span class="bold">{{ $alumno->cid ?? '—' }}</span>,
        nacido el <span class="bold">{{ \Carbon\Carbon::parse($alumno->fnac)->isoFormat('D [de] MMMM [de] Y') }}</span>,
        domiciliado en <span class="bold">{{ $alumno->direccion ?? '—' }}</span>,
        <span class="bold">{{ $alumno->ciudad->ciudad ?? '—' }}</span>,
        quien vive con su/s <span
            class="bold">{{ $alumno->vivecon->vive_con ?? $alumno->vivecon->vivecon ?? '—' }}</span>,
        y que proviene de <span class="bold">{{ $inscripcion->procede ?? '—' }}</span>,
        con email <span class="bold">{{ $alumno->email ?? '—' }}</span>
        / contraseña <span class="bold">{{ $alumno->passwd ?? '—' }}</span>,
        procede a suscribir el presente
        <span class="bold">CONTRATO DE MATRÍCULA PARA SERVICIOS EDUCACIONALES</span>,
        previa lectura, inscribiendo a su hijo/a o menor a cargo, en adelante denominado
        <span class="bold">"ESTUDIANTE"</span>, en el
        <span class="bold">{{ $grado->gradocurso }}</span>,
        turno <span class="bold">{{ $grado->turno }}</span>.
    </div>

    <div class="intro">
        Por una parte, el/los representante/s (padres o encargados de alumnos), en adelante denominados
        <span class="bold">"CONTRATANTE/S"</span>, y por otra parte la Institución
        <span class="bold">COLEGIO PRIVADO SANTA TERESITA</span>, propiedad de la
        <span class="bold">CONGREGACIÓN DE HERMANAS DOMINICAS DEL SANTÍSIMO SACRAMENTO</span>,
        representada por la <span class="bold">Hna. Mgtr. MARIA IRENE PAREDES OCAMPOS</span>,
        con C.I. N° 1.086.825, quien ejerce el cargo de Directora Pedagógica, con domicilio en Iturbe N° 85, Luque,
        en adelante mencionada <span class="bold">"INSTITUCIÓN EDUCATIVA"</span>,
        convienen en celebrar el presente <span class="bold">CONTRATO DE SERVICIOS EDUCATIVOS</span>,
        que abarca desde el inicio de las actividades académicas desde febrero de {{ $inscripcion->anio_lectivo }}
        hasta los exámenes complementarios correspondientes al año lectivo {{ $inscripcion->anio_lectivo }},
        comprometiéndose ambas partes a cumplir con los derechos y obligaciones que les son propias por Visión,
        Misión, Identidad y Competencias, reglamentadas en las Normas de Convivencia.
    </div>

    <div class="intro">
        El contratante asume expresamente que él es el primero y fundamental responsable de la educación del
        estudiante como padre, madre o encargado y que, al formar parte de la Comunidad Educativa, manifiesta cuánto
        sigue:
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- CLÁUSULAS --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div class="clausulas">

        <div class="clausula">
            <span class="clausula-num">1.</span> <span class="bold">EXPRESA</span> su conformidad con la Propuesta
            Educativa Dominica, de formar estudiantes con valores,
            basados en principios éticos, morales, cívicos y patrióticos. Acepta los derechos y obligaciones emanadas
            de las disposiciones legales vigentes de la Constitución Nacional, del Código de la Niñez y la Adolescencia,
            del Ministerio de Educación y Ciencias y de las Normas de Convivencia de la Institución.
        </div>

        <div class="clausula">
            <span class="clausula-num">2.</span> <span class="bold">SE COMPROMETE</span> a facilitar que su hijo/a o
            alumno/a a cargo participe, activa y positivamente,
            en las iniciativas educativas que se realicen en la Institución, como ser celebraciones religiosas, eventos
            sociales, culturales, recreativos, deportivos, jornadas de reflexión, campamentos, retiros, reuniones u
            otras
            actividades grupales.
        </div>

        <div class="clausula">
            <span class="clausula-num">3.</span> <span class="bold">ACEPTA QUE</span>, en el Nivel Inicial, estará
            permitido el acompañamiento de los niños durante el período
            de adaptación. Al finalizar dicho período, indefectiblemente deberán dejarlos al cuidado de la maestra.
            No se permite a los padres ingresar con sus hijos en el aula de clase en ningún nivel.
        </div>

        <div class="clausula">
            <span class="clausula-num">4.</span> <span class="bold">SE COMPROMETE</span> a asistir a reuniones,
            encuentros y entrevistas convocadas por la Institución.
        </div>

        <div class="clausula">
            <span class="clausula-num">5.</span> <span class="bold">RECONOCE Y ACEPTA</span> que la Institución se hace
            responsable única y exclusivamente de aquellas
            actividades que cuenten con el consentimiento y la autorización expresa y escrita de la Dirección. Por
            tanto,
            la misma deslinda toda responsabilidad social, económica y jurídica sobre cualquier actividad no académica,
            puramente turística, recreativa o de otra índole organizada por padres, encargados y/o alumnos.
        </div>

        <div class="clausula">
            <span class="clausula-num">6.</span> <span class="bold">SE COMPROMETE</span> a pagar a la Institución en
            concepto de
            <span class="bold">MATRÍCULA</span>, la suma de
            <span class="bold">Gs. {{ number_format($inscripcion->monto_matricula, 0, ',', '.') }}</span>
            en forma de pago único y al CONTADO en la Administración. Este monto no será reembolsable en ningún caso.
            El arancel "INSUMOS UTILIZADOS POR LOS ESTUDIANTES" contempla Aranceles del MEC, avisos, notificaciones,
            test de pruebas escritas, test de pruebas psicopedagógicas, libreta de calificaciones (amarilla y verde),
            inspección médica, servicio médico de primeros auxilios. Durante el proceso de matriculación, se deberá
            registrar un número de RUC para el año lectivo, sin posibilidad de modificarlo posteriormente, a fin de
            facilitar la impresión de las facturas.
        </div>

        <div class="clausula">
            <span class="clausula-num">7.</span> <span class="bold">ACEPTAN QUE</span> el pago del costo total anual de
            la educación de su hijo/a es de
            <span class="bold">Gs. {{ number_format($inscripcion->monto_anualidad, 0, ',', '.') }}</span>
            (monto referido exclusivamente a los servicios educativos, aparte de los montos en concepto de MATRÍCULA
            e INSUMOS UTILIZADOS POR LOS ESTUDIANTES). Mencionado monto deberá ser abonado en
            <span class="bold">10 (diez) cuotas iguales mensuales</span>, desde febrero hasta noviembre del año lectivo
            en la Administración de la Institución o a través de depósito bancario, en la cuenta cuyo número será
            proporcionado oportunamente. El incumplimiento de lo dispuesto en esta cláusula, dará derecho a la
            Institución a la aplicación de lo dispuesto en el Artículo 2 (última parte), de la Ley 5738/16.
            Es obligación de los padres y/o encargados estar al día con las cuotas para poder llevar a cabo los
            proyectos escolares.
        </div>

        <div class="clausula">
            <span class="clausula-num">8.</span> <span class="bold">ACEPTA</span> que la persona que firma el presente
            contrato sea el padre y/o madre y/o el/la tutor/a,
            se hace responsable del alumno durante el período lectivo en curso, asumiendo el costo del servicio
            educativo
            a prestarse. En caso de deserción del alumno, el responsable deberá abonar el costo de los servicios
            recibidos.
        </div>

        <div class="clausula">
            <span class="clausula-num">9.</span> <span class="bold">SE COMPROMETE</span> a acompañar a su hijo/a o
            alumno/a a cargo de forma afectiva y efectivamente,
            a fin de que pueda cumplir con todos sus deberes y los requisitos para su permanencia en la Institución.
            Acepta que, de no aprobar las materias en el examen del período de regularización, dará derecho a la
            cancelación de la matrícula, conforme a las Leyes vigentes.
        </div>

        <div class="clausula">
            <span class="clausula-num">10.</span> <span class="bold">RECONOCE Y ACEPTA</span> que la Institución se
            reserva el derecho de rescindir el presente
            "Contrato de prestación de Servicios Educativos", antes de su vencimiento, o de matricular al alumno/a
            en el siguiente año, en caso de falta grave o reiteradas violaciones de las Normas de Convivencia.
        </div>

        <div class="clausula">
            <span class="clausula-num">11.</span> <span class="bold">LA INSTITUCIÓN PODRÁ OPTAR</span> por las
            instancias del diálogo reflexivo como mecanismo para
            subsanar dificultades de convivencia que pudieran suceder en el transcurso del año lectivo.
        </div>

        <div class="clausula">
            <span class="clausula-num">12.</span> <span class="bold">POR SU PARTE, LA INSTITUCIÓN SE COMPROMETE</span> a
            brindar los servicios educativos que
            corresponden al grado y nivel del/a alumno/a, conforme a su Visión y Misión, a las Normas de Convivencia
            y a su Proyecto Educativo – Pastoral, así como las normas y leyes vigentes en el país en materia de
            educación.
        </div>

    </div>

    <div class="intro">
        En señal de conformidad, se firman en dos ejemplares del mismo tenor, en fecha y lugar indicados al inicio
        del presente documento, haciendo entrega de las Normas de Convivencia relacionada a la función del Padre,
        Madre o Encargado del alumno/a.
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- FIRMAS --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div class="firmas">
        <table>
            <tr>
                <td class="firma-col">
                    <div class="firma-linea"></div>
                    <div class="firma-nombre upper">{{ $firmante->nombre }}</div>
                    <div class="firma-datos">Céd. Id. N°: {{ number_format($firmante->cid, 0, ',', '.') }}</div>
                    @if($firmante->ruc)
                        <div class="firma-datos">RUC: {{ $firmante->ruc }}-{{ $firmante->dv }}</div>
                    @endif
                </td>
                <td class="firma-col">
                    <div class="firma-linea"></div>
                    <div class="firma-nombre">Hna. María Irene Paredes Ocampos</div>
                    <div class="firma-datos">Céd. Id. N°: 1.086.825</div>
                    <div class="firma-datos">Directora Pedagógica / C.S.T.</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- AUTORIZACIONES --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div class="autorizaciones">

        {{-- Autorización mochilas --}}
        <div class="autorizacion-bloque">
            <div class="aut-titulo">Autorización para inspección de mochilas u otros</div>
            <div class="aut-texto">
                <span class="checkbox-sim {{ $inscripcion->aut_mochila === 'Sí' ? 'checked' : '' }}"></span>
                Autorizo suficientemente a las autoridades del Colegio Privado/Escuela Básica N° 1138 Privada
                Subvencionada "Santa Teresita", a realizar controles aleatorios de la mochila de mi hijo/a,
                <span class="bold upper">{{ $alumno->nombres }} {{ $alumno->apellidos }}</span>
                y el uso de detector de metales, según criterio de las mismas.
            </div>
        </div>

        {{-- Autorización fotos --}}
        <div class="autorizacion-bloque">
            <div class="aut-titulo">Autorización para Publicación de material multimedia</div>
            <div class="aut-texto">
                <span class="checkbox-sim {{ $inscripcion->aut_foto === 'Sí' ? 'checked' : '' }}"></span>
                Autorizo a las autoridades del Colegio Privado/Escuela Básica N° 1138 Privada Subvencionada
                "Santa Teresita" a publicar ocasionalmente fotos, videos u otro material multimedia de los eventos
                realizados por esta institución en el que aparezca mi hijo/a,
                <span class="bold upper">{{ $alumno->nombres }} {{ $alumno->apellidos }}</span>,
                en el sitio web y en las cuentas de las redes sociales de esta casa de estudios.
            </div>
        </div>

        {{-- Firma de autorizaciones --}}
        <div class="firma-final">
            <div class="firma-linea-final"></div>
            <div class="firma-nombre upper" style="margin-top:3pt;">{{ $firmante->nombre }}</div>
            <div class="firma-datos">Céd. Id. N°: {{ number_format($firmante->cid, 0, ',', '.') }}</div>
        </div>

    </div>

</body>

</html>