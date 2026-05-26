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

        // Data para los formularios de creación
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
            'testigos' => 'nullable|array', // Array de IDs de colaboradores
            'testigos.*' => 'exists:colaboradores,id',
        ]);

        // Recuperamos el alumno para autofiltrar e hidratar sus responsables en la BD
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

        // Sincronizamos los testigos en la tabla intermedia (Many-to-Many de Laravel)
        if (!empty($request->input('testigos'))) {
            $entrevista->testigos()->sync($request->input('testigos'));
        }

        return redirect()->back()->with('success', 'Entrevista de Responsables registrada con éxito.');
    }
}