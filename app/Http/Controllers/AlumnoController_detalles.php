<?php

// ============================================================
//  AlumnoController.php  —  método detalles()
//  Ruta: GET  academica/alumnos/{id}/detalles
//
//  Agregar en routes/web.php (grupo 'academica'):
//  Route::get('alumnos/{id}/detalles', [AlumnoController::class, 'detalles']);
//  Route::get('faltas/{id}/detalle',   [AlumnoController::class, 'detalleFalta']);
// ============================================================

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Models\Alumno;
use App\Models\Falta;       // ajustar al namespace real del modelo
use App\Models\Madre;
use App\Models\Padre;
use App\Models\Encargado;

class AlumnoController extends Controller
{
    // ----------------------------------------------------------
    // GET  academica/alumnos/{id}/detalles
    // Devuelve: responsables, historial de inscripciones y faltas
    // ----------------------------------------------------------
    public function detalles(int $id): JsonResponse
    {
        $alumno = Alumno::with([
            'inscripciones.grado',
            'inscripciones.firmante',   // si existe relación; quitar si no
            'faltas.grado',             // relación faltas → grado/curso
            'faltas.asignatura',        // relación faltas → asignatura
        ])->findOrFail($id);

        // ---- Responsables ----------------------------------------
        $madre = $alumno->cid_madre
            ? Madre::where('cid', $alumno->cid_madre)->first(['nombre'])
            : null;

        $padre = $alumno->cid_padre
            ? Padre::where('cid', $alumno->cid_padre)->first(['nombre'])
            : null;

        $encargado = $alumno->cid_encargado
            ? Encargado::where('cid', $alumno->cid_encargado)->first(['nombre'])
            : null;

        // ---- Inscripciones (ordenadas más reciente primero) -------
        $inscripciones = $alumno->inscripciones
            ->sortByDesc('fecha')
            ->values()
            ->map(fn($ins) => [
                'id' => $ins->id,
                'fecha' => optional($ins->fecha)->format('d/m/Y') ?? $ins->fecha,
                'anio_lectivo' => $ins->anio_lectivo,
                'grado_curso' => $ins->grado->gradocurso ?? '—',
                'firmante_nombre' => $ins->firmante->nombre ?? '',   // quitar si no hay relación
                'firmante_rol' => $ins->firmante_rol ?? '',
                'estado' => $ins->estado,
            ]);

        // ---- Faltas (ordenadas más reciente primero) --------------
        $faltas = $alumno->faltas
            ->sortByDesc('fecha')
            ->values()
            ->map(fn($f) => [
                'id' => $f->id,
                'fecha' => optional($f->fecha)->format('d/m/Y') ?? $f->fecha,
                'falta' => $f->falta,                          // descripción corta de la falta
                'grado_curso' => $f->grado->gradocurso ?? '—',      // ajustar al nombre de columna/relación real
                'asignatura' => $f->asignatura->asignatura ?? $f->asignatura ?? '—',
            ]);

        return response()->json([
            'madre' => $madre,
            'padre' => $padre,
            'encargado' => $encargado,
            'inscripciones' => $inscripciones,
            'faltas' => $faltas,
        ]);
    }

    // ----------------------------------------------------------
    // GET  academica/faltas/{id}/detalle
    // Devuelve todos los campos de una falta individual
    // (para el modal "modalDetalleFalta" al hacer clic en la lupa)
    // ----------------------------------------------------------
    public function detalleFalta(int $id): JsonResponse
    {
        $falta = Falta::with(['grado', 'asignatura'])->findOrFail($id);

        return response()->json([
            'id' => $falta->id,
            'fecha' => optional($falta->fecha)->format('d/m/Y') ?? $falta->fecha,
            'falta' => $falta->falta,
            'grado_curso' => $falta->grado->gradocurso ?? '—',
            'asignatura' => $falta->asignatura->asignatura ?? $falta->asignatura ?? '—',
            'tipo' => $falta->tipo ?? null,   // ej: "Leve", "Grave"
            'sancion' => $falta->sancion ?? null,
            'observaciones' => $falta->observaciones ?? null,
        ]);
    }
}
