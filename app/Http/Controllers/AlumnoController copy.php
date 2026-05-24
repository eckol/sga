<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Ciudad;
use App\Models\Nacionalidad;
use App\Models\Sexo;
use App\Models\ViveCon;
use App\Models\Parentesco;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    public function index()
    {
        // Traemos al alumno con su última inscripción para saber su grado actual
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

        $grados = \App\Models\GradoCurso::orderBy('id')->get(); // Ordenar por ID es clave para el "siguiente"
        $anio_actual = date('Y');
        $anios = [$anio_actual, $anio_actual + 1];

        return view('rrhh.alumnos.index', compact('alumnos', 'ciudades', 'nacionalidades', 'sexos', 'vivecon', 'parentescos', 'grados', 'anios'));
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
}