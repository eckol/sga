<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\Asignatura;
use App\Models\GradoCurso;
use App\Models\Hora;
use App\Models\Ciclo;
use App\Models\Colaborador;
use App\Models\AsignaturaColaborador;
use Illuminate\Http\Request;

class DocenteHoraController extends Controller
{
    public function index()
    {
        $colaboradores = Colaborador::orderBy('apellidos')->orderBy('nombres')->get();
        $horas = Hora::orderBy('hora_inicio')->get();
        $gradosCursos = GradoCurso::orderBy('gradocurso')->get();

        return view('academica.docentes_hora.index', compact('colaboradores', 'horas', 'gradosCursos'));
    }

    public function getData(Request $request)
    {
        $colaboradorId = $request->colaborador_id;

        if (!$colaboradorId) {
            return response()->json(['success' => false, 'message' => 'Seleccione un docente.']);
        }

        // 1. Obtener las asignaturas que imparte el docente
        $asignaturasColaborador = AsignaturaColaborador::with('asignatura')
            ->where('colaborador_id', $colaboradorId)
            ->get();

        $asignaturasNames = $asignaturasColaborador->map(function ($ac) {
            return $ac->asignatura->abreviacion ?? $ac->asignatura->asignatura;
        })->unique()->implode(', ');

        $asignaturaIds = $asignaturasColaborador->pluck('asignatura_id')->unique();
        $gradoIds = $asignaturasColaborador->pluck('grado_curso_id')->unique();

        // 2. Obtener los horarios para esas combinaciones de docente/asignatura/grado
        // El Horario vincula asignatura_id y grado_curso_id.
        // Pero necesitamos filtrar por ciclo si el grado tiene ciclo? 
        // Normalmente GradoCurso tiene ciclo_id.

        $horarios = Horario::with(['gradoCurso', 'asignatura'])
            ->whereIn('asignatura_id', $asignaturaIds)
            ->whereIn('grado_curso_id', $gradoIds)
            ->get()
            ->filter(function ($h) use ($colaboradorId) {
                // Verificar que para ese grado y esa asignatura el docente sea el asignado
                return AsignaturaColaborador::where('asignatura_id', $h->asignatura_id)
                    ->where('grado_curso_id', $h->grado_curso_id)
                    ->where('colaborador_id', $colaboradorId)
                    ->exists();
            });

        // Indexar por día y hora_id
        $mapa = [];
        foreach ($horarios as $h) {
            $mapa[$h->dia][$h->hora_id][] = [
                'id' => $h->id,
                'grado_curso' => $h->gradoCurso->gradocurso ?? '?',
                'grado_curso_id' => $h->grado_curso_id,
                'asignatura' => $h->asignatura->abreviacion ?? $h->asignatura->asignatura ?? '?',
            ];
        }

        return response()->json([
            'success' => true,
            'asignaturas' => $asignaturasNames,
            'mapa' => $mapa
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:horarios,id',
            'grado_curso_id' => 'nullable|exists:gradoscursos,id',
        ]);

        $horario = Horario::findOrFail($request->id);
        $horario->update([
            'grado_curso_id' => $request->grado_curso_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Horario actualizado correctamente.'
        ]);
    }
}
