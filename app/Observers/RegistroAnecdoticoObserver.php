<?php

namespace App\Observers;

use App\Models\RegistroAnecdotico;
use App\Mail\NotificacionEventoAlumno;
use Illuminate\Support\Facades\Mail;

class RegistroAnecdoticoObserver
{
    public function created(\App\Models\RegistroAnecdotico $registro): void
    {
        // Forzamos la carga de las relaciones
        $registro = \App\Models\RegistroAnecdotico::with(['alumno', 'asignatura'])
            ->find($registro->id);

        if (!$registro)
            return;

        $alumno = $registro->alumno;
        if (!$alumno)
            return;

        $emails = $alumno->getResponsablesEmails();
        if (empty($emails))
            return;

        $nombreCompleto = $alumno->nombres . ' ' . $alumno->apellidos;
        $fechaFormateada = date('d/m/Y', strtotime($registro->fecha));

        // Si tu columna FK o relación difiere, nos aseguramos con un fallback
        $materiaNombre = 'No especificada';
        if ($registro->asignatura) {
            $materiaNombre = $registro->asignatura->asignatura;
        } elseif ($registro->asignatura_id) {
            // Por si acaso la relación se llame distinto pero el ID exista, busca el registro:
            $materia = \App\Models\Asignatura::find($registro->asignatura_id);
            if ($materia)
                $materiaNombre = $materia->asignatura;
        }

        // Dejar dos espacios al final de la línea o doble salto fuerza el renderizado en Markdown
        $detalle = "Asignatura: " . $materiaNombre . "  \n" .
            "Fecha: " . $fechaFormateada . "  \n" .
            "Detalle: " . $registro->detalle;

        \Illuminate\Support\Facades\Mail::to($emails)->send(
            new \App\Mail\NotificacionEventoAlumno($nombreCompleto, 'Registro Anecdótico', $detalle)
        );
    }
}