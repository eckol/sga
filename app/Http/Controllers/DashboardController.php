<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Models\Ciclo;
use App\Models\GradoCurso;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Usuarios con rol 6 (Responsables) van al portal
        if (auth()->user()->role_id === 6) {
            return redirect()->route('portal_responsables.index');
        }

        // Años lectivos disponibles en inscripciones
        $anios = Inscripcion::distinct()->orderBy('anio_lectivo', 'desc')->pluck('anio_lectivo');

        // Año seleccionado (por defecto el año actual del sistema; si no existe en BD, el más reciente)
        $anioActual = (int) date('Y');
        $defaultAnio = $anios->contains($anioActual) ? $anioActual : $anios->first();
        $anioSeleccionado = $request->input('anio', $defaultAnio);

        // Alumnos matriculados por ciclo para el año seleccionado
        $ciclos = Ciclo::all();

        $statsPorCiclo = $ciclos->map(function ($ciclo) use ($anioSeleccionado) {
            // Grados que pertenecen a este ciclo
            $gradoIds = GradoCurso::where('ciclo_id', $ciclo->id)->pluck('id');

            // Total de inscripciones en esos grados para el año
            $total = Inscripcion::where('anio_lectivo', $anioSeleccionado)
                ->whereIn('grado_curso_id', $gradoIds)
                ->where('estado', 'Matriculado')
                ->count();

            return [
                'ciclo' => $ciclo->ciclo,
                'total' => $total,
            ];
        })->filter(fn($s) => $s['total'] > 0)->values();

        $totalAlumnos = $statsPorCiclo->sum('total');

        return view('dashboard', compact('anios', 'anioSeleccionado', 'statsPorCiclo', 'totalAlumnos'));
    }
}