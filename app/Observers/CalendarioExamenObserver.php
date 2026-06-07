<?php

namespace App\Observers;

use App\Models\CalendarioExamen;
use App\Models\Alumno;
use App\Mail\NotificacionEventoAlumno;
use Illuminate\Support\Facades\Mail;

class CalendarioExamenObserver
{
    public function created(CalendarioExamen $examen): void
    {
        // Buscamos los alumnos que pertenecen al grado/curso del examen.
        // Asumo que tu tabla 'inscripciones' conecta alumnos con grado_curso_id
        // Si tienes una relación directa o a través de Inscripcion, la usamos:
        $alumnos = Alumno::whereHas('asistencias', function ($q) use ($examen) {
            // Nota: Como alternativa genérica si usas una tabla inscripciones:
            // Vamos a buscar los alumnos cuyo curso activo coincida.
        })->get();

        // Una forma más directa basada en cómo estructures las inscripciones:
        // Si tienes el modelo Inscripcion, podemos mapear los alumnos de ese curso:
        $alumnos = Alumno::whereIn('cid', function ($query) use ($examen) {
            $query->select('alumno_cid')
                ->from('inscripciones')
                ->where('grado_curso_id', $examen->grado_curso_id);
        })->get();

        foreach ($alumnos as $alumno) {
            $emails = $alumno->getResponsablesEmails();
            if (empty($emails))
                continue;

            $nombreCompleto = $alumno->nombres . ' ' . $alumno->apellidos;
            $detalle = "Fecha de Evaluación: " . $examen->fecha . "\nTipo: " . $examen->tipo_prueba . "\nEtapa: " . $examen->etapa;

            Mail::to($emails)->send(new NotificacionEventoAlumno($nombreCompleto, 'Calendario de Examen / Prueba Escrita', $detalle));
        }
    }
}