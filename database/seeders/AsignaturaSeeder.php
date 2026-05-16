<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Asignatura;
use Illuminate\Support\Facades\DB;

class AsignaturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $archivo = database_path('data/asignaturas.csv');

        if (!file_exists($archivo)) {
            $this->command->error("No se encontró el archivo en: $archivo");
            return;
        }

        $handle = fopen($archivo, 'r');

        DB::beginTransaction();

        try {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                // Según tu CSV: id[0], asignatura[1], abreviacion[2]
                Asignatura::create([
                    'asignatura' => $data[1],
                    'abreviacion' => $data[2],
                ]);
            }

            DB::commit();
            $this->command->info('Tabla de asignaturas poblada con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error en el seeder: ' . $e->getMessage());
        }

        fclose($handle);
    }
}
