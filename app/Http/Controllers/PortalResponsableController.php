<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PortalResponsableController extends Controller
{
    /**
     * Vista principal del Portal de Responsables
     */
    public function index()
    {
        // Correo del responsable autenticado
        $emailUsuario = auth()->user()->email;

        // Buscamos los alumnos asociados al email por cualquiera de los 3 roles
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
                'inscripciones' => function ($query) {
                    // Filtramos la inscripción activa del año corriente
                    $query->where('anio_lectivo', date('Y'))->with('gradoCurso');
                }
            ])
            ->get();

        return view('portal_responsables.index', compact('alumnos'));
    }
}