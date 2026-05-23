<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AsignaturaColaborador;
use App\Models\Asignatura;
use App\Models\Colaborador;
use App\Models\GradoCurso;
use Illuminate\Http\Request;

class AsignaturaColaboradorController extends Controller
{
    public function index()
    {
        $colaboradores = Colaborador::orderBy('apellidos')->orderBy('nombres')->get();

        // Grados: 3er Ciclo EEB (ids 19-24)
        $grados = GradoCurso::whereBetween('id', [19, 24])->orderBy('id')->get();

        // Cursos: Nivel Medio (ids 25-30)
        $cursos = GradoCurso::whereBetween('id', [25, 30])->orderBy('id')->get();

        $idsGrados = $grados->pluck('id');
        $idsCursos = $cursos->pluck('id');
        $todosIds  = $idsGrados->merge($idsCursos);

        // Construir mapa [asignatura_id][grado_curso_id] => AsignaturaColaborador
        $registros = AsignaturaColaborador::with('colaborador')
            ->whereIn('grado_curso_id', $todosIds)
            ->get();

        $mapa = [];
        foreach ($registros as $reg) {
            $mapa[$reg->asignatura_id][$reg->grado_curso_id] = $reg;
        }

        // IDs de asignaturas con al menos un docente asignado en 3er Ciclo
        $idsConGrado = AsignaturaColaborador::whereIn('grado_curso_id', $idsGrados)
            ->pluck('asignatura_id')
            ->unique();

        // IDs de asignaturas con al menos un docente asignado en Nivel Medio
        $idsConCurso = AsignaturaColaborador::whereIn('grado_curso_id', $idsCursos)
            ->pluck('asignatura_id')
            ->unique();

        // Solo cargar asignaturas que aparezcan en alguna de las dos grillas
        $asignaturas = Asignatura::whereIn('id', $idsConGrado->merge($idsConCurso))
            ->orderBy('asignatura')
            ->get();

        // Subconjuntos por grilla (para filtrar filas en la vista)
        $asignaturasGrado = $asignaturas->whereIn('id', $idsConGrado);
        $asignaturasCurso = $asignaturas->whereIn('id', $idsConCurso);

        return view('academica.docentes_asignatura.index', compact(
            'asignaturasGrado',
            'asignaturasCurso',
            'colaboradores',
            'grados',
            'cursos',
            'mapa'
        ));
    }

    public function update(Request $request, $asignatura_id, $grado_curso_id)
    {
        $request->validate([
            'colaborador_id' => 'nullable|exists:colaboradores,id',
        ]);

        $colaboradorId = $request->colaborador_id ?: null;

        if ($colaboradorId) {
            AsignaturaColaborador::updateOrCreate(
                ['asignatura_id' => $asignatura_id, 'grado_curso_id' => $grado_curso_id],
                ['colaborador_id' => $colaboradorId]
            );
        } else {
            AsignaturaColaborador::where('asignatura_id', $asignatura_id)
                ->where('grado_curso_id', $grado_curso_id)
                ->delete();
        }

        return response()->json(['success' => true, 'message' => 'Guardado correctamente.']);
    }
}
