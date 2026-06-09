<?php

namespace App\Observers;

use App\Models\CalendarioExamen;

class CalendarioExamenObserver
{
    public function created(CalendarioExamen $examen): void
    {
        // Desactivado: El envío ahora es manual mediante acción explícita por bloque
    }

    public function updated(CalendarioExamen $examen): void
    {
        // Desactivado
    }
}