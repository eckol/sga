<?php

namespace App\Http\Controllers;

use App\Models\CalendarioExamen;
use App\Models\Asignatura;
use App\Models\GradoCurso;
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
}