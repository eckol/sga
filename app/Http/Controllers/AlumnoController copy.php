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
        $alumnos = Alumno::with(['ciudad', 'sexo', 'nacionalidad'])->get();
        // Datos para los modales
        $ciudades = Ciudad::orderBy('ciudad')->get();
        $nacionalidades = Nacionalidad::all();
        $sexos = Sexo::all();
        $vivecon = ViveCon::all();
        $parentescos = Parentesco::all();

        return view('rrhh.alumnos.index', compact('alumnos', 'ciudades', 'nacionalidades', 'sexos', 'vivecon', 'parentescos'));
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
        return back();
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
        return back();
    }
    public function destroy($id)
    {
        Alumno::destroy($id);
        return back();
    }
}