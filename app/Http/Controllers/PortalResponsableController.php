<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PortalResponsableController extends Controller
{
    public function index()
    {
        $emailUsuario = auth()->user()->email;

        $alumnos = Alumno::whereHas('madre', function ($q) use ($emailUsuario) {
            $q->where('email', $emailUsuario);
        })
            ->orWhereHas('padre', function ($q) use ($emailUsuario) {
                $q->where('email', $emailUsuario);
            })
            ->orWhereHas('encargado', function ($q) use ($emailUsuario) {
                $q->where('email', $emailUsuario);
            })
            ->with([
                'madre',
                'padre',
                'encargado',
                'sexo',
                'nacionalidad',
                'ciudad',
                'vivecon',
                'faltas.indicadorFalta',
                'faltas.asignatura',
                'inscripciones' => function ($query) {
                    $query->where('anio_lectivo', date('Y'))->with('grado');
                }
            ])
            ->get();

        $tieneDeuda = $alumnos->contains(function ($alumno) {
            return $alumno->inscripciones
                ->where('al_dia', 0)  // <- usar 0 en lugar de false
                ->isNotEmpty();
        });

        return view('portal_responsables.index', compact('alumnos', 'tieneDeuda'));
    }
}