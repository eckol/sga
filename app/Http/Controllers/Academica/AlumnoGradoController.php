<?php

namespace App\Http\Controllers\Academica;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AlumnoGradoController extends Controller
{
    public function index(Request $request)
    {
        $grados = \App\Models\GradoCurso::orderBy('id')->get();
        $selectedGradoId = $request->input('grado_id', $grados->first() ? $grados->first()->id : null);

        $alumnos = collect();
        $selectedGrado = null;

        if ($selectedGradoId) {
            $selectedGrado = \App\Models\GradoCurso::find($selectedGradoId);
            $anio_actual = date('Y');

            // Get inscripciones for the selected grado, current year, and estado = Matriculado
            $inscripciones = \App\Models\Inscripcion::with([
                'alumno',
                'alumno.nacionalidad',
                'alumno.inscripciones' => function ($q) {
                    $q->where('anio_lectivo', date('Y'));
                }
            ])
                ->where('grado_curso_id', $selectedGradoId)
                ->where('anio_lectivo', $anio_actual)
                ->where('estado', 'Matriculado')
                ->get();

            $alumnos = $inscripciones->map(function ($inscripcion) {
                return $inscripcion->alumno;
            })->filter(function ($alumno) {
                return current($alumno) !== false && $alumno !== null;
            })->values();
        }

        $ciudades = \App\Models\Ciudad::orderBy('ciudad')->get();
        $nacionalidades = \App\Models\Nacionalidad::all();
        $sexos = \App\Models\Sexo::all();
        $vivecon = \App\Models\ViveCon::all();
        $parentescos = \App\Models\Parentesco::all();
        $anio_actual = date('Y');
        $anios = [$anio_actual, $anio_actual + 1];
        $indicadores = \App\Models\IndicadoresFaltas::orderBy('indicador_falta')->get();
        $colaboradores = \App\Models\Colaborador::orderBy('apellidos')->get();

        return view('academica.alumnos_grado', compact(
            'grados',
            'selectedGradoId',
            'selectedGrado',
            'alumnos',
            'ciudades',
            'nacionalidades',
            'sexos',
            'vivecon',
            'parentescos',
            'anios',
            'indicadores',
            'colaboradores'
        ));
    }

