<?php

namespace App\Observers;

use App\Models\EntrevistaAlumno;
use App\Mail\NotificacionEventoAlumno;
use Illuminate\Support\Facades\Mail;

class EntrevistaAlumnoObserver
{
    public function created(EntrevistaAlumno $entrevista): void
    {
        $alumno = $entrevista->alumno;
        if (!$alumno)
            return;

        $emails = $alumno->getResponsablesEmails();
        if (empty($emails))
            return;

        $nombreCompleto = $alumno->nombres . ' ' . $alumno->apellidos;
        $detalle = "Fecha: " . $entrevista->fecha->format('d/m/Y') . "\nMotivo: " . $entrevista->motivo;

        Mail::to($emails)->send(new NotificacionEventoAlumno($nombreCompleto, 'Entrevista con Orientación/Dirección', $detalle));
    }
}