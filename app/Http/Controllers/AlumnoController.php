<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Ciudad;
use App\Models\Nacionalidad;
use App\Models\Sexo;
use App\Models\ViveCon;
use App\Models\Parentesco;
use App\Models\Falta;
use App\Models\Madre;
use App\Models\Padre;
use App\Models\Encargado;
use App\Models\GradoCurso;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AlumnoController extends Controller
{
    public function index()
    {
        $alumnos = Alumno::with([
            'ciudad',
            'sexo',
            'nacionalidad',
            'inscripciones' => function ($q) {
                $q->latest()->limit(1);
            },
            'inscripciones.grado'
        ])->get();

        $ciudades = Ciudad::orderBy('ciudad')->get();
        $nacionalidades = Nacionalidad::all();
        $sexos = Sexo::all();
        $vivecon = ViveCon::all();
        $parentescos = Parentesco::all();
        $grados = \App\Models\GradoCurso::orderBy('id')->get();
        $colaboradores = \App\Models\Colaborador::orderBy('apellidos')->get();
        $anio_actual = date('Y');
        $anios = [$anio_actual, $anio_actual + 1];
        $indicadores = \App\Models\IndicadoresFaltas::orderBy('indicador_falta')->get();

        return view('rrhh.alumnos.index', compact(
            'alumnos',
            'ciudades',
            'nacionalidades',
            'sexos',
            'vivecon',
            'parentescos',
            'grados',
            'colaboradores',
            'anios',
            'indicadores'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->except(['_token', '_method', 'foto']);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/alumnos'), $filename);
            $data['foto'] = $filename;
        }

        Alumno::create($data);
        return back()->with('success', 'Alumno registrado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $alumno = Alumno::findOrFail($id);
        $data = $request->except(['_token', '_method', 'foto']);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/alumnos'), $filename);
            $data['foto'] = $filename;
        }

        $alumno->update($data);
        return back()->with('success', 'Alumno actualizado correctamente.');
    }

    public function destroy($id)
    {
        Alumno::destroy($id);
        return back()->with('success', 'Alumno eliminado correctamente.');
    }

    // ----------------------------------------------------------------
    // GET  academica/alumnos/{id}/detalles
    // AJAX: responsables + historial de inscripciones + faltas + entrevistas
    // ----------------------------------------------------------------
    public function detalles(int $id): JsonResponse
    {
        $alumno = Alumno::with(['madre', 'padre', 'encargado', 'inscripciones', 'entrevistasAlumnos', 'entrevistasResponsables'])->findOrFail($id);

        $madre = Madre::where('cid', $alumno->cid_madre)->first();
        $padre = Padre::where('cid', $alumno->cid_padre)->first();
        $encargado = Encargado::where('cid', $alumno->cid_encargado)->first();

        $inscripciones = $alumno->inscripciones->map(fn($ins) => [
            'id' => $ins->id,
            'fecha' => $ins->fecha ? \Carbon\Carbon::parse($ins->fecha)->format('d/m/Y') : '—',
            'fecha_raw' => $ins->fecha ? \Carbon\Carbon::parse($ins->fecha)->format('Y-m-d') : '',
            'anio_lectivo' => $ins->anio_lectivo,
            'alumno_cid' => $ins->alumno_cid,
            'grado_curso_id' => $ins->grado_curso_id,
            'grado_curso' => $ins->grado->gradocurso ?? '—',
            'procede' => $ins->procede,
            'fpago' => $ins->fpago,
            'firmante_rol' => $ins->firmante_rol,
            'firmante_nombre' => $ins->firmante_nombre,
            'monto_matricula' => $ins->monto_matricula,
            'monto_anualidad' => $ins->monto_anualidad,
            'aut_mochila' => $ins->aut_mochila,
            'aut_foto' => $ins->aut_foto,
            'estado' => $ins->estado,
            'fecha_baja' => $ins->fecha_baja ? \Carbon\Carbon::parse($ins->fecha_baja)->format('Y-m-d') : '',
            'observaciones' => $ins->observaciones,
        ]);

        // Mapeo enriquecido con IDs nativos para el funcionamiento del modal de faltas
        $faltas = $alumno->faltas->map(fn($f) => [
            'id' => $f->id,
            'fecha' => $f->fecha ? \Carbon\Carbon::parse($f->fecha)->format('d/m/Y') : '—',
            'fecha_raw' => $f->fecha ? \Carbon\Carbon::parse($f->fecha)->format('Y-m-d') : '',
            'falta' => $f->indicadorFalta->indicador_falta ?? '—',
            'indicador_falta_id' => $f->indicador_falta_id,
            'grado_curso' => $f->gradoCurso->gradocurso ?? '—',
            'grado_curso_id' => $f->grado_curso_id,
            'asignatura' => $f->asignatura->asignatura ?? '—',
            'asignatura_id' => $f->asignatura_id,
            'alumno_id' => $f->alumno_id,
        ]);

        // Entrevistas combinadas (Alumnos + Responsables)
        $entrevistas = collect();

        foreach ($alumno->entrevistasAlumnos as $ea) {
            $entrevistas->push([
                'id' => $ea->id,
                'tipo' => 'Alumno',
                'fecha' => $ea->fecha->format('d/m/Y'),
                'fecha_raw' => $ea->fecha->format('Y-m-d'),
                'entrevistador' => $ea->entrevistador ? $ea->entrevistador->apellidos . ', ' . $ea->entrevistador->nombres : '—',
                'colaborador_id' => $ea->colaborador_id,
                'motivo' => $ea->motivo,
                'obs' => $ea->observaciones,
            ]);
        }

        foreach ($alumno->entrevistasResponsables as $er) {
            $entrevistas->push([
                'id' => $er->id,
                'tipo' => 'Responsable',
                'fecha' => $er->fecha->format('d/m/Y'),
                'fecha_raw' => $er->fecha->format('Y-m-d'),
                'entrevistador' => $er->entrevistador ? $er->entrevistador->apellidos . ', ' . $er->entrevistador->nombres : '—',
                'colaborador_id' => $er->colaborador_id,
                'motivo' => $er->motivo,
                'obs' => $er->observaciones,
                'testigos' => $er->testigos->pluck('id'),
            ]);
        }

        $entrevistas = $entrevistas->sortByDesc(fn($e) => $e['fecha_raw'])->values();

        return response()->json([
            'madre' => $madre,
            'padre' => $padre,
            'encargado' => $encargado,
            'inscripciones' => $inscripciones,
            'faltas' => $faltas,
            'entrevistas' => $entrevistas,
        ]);
    }

    // ----------------------------------------------------------------
    // GET  academica/faltas/{id}/detalle
    // AJAX: detalle completo de una falta individual (modal lupa)
    // ----------------------------------------------------------------
    public function detalleFalta(int $id): JsonResponse
    {
        $falta = Falta::with(['gradoCurso', 'asignatura', 'indicadorFalta', 'alumno'])
            ->findOrFail($id);

        return response()->json([
            'id' => $falta->id,
            'fecha' => $falta->fecha
                ? \Carbon\Carbon::parse($falta->fecha)->format('d/m/Y')
                : '—',
            'alumno' => $falta->alumno
                ? $falta->alumno->apellidos . ', ' . $falta->alumno->nombres
                : '—',
            'grado_curso' => $falta->gradoCurso->gradocurso ?? '—',
            'asignatura' => $falta->asignatura->asignatura ?? '—',
            'indicador' => $falta->indicadorFalta->indicador_falta ?? '—',
        ]);
    }

    public function toggleEstado(Request $request, $id)
    {
        if ($request->has('al_dia')) {
            $valor = filter_var($request->input('al_dia'), FILTER_VALIDATE_BOOLEAN);
            $alumno = \App\Models\Alumno::findOrFail($id);
            \App\Models\Inscripcion::where('alumno_cid', $alumno->cid)
                ->where('anio_lectivo', date('Y'))
                ->update(['al_dia' => $valor]);
        }

        if ($request->has('activo')) {
            $alumno = \App\Models\Alumno::findOrFail($id);
            $alumno->activo = filter_var($request->input('activo'), FILTER_VALIDATE_BOOLEAN) ? 'Sí' : 'No';
            $alumno->save();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Devuelve los exámenes del calendario para el grado_curso actual del alumno.
     */
    public function getCalendarioExamenes($alumnoId)
    {
        // Obtener el grado_curso_id de la inscripción activa del alumno en el año actual
        $inscripcion = \App\Models\Inscripcion::where('alumno_id', $alumnoId)
            ->where('anio_lectivo', date('Y'))
            ->where('estado', 'Matriculado')
            ->orderBy('fecha', 'desc')
            ->first();

        if (!$inscripcion) {
            return response()->json(['examenes' => [], 'grado_curso' => null]);
        }

        $gradoCursoId = $inscripcion->grado_curso_id;

        $examenes = \App\Models\CalendarioExamen::with([
            'asignatura1_rel',
            'asignatura2_rel',
            'asignatura3_rel',
            'grado_curso',
        ])
            ->where('grado_curso_id', $gradoCursoId)
            ->orderBy('etapa')
            ->orderBy('tipo_prueba')
            ->orderBy('fecha')
            ->get()
            ->map(function ($e) {
                return [
                    'id' => $e->id,
                    'fecha' => \Carbon\Carbon::parse($e->fecha)->format('d/m/Y'),
                    'etapa' => $e->etapa,
                    'tipo_prueba' => $e->tipo_prueba,
                    'grado_curso' => $e->grado_curso->gradocurso ?? '—',
                    'asignatura1' => $e->asignatura1_rel->asignatura ?? null,
                    'asignatura2' => $e->asignatura2_rel->asignatura ?? null,
                    'asignatura3' => $e->asignatura3_rel->asignatura ?? null,
                ];
            });

        return response()->json([
            'examenes' => $examenes,
            'grado_curso' => $inscripcion->grado->gradocurso ?? null,
        ]);
    }

}