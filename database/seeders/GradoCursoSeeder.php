<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GradoCurso;
use Illuminate\Support\Facades\DB;

class GradoCursoSeeder extends Seeder
{
    public function run(): void
    {
        $archivo = database_path('data/gc.csv');
        if (!file_exists($archivo))
            return;

        $handle = fopen($archivo, 'r');
        DB::beginTransaction();
        try {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                // Estructura CSV: id[0], gradocurso[1], turno[2], ciclo_id[3]
                GradoCurso::create([
                    'gradocurso' => $data[1],
                    'turno' => $data[2],
                    'ciclo_id' => $data[3],
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        fclose($handle);
    }
}