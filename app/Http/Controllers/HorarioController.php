<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\Asignatura;
use App\Models\GradoCurso;
use App\Models\Hora;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    public function index()
    {
        $asignaturas = Asignatura::orderBy('asignatura', 'asc')->get();
        $horas       = Hora::orderBy('hora_inicio', 'asc')->get();

        // Grados: IDs 19 al 24
        $grados = GradoCurso::whereIn('id', [19, 20, 21, 22, 23, 24])
            ->orderBy('id', 'asc')->get();

        // Cursos: IDs 25 al 30
        $cursos = GradoCurso::whereIn('id', [25, 26, 27, 28, 29, 30])
            ->orderBy('id', 'asc')->get();

        // Todos los horarios de grados y cursos, indexados para acceso rápido
        // Clave: "dia-hora_id-grado_curso_id" => horario
        $horarios = Horario::whereIn('grado_curso_id', array_merge(
            [19, 20, 21, 22, 23, 24],
            [25, 26, 27, 28, 29, 30]
        ))->get()->keyBy(function ($h) {
            return "{$h->dia}-{$h->hora_id}-{$h->grado_curso_id}";
        });

        $dias = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
        ];

        return view('academica.horarios.index', compact(
            'asignaturas', 'horas', 'grados', 'cursos', 'horarios', 'dias'
        ));
    }

    public function update(Request $request, $id)
    {
        $horario = Horario::findOrFail($id);
        $horario->update([
            'asignatura_id' => $request->asignatura_id ?: null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Horario actualizado correctamente.',
        ]);
    }
}
