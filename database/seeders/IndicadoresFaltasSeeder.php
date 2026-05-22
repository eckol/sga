<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IndicadoresFaltas;
use Illuminate\Support\Facades\DB;

class IndicadoresFaltasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $archivo = database_path('data/faltas.csv');

        if (!file_exists($archivo)) {
            $this->command->error("No se encontró el archivo en: $archivo");
            return;
        }

        $handle = fopen($archivo, 'r');

        DB::beginTransaction();

        try {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                // Según tu CSV: id[0], tipo_falta[1]
                IndicadoresFaltas::create([
                    'tipo_falta' => $data[1],
                ]);
            }

            DB::commit();
            $this->command->info('Tabla de indicadores_faltas poblada con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error en el seeder: ' . $e->getMessage());
        }

        fclose($handle);
    }
}
