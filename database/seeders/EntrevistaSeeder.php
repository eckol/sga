<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EntrevistaAlumno;
use App\Models\Alumno;
use App\Models\Colaborador;

class EntrevistaSeeder extends Seeder
{
    public function run(): void
    {
        $alumno = Alumno::first();
        $colaborador = Colaborador::first();

        if ($alumno && $colaborador) {
            EntrevistaAlumno::create([
                'fecha' => now(),
                'alumno_id' => $alumno->id,
                'colaborador_id' => $colaborador->id,
                'motivo' => 'Seguimiento de rendimiento académico',
                'observaciones' => 'El alumno se compromete a mejorar su conducta en clase.',
            ]);
        }
    }
}