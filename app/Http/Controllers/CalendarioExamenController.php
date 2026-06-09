<?php

namespace App\Http\Controllers;

// Todos los modelos e importaciones deben ir AQUÍ (Fuera y antes de la clase)
use App\Models\CalendarioExamen;
use App\Models\Asignatura;
use App\Models\GradoCurso;
use App\Models\Alumno; // <- Asegúrate de que esté aquí
use App\Mail\NotificacionEventoAlumno; // <- Aquí también
use Illuminate\Support\Facades\Mail; // <- Y aquí
use Illuminate\Http\Request;

class CalendarioExamenController extends Controller
{
    /**
     * Mapa ciclo_label → IDs de grados_cursos.
     * Debe coincidir con el que usa la vista Blade y el JS.
     */
    private const CICLO_IDS = [
        '1er. Ciclo E.E.B.' => [7, 8, 9, 10, 11, 12],
        '2do. Ciclo E.E.B.' => [13, 14, 15, 16, 17, 18],
        '3er. Ciclo E.E.B.' => [19, 20, 21, 22, 23, 24],
        'Nivel Medio' => [25, 26, 27, 28, 29, 30],
    ];

    public function index()
    {
        $asignaturas = Asignatura::orderBy('asignatura', 'asc')->get();

        // Todos los grados de los ciclos que maneja este módulo
        $todosLosIds = array_merge(...array_values(self::CICLO_IDS));

        $grados = GradoCurso::whereIn('id', $todosLosIds)
            ->orderBy('id', 'asc')
            ->get();

        $examenes = CalendarioExamen::with([
            'grado_curso',
            'asignatura1_rel',
            'asignatura2_rel',
            'asignatura3_rel',
        ])->orderBy('fecha')->orderBy('grado_curso_id')->get();

        // Asignar etiqueta de ciclo a cada examen
        foreach ($examenes as $ex) {
            $ex->ciclo = 'Otro';
            foreach (self::CICLO_IDS as $label => $ids) {
                if (in_array($ex->grado_curso_id, $ids)) {
                    $ex->ciclo = $label;
                    break;
                }
            }
        }

        return view('academica.calendario_examenes.index', compact(
            'asignaturas',
            'grados',
            'examenes'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'etapa' => 'required|string',
            'tipo_prueba' => 'required|string',
            'grado_curso_id' => 'required|exists:grados_cursos,id',
            'asignatura1' => 'nullable|exists:asignaturas,id',
            'asignatura2' => 'nullable|exists:asignaturas,id',
            'asignatura3' => 'nullable|exists:asignaturas,id',
        ]);

        $examen = CalendarioExamen::create($request->all());
        $examen->load(['asignatura1_rel', 'asignatura2_rel', 'asignatura3_rel']);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registro creado correctamente.',
                'examen' => $examen,
            ]);
        }

        return redirect()->route('academica.calendario-examenes.index')
            ->with('success', 'Registro de calendario agregado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fecha' => 'required|date',
            'etapa' => 'required|string',
            'tipo_prueba' => 'required|string',
            'grado_curso_id' => 'required|exists:grados_cursos,id',
            'asignatura1' => 'nullable|exists:asignaturas,id',
            'asignatura2' => 'nullable|exists:asignaturas,id',
            'asignatura3' => 'nullable|exists:asignaturas,id',
        ]);

        $examen = CalendarioExamen::findOrFail($id);
        $examen->update($request->all());
        $examen->load(['asignatura1_rel', 'asignatura2_rel', 'asignatura3_rel']);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registro actualizado correctamente.',
                'examen' => $examen,
            ]);
        }

        return redirect()->route('academica.calendario-examenes.index')
            ->with('success', 'Registro de calendario actualizado exitosamente.');
    }

    public function destroy(Request $request, $id)
    {
        $examen = CalendarioExamen::findOrFail($id);
        $examen->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registro eliminado correctamente.',
            ]);
        }

        return redirect()->route('academica.calendario-examenes.index')
            ->with('success', 'Registro de calendario eliminado exitosamente.');
    }

    public function notificarBloque(Request $request)
    {
        $request->validate([
            'ciclo' => 'required|string',
            'etapa' => 'required|string',
            'tipo_prueba' => 'required|string',
        ]);

        $ciclo = $request->input('ciclo');
        $etapa = $request->input('etapa');
        $tipo = $request->input('tipo_prueba');

        // 1. Obtener todos los exámenes del bloque para armar el resumen
        $examenes = CalendarioExamen::with(['asignatura1_rel', 'asignatura2_rel', 'asignatura3_rel'])
            ->where('ciclo', $ciclo)
            ->where('etapa', $etapa)
            ->where('tipo_prueba', $tipo)
            ->orderBy('fecha', 'asc')
            ->get();

        if ($examenes->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No existen exámenes registrados en este bloque para notificar.'
            ], 422);
        }

        // Agrupamos los grados involucrados en este bloque específico
        $gradoIds = $examenes->pluck('grado_curso_id')->unique()->toArray();

        // 2. Traer los alumnos inscritos en esos grados
        $alumnos = Alumno::whereIn('cid', function ($query) use ($gradoIds) {
            $query->select('alumno_cid')
                ->from('inscripciones')
                ->whereIn('grado_curso_id', $gradoIds);
        })->get();

        if ($alumnos->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron alumnos inscriptos en los grados de este bloque.'
            ], 422);
        }

        // 3. Procesar y despachar los correos a la cola (queue)
        $enviados = 0;
        foreach ($alumnos as $alumno) {
            $emails = $alumno->getResponsablesEmails();
            if (empty($emails)) {
                continue;
            }

            // Filtramos los exámenes que corresponden estrictamente al grado del alumno actual
            $examenesDelAlumno = $examenes->where('grado_curso_id', $alumno->inscripciones->whereIn('grado_curso_id', $gradoIds)->first()->grado_curso_id ?? null);

            if ($examenesDelAlumno->isEmpty()) {
                continue;
            }

            // Estructuramos el detalle del cronograma para el correo en texto limpio
            $detalle = "Cronograma de Evaluaciones - Etapa: {$etapa} ({$tipo})\n";
            $detalle .= "--------------------------------------------------\n";
            foreach ($examenesDelAlumno as $ex) {
                $fechaFormateada = date('d/mm/Y', strtotime($ex->fecha));
                $asig1 = $ex->asignatura1_rel->asignatura ?? '';
                $asig2 = $ex->asignatura2_rel ? ' / ' . $ex->asignatura2_rel->asignatura : '';
                $asig3 = $ex->asignatura3_rel ? ' / ' . $ex->asignatura3_rel->asignatura : '';

                $detalle .= "• {$fechaFormateada} -> {$asig1}{$asig2}{$asig3}\n";
            }

            $nombreCompleto = $alumno->nombres . ' ' . $alumno->apellidos;

            // Se despacha de forma nativa a la cola (queue:listen)
            Mail::to($emails)->send(new NotificacionEventoAlumno(
                $nombreCompleto,
                "Calendario Oficial de Exámenes - {$ciclo}",
                $detalle
            ));

            $enviados++;
        }

        return response()->json([
            'success' => true,
            'message' => "¡Operación exitosa! Se han encolado las notificaciones para {$enviados} alumnos de este bloque."
        ]);
    }
}