<?php

namespace App\Http\Controllers\Academica;

use App\Http\Controllers\Controller;
use App\Models\EntrevistaAlumno;
use App\Models\EntrevistaResponsable;
use App\Models\Alumno;
use App\Models\Colaborador;
use App\Models\Inscripcion;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EntrevistaController extends Controller
{
    /**
     * Retorna únicamente los alumnos matriculados del año en curso
     */
    private function getAlumnosMatriculados()
    {
        $anioActual = date('Y');

        return Alumno::whereHas('inscripciones', function ($query) use ($anioActual) {
            $query->where('anio_lectivo', $anioActual)
                ->where('estado', 'Matriculado');
        })->orderBy('apellidos')->get();
    }

    public function index(): View
    {
        $entrevistasAlumnos = EntrevistaAlumno::with(['alumno', 'entrevistador'])->latest()->get();
        $entrevistasResponsables = EntrevistaResponsable::with(['alumno', 'entrevistador', 'testigos'])->latest()->get();

        // Data para los formularios de creacion
        $alumnos = $this->getAlumnosMatriculados();
        $colaboradores = Colaborador::orderBy('apellidos')->get();

        return view('academica.entrevistas.index', compact(
            'entrevistasAlumnos',
            'entrevistasResponsables',
            'alumnos',
            'colaboradores'
        ));
    }

    public function storeAlumno(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'fecha' => 'required|date',
            'alumno_id' => 'required|exists:alumnos,id',
            'colaborador_id' => 'required|exists:colaboradores,id',
            'motivo' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        EntrevistaAlumno::create($data);

        return redirect()->back()->with('success', 'Entrevista de Alumno registrada exitosamente.');
    }

    public function storeResponsable(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'fecha' => 'required|date',
            'alumno_id' => 'required|exists:alumnos,id',
            'colaborador_id' => 'required|exists:colaboradores,id',
            'motivo' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
            'testigos' => 'nullable|array',
            'testigos.*' => 'exists:colaboradores,id',
        ]);

        $alumno = Alumno::findOrFail($data['alumno_id']);

        $entrevista = EntrevistaResponsable::create([
            'fecha' => $data['fecha'],
            'alumno_id' => $data['alumno_id'],
            'colaborador_id' => $data['colaborador_id'],
            'motivo' => $data['motivo'],
            'observaciones' => $data['observaciones'],
            'madre_cid' => $alumno->cid_madre,
            'padre_cid' => $alumno->cid_padre,
            'encargado_cid' => $alumno->cid_encargado,
            'parentesco_id' => $alumno->parentesco_id,
        ]);

        if (!empty($request->input('testigos'))) {
            $entrevista->testigos()->sync($request->input('testigos'));
        }

        return redirect()->back()->with('success', 'Entrevista de Responsables registrada con exito.');
    }

    // ── UPDATE ─────────────────────────────────────────────────────────────

    public function updateAlumno(Request $request, int $id): RedirectResponse
    {
        $entrevista = EntrevistaAlumno::findOrFail($id);

        $data = $request->validate([
            'fecha' => 'required|date',
            'colaborador_id' => 'required|exists:colaboradores,id',
            'motivo' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        $entrevista->update($data);

        return redirect()->back()->with('success', 'Entrevista de Alumno actualizada correctamente.');
    }

    public function updateResponsable(Request $request, int $id): RedirectResponse
    {
        $entrevista = EntrevistaResponsable::findOrFail($id);

        $data = $request->validate([
            'fecha' => 'required|date',
            'colaborador_id' => 'required|exists:colaboradores,id',
            'motivo' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
            'testigos' => 'nullable|array',
            'testigos.*' => 'exists:colaboradores,id',
        ]);

        $entrevista->update([
            'fecha' => $data['fecha'],
            'colaborador_id' => $data['colaborador_id'],
            'motivo' => $data['motivo'],
            'observaciones' => $data['observaciones'] ?? null,
        ]);

        $entrevista->testigos()->sync($request->input('testigos', []));

        return redirect()->back()->with('success', 'Acta de Responsables actualizada correctamente.');
    }

    // ── DESTROY ────────────────────────────────────────────────────────────

    public function destroyAlumno(int $id): RedirectResponse
    {
        EntrevistaAlumno::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Entrevista eliminada correctamente.');
    }

    public function destroyResponsable(int $id): RedirectResponse
    {
        $entrevista = EntrevistaResponsable::findOrFail($id);
        $entrevista->testigos()->detach();
        $entrevista->delete();
        return redirect()->back()->with('success', 'Acta de Responsables eliminada correctamente.');
    }

    // ── AJAX ───────────────────────────────────────────────────────────────

    public function getEntrevistasPorAlumno(int $alumnoId)
    {
        $alumno = Alumno::with([
            'entrevistasAlumnos.entrevistador',
            'entrevistasResponsables.entrevistador',
            'entrevistasResponsables.testigos'
        ])->findOrFail($alumnoId);

        $entrevistas = collect();

        foreach ($alumno->entrevistasAlumnos as $ea) {
            $entrevistas->push([
                'id' => $ea->id,
                'tipo' => 'Alumno',
                'fecha' => $ea->fecha->format('d/m/Y'),
                'fecha_raw' => $ea->fecha->format('Y-m-d'),
                'entrevistador' => $ea->entrevistador
                    ? $ea->entrevistador->apellidos . ', ' . $ea->entrevistador->nombres
                    : '-',
                'colaborador_id' => $ea->colaborador_id,
                'motivo' => $ea->motivo,
                'obs' => $ea->observaciones,
                'testigos' => [],           // sin testigos en entrevista de alumno
                'testigos_nombres' => [],
            ]);
        }

        foreach ($alumno->entrevistasResponsables as $er) {
            $entrevistas->push([
                'id' => $er->id,
                'tipo' => 'Responsable',
                'fecha' => $er->fecha->format('d/m/Y'),
                'fecha_raw' => $er->fecha->format('Y-m-d'),
                'entrevistador' => $er->entrevistador
                    ? $er->entrevistador->apellidos . ', ' . $er->entrevistador->nombres
                    : '-',
                'colaborador_id' => $er->colaborador_id,
                'motivo' => $er->motivo,
                'obs' => $er->observaciones,
                'testigos' => $er->testigos->pluck('id'),
                // ← NUEVO: nombres completos para mostrar en el Portal
                'testigos_nombres' => $er->testigos->map(fn($t) => $t->apellidos . ', ' . $t->nombres)->values(),
            ]);
        }

        return response()->json($entrevistas->sortByDesc('fecha_raw')->values());
    }
}