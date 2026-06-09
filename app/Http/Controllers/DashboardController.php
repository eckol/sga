<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Models\Ciclo;
use App\Models\GradoCurso;
use App\Models\Asistencia;
use App\Models\Colaborador;
use App\Models\CalendarioExamen;
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

        // Últimos 5 alumnos inscriptos (matriculados)
        $ultimosAlumnos = Inscripcion::with(['alumno', 'grado'])
            ->where('anio_lectivo', $anioSeleccionado)
            ->where('estado', 'Matriculado')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Estadísticas de género (solo matriculados)
        $generosStats = \App\Models\Alumno::whereIn('cid', function ($query) use ($anioSeleccionado) {
            $query->select('alumno_cid')
                ->from('inscripciones')
                ->where('anio_lectivo', $anioSeleccionado)
                ->where('estado', 'Matriculado');
        })
            ->select('sexo_id', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('sexo_id')
            ->get()
            ->pluck('total', 'sexo_id'); // [1 => masc, 2 => fem]

        $countM = $generosStats->get(1, 0); // ID 1 = M
        $countF = $generosStats->get(2, 0); // ID 2 = F

        // --- NUEVOS KPIs ---
        $hoy = date('Y-m-d');

        // 1. % Asistencia Hoy
        $totalAsis = Asistencia::where('fecha', $hoy)->count();
        $presentes = Asistencia::where('fecha', $hoy)->where('estado', 'Presente')->count();
        $porcentajeAsistencia = $totalAsis > 0 ? round(($presentes / $totalAsis) * 100) : 0;

        // 2. Finanzas (Cantidad de alumnos matriculados al día)
        $alumnosAlDiaCount = Inscripcion::where('anio_lectivo', $anioSeleccionado)
            ->where('estado', 'Matriculado')
            ->where('al_dia', 1)
            ->count();

        // 3. Personal
        $totalColaboradores = Colaborador::count();

        // 4. Exámenes del día
        $examenesHoyCount = CalendarioExamen::where('fecha', $hoy)->count();

        return view('dashboard', compact(
            'anios',
            'anioSeleccionado',
            'statsPorCiclo',
            'totalAlumnos',
            'ultimosAlumnos',
            'countM',
            'countF',
            'porcentajeAsistencia',
            'alumnosAlDiaCount',
            'totalColaboradores',
            'examenesHoyCount'
        ));
    }
}