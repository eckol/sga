<?php

namespace App\Http\Controllers\Academica;

use App\Http\Controllers\Controller;
use App\Models\EntrevistaAlumno;
use App\Models\EntrevistaResponsable;
use App\Models\Alumno;
use App\Models\Colaborador;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class EntrevistaController extends Controller
{
    // ... (index y store se mantienen igual)

    /**
     * AJAX: Obtiene entrevistas de un alumno específico para el Tab del Modal
     */
    public function getEntrevistasPorAlumno($alumnoId): JsonResponse
    {
        $alumno = Alumno::findOrFail($alumnoId);

        $directas = EntrevistaAlumno::with('entrevistador')
            ->where('alumno_id', $alumnoId)
            ->get()
            ->map(fn($e) => [
                'tipo' => 'Alumno',
                'fecha' => $e->fecha->format('d/m/Y'),
                'entrevistador' => $e->entrevistador->apellidos . ', ' . $e->entrevistador->nombres,
                'motivo' => $e->motivo,
                'obs' => $e->observaciones
            ]);

        $responsables = EntrevistaResponsable::with(['entrevistador', 'testigos'])
            ->where('alumno_id', $alumnoId)
            ->get()
            ->map(fn($e) => [
                'tipo' => 'Responsables',
                'fecha' => $e->fecha->format('d/m/Y'),
                'entrevistador' => $e->entrevistador->apellidos . ', ' . $e->entrevistador->nombres,
                'motivo' => $e->motivo,
                'obs' => $e->observaciones,
                'testigos' => $e->testigos->pluck('nombres')->implode(', ')
            ]);

        return response()->json($directas->concat($responsables)->sortByDesc('fecha')->values());
    }

    public function updateAlumno(Request $request, $id): RedirectResponse
    {
        $entrevista = EntrevistaAlumno::findOrFail($id);
        $entrevista->update($request->all());
        return back()->with('success', 'Entrevista de alumno actualizada.');
    }

    public function destroyAlumno($id): RedirectResponse
    {
        EntrevistaAlumno::destroy($id);
        return back()->with('success', 'Entrevista eliminada.');
    }

    public function updateResponsable(Request $request, $id): RedirectResponse
    {
        $entrevista = EntrevistaResponsable::findOrFail($id);
        $entrevista->update($request->except('testigos'));
        if ($request->has('testigos')) {
            $entrevista->testigos()->sync($request->testigos);
        }
        return back()->with('success', 'Acta de entrevista actualizada.');
    }

    public function destroyResponsable($id): RedirectResponse
    {
        EntrevistaResponsable::destroy($id);
        return back()->with('success', 'Acta eliminada.');
    }
}