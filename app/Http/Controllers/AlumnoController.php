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
    { /* Lógica similar a GC */
    }
    public function update(Request $request, $id)
    { /* Lógica similar a GC */
    }
    public function destroy($id)
    {
        Alumno::destroy($id);
        return back();
    }
}