    public function getDetalles($id)
    {
        $alumno = \App\Models\Alumno::with([
            'madre',
            'padre',
            'encargado',
            'inscripciones' => function ($q) {
                $q->orderBy('fecha', 'desc');
            },
            'inscripciones.grado',
            'faltas',
            'faltas.indicadorFalta',
            'faltas.gradoCurso',
            'faltas.asignatura',
            'registrosAnecdoticos',
            'registrosAnecdoticos.asignatura',
            'registrosAnecdoticos.gradoCurso',
            'entrevistasAlumnos',
            'entrevistasResponsables'
        ])
            ->findOrFail($id);

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
            'madre' => $alumno->madre,
            'padre' => $alumno->padre,
            'encargado' => $alumno->encargado,
            'inscripciones' => $alumno->inscripciones->map(function ($ins) {
                return [
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
                    'alumno_nuevo' => $ins->alumno_nuevo ? 1 : 0,
                    'estado' => $ins->estado,
                    'fecha_baja' => $ins->fecha_baja ? \Carbon\Carbon::parse($ins->fecha_baja)->format('Y-m-d') : '',
                    'observaciones' => $ins->observaciones,
                ];
            }),
            'faltas' => $alumno->faltas->map(function ($f) {
                return [
                    'id' => $f->id,
                    'fecha' => $f->fecha
                        ? \Carbon\Carbon::parse($f->fecha)->format('d/m/Y')
                        : '—',
                    'fecha_raw' => $f->fecha
                        ? \Carbon\Carbon::parse($f->fecha)->format('Y-m-d')
                        : '',
                    'falta' => $f->indicadorFalta->indicador_falta ?? '—',
                    'grado_curso' => $f->gradoCurso->gradocurso ?? '—',
                    'asignatura' => $f->asignatura->asignatura ?? '—',
                    'indicador_falta_id' => $f->indicador_falta_id,
                    'grado_curso_id' => $f->grado_curso_id,
                    'asignatura_id' => $f->asignatura_id,
                    'alumno_id' => $f->alumno_id,
                ];
            }),
            'entrevistas' => $entrevistas,
            'registros_anecdoticos' => $alumno->registrosAnecdoticos->map(function ($r) {
                return [
                    'id' => $r->id,
                    'fecha' => $r->fecha
                        ? \Carbon\Carbon::parse($r->fecha)->format('d/m/Y')
                        : '—',
                    'fecha_raw' => $r->fecha
                        ? \Carbon\Carbon::parse($r->fecha)->format('Y-m-d')
                        : '',
                    'asignatura' => $r->asignatura->asignatura ?? '—',
                    'asignatura_id' => $r->asignatura_id,
                    'grado_curso_id' => $r->grado_curso_id,
                    'alumno_id' => $r->alumno_id,
                    'detalle' => $r->detalle,
                ];
            }),
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
     * Devuelve los registros anecdóticos de un alumno para el portal de responsables.
     * Valida que el alumno pertenezca al responsable autenticado antes de responder.
     */
    public function getRegistrosAnecdoticosPortal($id)
    {
        $emailUsuario = auth()->user()->email;

        // Verificar que el alumno le pertenece al responsable logueado
        $alumno = \App\Models\Alumno::where('id', $id)
            ->where(function ($q) use ($emailUsuario) {
                $q->whereHas('madre', fn($s) => $s->where('email', $emailUsuario))
                    ->orWhereHas('padre', fn($s) => $s->where('email', $emailUsuario))
                    ->orWhereHas('encargado', fn($s) => $s->where('email', $emailUsuario));
            })
            ->with(['registrosAnecdoticos.asignatura', 'registrosAnecdoticos.gradoCurso'])
            ->firstOrFail();

        // Enriquecer con nombre del colaborador (misma lógica que RegistroAnecdoticoController)
        $asigColabMap = \App\Models\AsignaturaColaborador::with('colaborador')->get()
            ->keyBy(fn($r) => $r->asignatura_id . '-' . $r->grado_curso_id);

        $registros = $alumno->registrosAnecdoticos
            ->sortByDesc('fecha')
            ->map(function ($r) use ($asigColabMap) {
                $key = $r->asignatura_id . '-' . $r->grado_curso_id;
                return [
                    'id' => $r->id,
                    'fecha' => $r->fecha ? \Carbon\Carbon::parse($r->fecha)->format('d/m/Y') : '—',
                    'asignatura' => $r->asignatura->asignatura ?? '—',
                    'colaborador_nombre' => isset($asigColabMap[$key])
                        ? $asigColabMap[$key]->colaborador->apellidos . ', ' . $asigColabMap[$key]->colaborador->nombres
                        : '—',
                    'detalle' => $r->detalle,
                ];
            })
            ->values();

        return response()->json(['registros_anecdoticos' => $registros]);
    }

    /**
     * Devuelve el calendario de exámenes del grado_curso actual del alumno.
     */
    public function getCalendarioExamenes($id)
    {
        $alumno = \App\Models\Alumno::findOrFail($id);

        // Inscripción activa del año en curso
        $inscripcion = \App\Models\Inscripcion::where('alumno_cid', $alumno->cid)
            ->where('anio_lectivo', date('Y'))
            ->where('estado', 'Matriculado')
            ->orderBy('fecha', 'desc')
            ->first();

        if (!$inscripcion) {
            return response()->json(['examenes' => [], 'grado_curso' => null]);
        }

        $examenes = \App\Models\CalendarioExamen::with([
            'asignatura1_rel',
            'asignatura2_rel',
            'asignatura3_rel',
            'grado_curso',
        ])
            ->where('grado_curso_id', $inscripcion->grado_curso_id)
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