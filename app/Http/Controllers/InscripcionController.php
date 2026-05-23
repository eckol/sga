<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Models\Alumno;
use App\Models\GradoCurso;
use App\Models\Arancel;
use Illuminate\Http\Request;

class InscripcionController extends Controller
{
    public function index()
    {
        $inscripciones = Inscripcion::with(['alumno', 'grado'])->orderBy('fecha', 'desc')->get();
        $grados = GradoCurso::orderBy('gradocurso')->get();

        // Generamos los años para el select (actual y el que viene)
        $anio_actual = date('Y');
        $anios = [$anio_actual, $anio_actual + 1];

        return view('inscripciones.index', compact('inscripciones', 'grados', 'anios'));
    }

    public function store(Request $request)
    {
        $alumno = Alumno::where('cid', $request->alumno_cid)->first();
        $grado = GradoCurso::find($request->grado_curso_id);

        // Buscamos el arancel exacto para ese año y el ciclo del grado
        $arancel = Arancel::where('anio_lect', $request->anio_lectivo)
            ->where('ciclo_id', $grado->ciclo_id)
            ->first();

        if (!$arancel) {
            return back()->with('error', 'No se encontraron aranceles definidos para el ciclo y año seleccionados.');
        }

        $data = $request->all();
        $data['monto_matricula'] = $arancel->monto_matricula;
        $data['monto_anualidad'] = $arancel->monto_anualidad;

        $firmante_nombre = 'No especificado';
        if ($request->firmante_rol == 'Madre' && $alumno->cid_madre) {
            $persona = \App\Models\Madre::where('cid', $alumno->cid_madre)->first();
            $firmante_nombre = $persona ? $persona->nombre : 'No especificado';
        } elseif ($request->firmante_rol == 'Padre' && $alumno->cid_padre) {
            $persona = \App\Models\Padre::where('cid', $alumno->cid_padre)->first();
            $firmante_nombre = $persona ? $persona->nombre : 'No especificado';
        } elseif ($request->firmante_rol == 'Encargado' && $alumno->cid_encargado) {
            $persona = \App\Models\Encargado::where('cid', $alumno->cid_encargado)->first();
            $firmante_nombre = $persona ? $persona->nombre : 'No especificado';
        }
        $data['firmante_nombre'] = $firmante_nombre;

        Inscripcion::create($data);

        return back()->with('success', 'Inscripción registrada');
    }

    public function update(Request $request, $id)
    {
        $inscripcion = Inscripcion::findOrFail($id);
        $inscripcion->update($request->all());
        return back()->with('success', 'Inscripción actualizada correctamente.');
    }

    public function destroy($id)
    {
        $inscripcion = Inscripcion::findOrFail($id);
        $alumno_cid = $inscripcion->alumno_cid;
        $inscripcion->delete();

        // Verificar si existen otras inscripciones activas
        $otras = Inscripcion::where('alumno_cid', $alumno_cid)->where('estado', 'Matriculado')->count();
        if ($otras == 0) {
            // No need to update matriculado, it has been removed from the alumnos table.
        }

        return back()->with('success', 'Inscripción eliminada correctamente.');
    }
}