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
}