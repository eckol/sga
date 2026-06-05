<?php

namespace App\Http\Controllers\Academica;

use App\Http\Controllers\Controller;
use App\Models\RegistroAnecdotico;
use App\Models\GradoCurso;
use App\Models\AsignaturaColaborador;
use Illuminate\Http\Request;

class RegistroAnecdoticoController extends Controller
{
    public function index(Request $request)
    {
        $gradoId    = $request->get('grado_id');
        $fechaDesde = $request->get('fecha_desde');
        $fechaHasta = $request->get('fecha_hasta');

        $grados = GradoCurso::whereBetween('id', [19, 30])->orderBy('id')->get();

        $query = RegistroAnecdotico::with(['alumno', 'asignatura', 'gradoCurso'])
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

        $registros = $query->get();

        // Enriquecer con nombre del colaborador (sin columna en DB)
        $asigColabMap = AsignaturaColaborador::with('colaborador')->get()
            ->keyBy(fn($r) => $r->asignatura_id . '-' . $r->grado_curso_id);

        foreach ($registros as $reg) {
            $key = $reg->asignatura_id . '-' . $reg->grado_curso_id;
            $reg->colaborador_nombre = isset($asigColabMap[$key])
                ? $asigColabMap[$key]->colaborador->apellidos . ', ' . $asigColabMap[$key]->colaborador->nombres
                : '—';
        }

        return view('academica.registros_anecdoticos.index', compact(
            'registros',
            'grados',
            'gradoId',
            'fechaDesde',
            'fechaHasta'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha'          => 'required|date',
            'alumno_id'      => 'required|exists:alumnos,id',
            'asignatura_id'  => 'required|exists:asignaturas,id',
            'grado_curso_id' => 'required|exists:grados_cursos,id',
            'detalle'        => 'required|string',
        ]);

        RegistroAnecdotico::create($validated);

        return back()->with('success', 'Registro guardado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'fecha'          => 'required|date',
            'alumno_id'      => 'required|exists:alumnos,id',
            'asignatura_id'  => 'required|exists:asignaturas,id',
            'grado_curso_id' => 'required|exists:grados_cursos,id',
            'detalle'        => 'required|string',
        ]);

        RegistroAnecdotico::findOrFail($id)->update($validated);

        return back()->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy($id)
    {
        RegistroAnecdotico::findOrFail($id)->delete();

        return back()->with('success', 'Registro eliminado correctamente.');
    }
}
