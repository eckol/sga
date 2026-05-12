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

Route::get('/', function () {
    //return view('welcome');
    //esta línea me lleva directamente a la página del login
    Route::get('/', function () {
        return redirect()->route('login');
    });
});

Route::get('/dashboard', function () {
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

});

Route::middleware(['auth'])->group(function () {

    Route::prefix('rrhh')->group(function () {
        // Vista principal (Listado)
        Route::get('responsables/{tipo}', [ResponsableController::class, 'index'])
            ->name('responsables.index');

        Route::post('responsables/{tipo}', [ResponsableController::class, 'store'])
            ->name('responsables.store');

        Route::put('responsables/{tipo}/{id}', [ResponsableController::class, 'update'])
            ->name('responsables.update');

        Route::delete('responsables/{tipo}/{id}', [ResponsableController::class, 'destroy'])
            ->name('responsables.destroy');
    });
});

require __DIR__ . '/auth.php';
