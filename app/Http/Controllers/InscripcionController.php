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
        $inscripciones = Inscripcion::with(['alumno.madre', 'alumno.padre', 'alumno.encargado', 'grado'])->orderBy('fecha', 'desc')->get();
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

        $arancel = Arancel::where('anio_lect', $request->anio_lectivo)
            ->where('ciclo_id', $grado->ciclo_id)
            ->first();

        $data = $request->all();

        // Si no hay arancel y no se enviaron montos manuales, error.
        if (!$arancel && (empty($data['monto_matricula']) || empty($data['monto_anualidad']))) {
            return back()->with('error', 'No se encontraron aranceles definidos para el ciclo y año seleccionados y no se ingresaron montos manuales.');
        }

        // Prioridad: 1. Request (si > 0), 2. Arancel
        if (empty($data['monto_matricula']) || $data['monto_matricula'] == 0) {
            $data['monto_matricula'] = $arancel ? $arancel->monto_matricula : 0;
        }
        if (empty($data['monto_anualidad']) || $data['monto_anualidad'] == 0) {
            $data['monto_anualidad'] = $arancel ? $arancel->monto_anualidad : 0;
        }

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

    public function toggleAlumnoNuevo(Request $request, $id)
    {
        $inscripcion = Inscripcion::findOrFail($id);
        $inscripcion->alumno_nuevo = $request->input('alumno_nuevo');
        $inscripcion->save();
        return response()->json(['success' => true, 'alumno_nuevo' => $inscripcion->alumno_nuevo]);
    }
}