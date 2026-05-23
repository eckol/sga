<?php

namespace App\Http\Controllers\Academica;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AlumnoGradoController extends Controller
{
    public function index(Request $request)
    {
        $grados = \App\Models\GradoCurso::orderBy('id')->get();
        $selectedGradoId = $request->input('grado_id', $grados->first() ? $grados->first()->id : null);

        $alumnos = collect();
        $selectedGrado = null;

        if ($selectedGradoId) {
            $selectedGrado = \App\Models\GradoCurso::find($selectedGradoId);
            $anio_actual = date('Y');

            // Get inscripciones for the selected grado, current year, and estado = Matriculado
            $inscripciones = \App\Models\Inscripcion::with(['alumno', 'alumno.nacionalidad'])
                ->where('grado_curso_id', $selectedGradoId)
                ->where('anio_lectivo', $anio_actual)
                ->where('estado', 'Matriculado')
                ->get();

            $alumnos = $inscripciones->map(function ($inscripcion) {
                return $inscripcion->alumno;
            })->filter(function ($alumno) {
                return current($alumno) !== false && $alumno !== null;
            })->values();
        }

        $ciudades = \App\Models\Ciudad::orderBy('ciudad')->get();
        $nacionalidades = \App\Models\Nacionalidad::all();
        $sexos = \App\Models\Sexo::all();
        $vivecon = \App\Models\ViveCon::all();
        $parentescos = \App\Models\Parentesco::all();
        $anio_actual = date('Y');
        $anios = [$anio_actual, $anio_actual + 1];

        return view('academica.alumnos_grado', compact(
            'grados',
            'selectedGradoId',
            'selectedGrado',
            'alumnos',
            'ciudades',
            'nacionalidades',
            'sexos',
            'vivecon',
            'parentescos',
            'anios'
        ));
    }

    public function toggleEstado(Request $request, $id)
    {
        $alumno = \App\Models\Alumno::findOrFail($id);
        if ($request->has('activo')) {
            $alumno->activo = $request->input('activo') == 'true' ? 'Sí' : 'No';
        }
        $alumno->save();
        return response()->json(['success' => true]);
    }
}
