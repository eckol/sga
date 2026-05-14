<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inscripcion;
use App\Models\GradoCurso;
use App\Models\Arancel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InscripcionSeeder extends Seeder
{
    public function run(): void
    {
        $archivo = database_path('data/inscripciones.csv');
        if (!file_exists($archivo))
            return;

        $handle = fopen($archivo, 'r');
        DB::beginTransaction();

        try {
            while (($line = fgetcsv($handle, 1000, ";")) !== FALSE) {
                // Saltamos si la línea está vacía o si es la cabecera (si existe)
                if (empty($line[0]) || $line[0] == 'fecha')
                    continue;

                // Limpiamos posibles caracteres invisibles del inicio (BOM)
                $fechaRaw = trim(str_replace("\xEF\xBB\xBF", "", $line[0]));

                try {
                    $fecha = Carbon::createFromFormat('d/m/Y', $fechaRaw)->format('Y-m-d');
                } catch (\Exception $e) {
                    // Si una fecha falla, la saltamos o usamos la actual para no detener todo
                    $this->command->warn("Fecha inválida detectada: $fechaRaw. Saltando registro.");
                    continue;
                }

                $anio = $line[1];
                $cid = $line[2];
                $gc_id = $line[3];

                $grado = GradoCurso::find($gc_id);
                if (!$grado)
                    continue;

                $arancel = Arancel::where('anio_lect', $anio)
                    ->where('ciclo_id', $grado->ciclo_id)
                    ->first();

                // Determinamos firmante (limpiando los '--')
                $firmante_nombre = ($line[6] != '--') ? $line[6] : (($line[7] != '--') ? $line[7] : $line[8]);
                $firmante_rol = ($line[6] != '--') ? 'Madre' : (($line[7] != '--') ? 'Padre' : 'Encargado');

                Inscripcion::create([
                    'fecha' => $fecha,
                    'anio_lectivo' => $anio,
                    'alumno_cid' => $cid,
                    'grado_curso_id' => $gc_id,
                    'procede' => $line[4],
                    'fpago' => $line[5],
                    'firmante_nombre' => ($firmante_nombre == '--') ? 'No especificado' : $firmante_nombre,
                    'firmante_rol' => $firmante_rol,
                    'monto_matricula' => $arancel->monto_matricula ?? 0,
                    'monto_anualidad' => $arancel->monto_anualidad ?? 0,
                    'aut_mochila' => $line[11] ?? 'No',
                    'aut_foto' => $line[12] ?? 'No',
                ]);
            }
            DB::commit();
            $this->command->info('¡Inscripciones cargadas con éxito!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error crítico: ' . $e->getMessage());
        }
        fclose($handle);
    }
}