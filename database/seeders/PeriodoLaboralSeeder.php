<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PeriodoLaboral;
use App\Models\Colaborador;
use Carbon\Carbon;

class PeriodoLaboralSeeder extends Seeder
{
    public function run(): void
    {
        $archivo = database_path('data/periodos_laborales.csv');

        if (!file_exists($archivo)) {
            $this->command->error("No se encontró el archivo en: $archivo");
            return;
        }

        $handle = fopen($archivo, 'r');
        DB::beginTransaction();

        try {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                // Saltar filas totalmente vacías del CSV
                if (empty($data) || !isset($data[1]) || trim($data[1]) === '') {
                    continue;
                }

                $cid = trim($data[1]);

                // Conversión segura de fechas usando Carbon
                $ingreso = Carbon::createFromFormat('d/m/Y', trim($data[2]))->format('Y-m-d');

                $egreso = (!empty($data[3]) && trim($data[3]) !== '')
                    ? Carbon::createFromFormat('d/m/Y', trim($data[3]))->format('Y-m-d')
                    : null;

                $observacion = (!empty($data[4]) && trim($data[4]) !== '') ? trim($data[4]) : null;

                // Buscar el ID real usando el número de cédula
                $colaborador = Colaborador::where('cid', $cid)->first();

                if ($colaborador) {
                    PeriodoLaboral::create([
                        'colaborador_id' => $colaborador->id,
                        'fecha_ingreso' => $ingreso,
                        'fecha_egreso' => $egreso,
                        'observacion' => $observacion,
                    ]);
                } else {
                    $this->command->warn("C.I.D: $cid no encontrado en la tabla colaboradores. Fila omitida.");
                }
            }

            DB::commit();
            $this->command->info('Tabla de períodos laborales poblada con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error procesando el Seeder: ' . $e->getMessage());
        }

        fclose($handle);
    }
}