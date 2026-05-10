<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\CicloController;

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
});

require __DIR__ . '/auth.php';
