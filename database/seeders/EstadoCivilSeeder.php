<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\EstadoCivil;

class EstadoCivilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $archivo = database_path('data/estados_civiles.csv');

        if (!file_exists($archivo)) {
            $this->command->error("No se encontró el archivo en: $archivo");
            return;
        }

        $handle = fopen($archivo, 'r');

        DB::beginTransaction();

        try {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                // Según tu CSV: id[0], estado_civil[1]
                EstadoCivil::create([
                    'estado_civil' => $data[1],
                ]);
            }

            DB::commit();
            $this->command->info('Tabla de estados civiles poblada con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error en el seeder: ' . $e->getMessage());
        }

        fclose($handle);
    }
}
