<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Falta;
use App\Models\Alumno;
use App\Models\Asignatura;
use App\Models\AsignaturaColaborador;
use App\Models\GradoCurso;
use App\Models\IndicadoresFaltas;
use Illuminate\Http\Request;

class FaltaController extends Controller
{
    public function index(Request $request)
    {
        // Filtros
        $gradoId = $request->get('grado_id');
        $fechaDesde = $request->get('fecha_desde');
        $fechaHasta = $request->get('fecha_hasta');

        $grados = GradoCurso::whereBetween('id', [19, 30])->orderBy('id')->get();

        $query = Falta::with(['alumno', 'asignatura', 'gradoCurso', 'indicadorFalta'])
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc');

        if ($gradoId) {
            $query->where('grado_curso_id', $gradoId);
        }
        if ($fechaDesde) {
            $query->where('fecha', '>=', $fechaDesde);
        }
        if ($fechaHasta) {
            $query->where('fecha', '<=', $fechaHasta);
        }

        $faltas = $query->get();

        // Enriquecer cada falta con el colaborador (sin columna en DB)
        $asigColabMap = AsignaturaColaborador::with('colaborador')->get()
            ->keyBy(fn($r) => $r->asignatura_id . '-' . $r->grado_curso_id);

        foreach ($faltas as $falta) {
            $key = $falta->asignatura_id . '-' . $falta->grado_curso_id;
            $falta->colaborador_nombre = isset($asigColabMap[$key])
                ? $asigColabMap[$key]->colaborador->apellidos . ', ' . $asigColabMap[$key]->colaborador->nombres
                : '—';
        }

        // Datos para los selects del modal
        $indicadores = IndicadoresFaltas::orderBy('indicador_falta')->get();
        $asignaturas = Asignatura::orderBy('asignatura')->get();

        return view('academica.faltas.index', compact(
            'faltas',
            'grados',
            'indicadores',
            'asignaturas',
            'gradoId',
            'fechaDesde',
            'fechaHasta'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'grado_curso_id' => 'required|exists:grados_cursos,id',
            'alumno_id' => 'required|exists:alumnos,id',
            'asignatura_id' => 'required|exists:asignaturas,id',
            'indicador_falta_id' => 'required|exists:indicadores_faltas,id',
        ]);

        Falta::create($request->only([
            'fecha',
            'grado_curso_id',
            'alumno_id',
            'asignatura_id',
            'indicador_falta_id'
        ]));

        return redirect()->back()->with('success', 'Falta registrada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fecha' => 'required|date',
            'grado_curso_id' => 'required|exists:grados_cursos,id',
            'alumno_id' => 'required|exists:alumnos,id',
            'asignatura_id' => 'required|exists:asignaturas,id',
            'indicador_falta_id' => 'required|exists:indicadores_faltas,id',
        ]);

        $falta = Falta::findOrFail($id);
        $falta->update($request->only([
            'fecha',
            'grado_curso_id',
            'alumno_id',
            'asignatura_id',
            'indicador_falta_id'
        ]));

        return redirect()->back()->with('success', 'Falta actualizada correctamente.');
    }

    public function destroy($id)
    {
        Falta::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Falta eliminada correctamente.');
    }

    /**
     * AJAX: devuelve alumnos del grado seleccionado
     */
    public function alumnosPorGrado($gradoId)
    {
        $alumnos = Alumno::whereHas('inscripciones', function ($q) use ($gradoId) {
            $q->where('grado_curso_id', $gradoId);
        })
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get(['id', 'apellidos', 'nombres']);

        return response()->json($alumnos);
    }

    /**
     * AJAX: devuelve asignaturas del grado seleccionado (con docente)
     */
    public function asignaturasPorGrado($gradoId)
    {
        $registros = AsignaturaColaborador::with(['asignatura', 'colaborador'])
            ->where('grado_curso_id', $gradoId)
            ->get();

        $data = $registros->map(fn($r) => [
            'asignatura_id' => $r->asignatura_id,
            'asignatura' => $r->asignatura->asignatura,
            'docente' => $r->colaborador
                ? $r->colaborador->apellidos . ', ' . $r->colaborador->nombres
                : '—',
        ]);

        return response()->json($data);
    }
}
