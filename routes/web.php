<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\CicloController;
use App\Http\Controllers\SexoController;
use App\Http\Controllers\NacionalidadController;
use App\Http\Controllers\ParentescoController;
use App\Http\Controllers\ViveConController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResponsableController;
use App\Http\Controllers\ArancelController;
use App\Http\Controllers\GradoCursoController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\HoraController;
use App\Http\Controllers\EstadoCivilController;
use App\Http\Controllers\TipoColaboradorController;
use App\Http\Controllers\AsignaturaController;
use App\Http\Controllers\ColaboradorController;
use App\Http\Controllers\PeriodoLaboralController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\IndicadoresFaltasController;
use App\Http\Controllers\AsignaturaColaboradorController;
use App\Http\Controllers\FaltaController;
use App\Http\Controllers\Academica\AsistenciaController;
use App\Http\Controllers\PortalResponsableController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    if (auth()->user()->role_id === 6) {
        return redirect()->route('portal_responsables.index');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('ciudades', CiudadController::class)->except(['create', 'show', 'edit']);
    Route::resource('ciclos', CicloController::class)->except(['create', 'show', 'edit']);
    Route::resource('sexos', SexoController::class)->except(['create', 'show', 'edit']);
    Route::resource('nacionalidades', NacionalidadController::class)->except(['create', 'show', 'edit']);
    Route::resource('parentescos', ParentescoController::class)->except(['create', 'show', 'edit']);
    Route::resource('vivecon', ViveConController::class)->except(['create', 'show', 'edit']);
    Route::resource('roles', RolController::class)->except(['create', 'show', 'edit']);
    Route::resource('usuarios', UserController::class)->except(['create', 'show', 'edit']);
    Route::resource('aranceles', ArancelController::class)->except(['create', 'show', 'edit']);
    Route::resource('gradoscursos', GradoCursoController::class)->except(['create', 'show', 'edit']);
    Route::resource('inscripciones', InscripcionController::class)->except(['create', 'show', 'edit']);
    Route::resource('horas', HoraController::class)->except(['create', 'show', 'edit']);
    Route::resource('estadosciviles', EstadoCivilController::class)->except(['create', 'show', 'edit']);
    Route::resource('tiposcolaboradores', TipoColaboradorController::class)->except(['create', 'show', 'edit']);
    Route::resource('asignaturas', AsignaturaController::class)->except(['create', 'show', 'edit']);
    Route::resource('indicadores_faltas', IndicadoresFaltasController::class)->except(['create', 'show', 'edit']);
    Route::resource('asignaturas-colaboradores', AsignaturaColaboradorController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names([
            'asignaturas-colaboradores.index' => 'asignaturas-colaboradores.index',
            'asignaturas-colaboradores.store' => 'asignaturas-colaboradores.store',
            'asignaturas-colaboradores.update' => 'asignaturas-colaboradores.update',
            'asignaturas-colaboradores.destroy' => 'asignaturas-colaboradores.destroy',
        ]);

    // ── Portal de Responsables (fuera de rrhh, URL limpia) ──
    Route::get('/portal-responsables', [PortalResponsableController::class, 'index'])
        ->name('portal_responsables.index');
});

Route::middleware(['auth'])->group(function () {

    Route::prefix('rrhh')->group(function () {
        Route::get('responsables/{tipo}', [ResponsableController::class, 'index'])
            ->name('responsables.index');
        Route::post('responsables/{tipo}', [ResponsableController::class, 'store'])
            ->name('responsables.store');
        Route::put('responsables/{tipo}/{id}', [ResponsableController::class, 'update'])
            ->name('responsables.update');
        Route::delete('responsables/{tipo}/{id}', [ResponsableController::class, 'destroy'])
            ->name('responsables.destroy');
        Route::get('responsables/{tipo}/buscar/{cid}', [ResponsableController::class, 'getByCid'])
            ->name('responsables.buscar');

        Route::get('alumnos', [AlumnoController::class, 'index'])->name('alumnos.index');
        Route::post('alumnos', [AlumnoController::class, 'store'])->name('alumnos.store');
        Route::put('alumnos/{id}', [AlumnoController::class, 'update'])->name('alumnos.update');
        Route::delete('alumnos/{id}', [AlumnoController::class, 'destroy'])->name('alumnos.destroy');

        Route::get('colaboradores', [ColaboradorController::class, 'index'])->name('colaboradores.index');
        Route::post('colaboradores', [ColaboradorController::class, 'store'])->name('colaboradores.store');
        Route::put('colaboradores/{id}', [ColaboradorController::class, 'update'])->name('colaboradores.update');
        Route::delete('colaboradores/{id}', [ColaboradorController::class, 'destroy'])->name('colaboradores.destroy');
        Route::resource('periodos-laborales', PeriodoLaboralController::class)->except(['create', 'show', 'edit']);
    });

    Route::prefix('academica')->group(function () {
        Route::get('alumnos-grado', [\App\Http\Controllers\Academica\AlumnoGradoController::class, 'index'])->name('academica.alumnos-grado');
        Route::get('alumnos/{id}/detalles', [\App\Http\Controllers\Academica\AlumnoGradoController::class, 'getDetalles'])->name('academica.alumnos.detalles');
        Route::post('alumnos/{id}/toggle', [\App\Http\Controllers\Academica\AlumnoGradoController::class, 'toggleEstado'])->name('academica.alumnos.toggle');
        Route::get('horarios', [HorarioController::class, 'index'])->name('academica.horarios.index');
        Route::put('horarios/{id}', [HorarioController::class, 'update'])->name('academica.horarios.update');
        Route::get('docentes-asignatura', [AsignaturaColaboradorController::class, 'index'])->name('academica.docentes-asignatura.index');
        Route::put('docentes-asignatura/{asignatura}/{grado}', [AsignaturaColaboradorController::class, 'update'])->name('academica.docentes-asignatura.update');
        Route::get('faltas', [FaltaController::class, 'index'])->name('academica.faltas.index');
        Route::post('faltas', [FaltaController::class, 'store'])->name('academica.faltas.store');
        Route::put('faltas/{id}', [FaltaController::class, 'update'])->name('academica.faltas.update');
        Route::delete('faltas/{id}', [FaltaController::class, 'destroy'])->name('academica.faltas.destroy');
        Route::get('faltas/alumnos-por-grado/{grado}', [FaltaController::class, 'alumnosPorGrado'])->name('academica.faltas.alumnos-por-grado');
        Route::get('faltas/asignaturas-por-grado/{grado}', [FaltaController::class, 'asignaturasPorGrado'])->name('academica.faltas.asignaturas-por-grado');
        Route::get('/asistencias', [AsistenciaController::class, 'index'])->name('asistencias.index');
        Route::post('/asistencias', [AsistenciaController::class, 'store'])->name('asistencias.store');
        Route::post('/asistencias/guardar-grilla', [AsistenciaController::class, 'guardarGrilla'])->name('asistencias.guardarGrilla');
        Route::get('/asistencias/{alumno}/por-alumno', [AsistenciaController::class, 'porAlumno'])->name('asistencias.porAlumno');
        Route::delete('/asistencias/{id}', [AsistenciaController::class, 'destroy'])->name('asistencias.destroy');
    });
});

require __DIR__ . '/auth.php';