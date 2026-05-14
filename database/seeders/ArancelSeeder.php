<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Arancel;
use Illuminate\Support\Facades\DB;

class ArancelSeeder extends Seeder
{
    public function run(): void
    {
        $archivo = database_path('data/aranceles.csv');

        if (!file_exists($archivo)) {
            $this->command->error("No se encontró el archivo en: $archivo");
            return;
        }

        $handle = fopen($archivo, 'r');

        DB::beginTransaction();

        try {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                // Según tu CSV: id[0], anio[1], ciclo[2], matricula[3], anualidad[4]
                Arancel::create([
                    'anio_lect' => $data[1],
                    'ciclo_id' => $data[2],
                    'monto_matricula' => $data[3],
                    'monto_anualidad' => $data[4],
                ]);
            }

            DB::commit();
            $this->command->info('Tabla de aranceles poblada con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error en el seeder: ' . $e->getMessage());
        }

        fclose($handle);
    }
}