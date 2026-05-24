<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Ciudad;
use App\Models\Nacionalidad;
use App\Models\Sexo;
use App\Models\ViveCon;
use App\Models\Parentesco;
use App\Models\Falta;
use App\Models\Madre;
use App\Models\Padre;
use App\Models\Encargado;
use App\Models\GradoCurso;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AlumnoController extends Controller
{
    public function index()
    {
        $alumnos = Alumno::with([
            'ciudad',
            'sexo',
            'nacionalidad',
            'inscripciones' => function ($q) {
                $q->latest()->limit(1);
            },
            'inscripciones.grado'
        ])->get();

        $ciudades = Ciudad::orderBy('ciudad')->get();
        $nacionalidades = Nacionalidad::all();
        $sexos = Sexo::all();
        $vivecon = ViveCon::all();
        $parentescos = Parentesco::all();
        $grados = \App\Models\GradoCurso::orderBy('id')->get();
        $anio_actual = date('Y');
        $anios = [$anio_actual, $anio_actual + 1];

        return view('rrhh.alumnos.index', compact(
            'alumnos',
            'ciudades',
            'nacionalidades',
            'sexos',
            'vivecon',
            'parentescos',
            'grados',
            'anios'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->except(['_token', '_method', 'foto']);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/alumnos'), $filename);
            $data['foto'] = $filename;
        }

        Alumno::create($data);
        return back()->with('success', 'Alumno registrado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $alumno = Alumno::findOrFail($id);
        $data = $request->except(['_token', '_method', 'foto']);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/alumnos'), $filename);
            $data['foto'] = $filename;
        }

        $alumno->update($data);
        return back()->with('success', 'Alumno actualizado correctamente.');
    }

    public function destroy($id)
    {
        Alumno::destroy($id);
        return back()->with('success', 'Alumno eliminado correctamente.');
    }

    // ----------------------------------------------------------------
    // GET  academica/alumnos/{id}/detalles
    // AJAX: responsables + historial de inscripciones + faltas
    // ----------------------------------------------------------------
    public function detalles(int $id): JsonResponse
    {
        $alumno = Alumno::with(['madre', 'padre', 'encargado', 'inscripciones'])->findOrFail($id);

        $madre = Madre::where('cid', $alumno->cid_madre)->first();
        $padre = Padre::where('cid', $alumno->cid_padre)->first();
        $encargado = Encargado::where('cid', $alumno->cid_encargado)->first();

        $inscripciones = $alumno->inscripciones->map(fn($ins) => [
            'id' => $ins->id,
            'fecha' => $ins->fecha ? \Carbon\Carbon::parse($ins->fecha)->format('d/m/Y') : '—',
            'anio_lectivo' => $ins->anio_lectivo,
            'grado_curso' => $ins->grado->gradocurso ?? '—',
            'firmante_nombre' => $ins->firmante_nombre,
            'firmante_rol' => $ins->firmante_rol,
            'estado' => $ins->estado,
        ]);

        // Mapeo enriquecido con IDs nativos para el funcionamiento del modal de faltas
        $faltas = $alumno->faltas->map(fn($f) => [
            'id' => $f->id,
            'fecha' => $f->fecha ? \Carbon\Carbon::parse($f->fecha)->format('d/m/Y') : '—',
            'fecha_raw' => $f->fecha ? \Carbon\Carbon::parse($f->fecha)->format('Y-m-d') : '',
            'falta' => $f->indicadorFalta->indicador_falta ?? '—',
            'indicador_falta_id' => $f->indicador_falta_id,
            'grado_curso' => $f->gradoCurso->gradocurso ?? '—',
            'grado_curso_id' => $f->grado_curso_id,
            'asignatura' => $f->asignatura->asignatura ?? '—',
            'asignatura_id' => $f->asignatura_id,
            'alumno_id' => $f->alumno_id,
        ]);

        return response()->json([
            'madre' => $madre,
            'padre' => $padre,
            'encargado' => $encargado,
            'inscripciones' => $inscripciones,
            'faltas' => $faltas,
        ]);
    }

    // ----------------------------------------------------------------
    // GET  academica/faltas/{id}/detalle
    // AJAX: detalle completo de una falta individual (modal lupa)
    // ----------------------------------------------------------------
    public function detalleFalta(int $id): JsonResponse
    {
        $falta = Falta::with(['gradoCurso', 'asignatura', 'indicadorFalta', 'alumno'])
            ->findOrFail($id);

        return response()->json([
            'id' => $falta->id,
            'fecha' => $falta->fecha
                ? \Carbon\Carbon::parse($falta->fecha)->format('d/m/Y')
                : '—',
            'alumno' => $falta->alumno
                ? $falta->alumno->apellidos . ', ' . $falta->alumno->nombres
                : '—',
            'grado_curso' => $falta->gradoCurso->gradocurso ?? '—',
            'asignatura' => $falta->asignatura->asignatura ?? '—',
            'indicador' => $falta->indicadorFalta->indicador_falta ?? '—',
        ]);
    }
}
