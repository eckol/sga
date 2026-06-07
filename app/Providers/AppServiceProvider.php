<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\RegistroAnecdotico;
use App\Models\EntrevistaAlumno;
use App\Models\CalendarioExamen;
use App\Observers\RegistroAnecdoticoObserver;
use App\Observers\EntrevistaAlumnoObserver;
use App\Observers\CalendarioExamenObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Registro de los Observers
        RegistroAnecdotico::observe(RegistroAnecdoticoObserver::class);
        EntrevistaAlumno::observe(EntrevistaAlumnoObserver::class);
        CalendarioExamen::observe(CalendarioExamenObserver::class);
    }
}