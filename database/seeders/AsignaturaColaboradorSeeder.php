<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsignaturaColaboradorSeeder extends Seeder
{
    public function run(): void
    {
        $columnaGrado = [
            '7MO. GRADO A'    => 19,
            '7MO. GRADO B'    => 20,
            '8VO. GRADO A'    => 21,
            '8VO. GRADO B'    => 22,
            '9NO. GRADO A'    => 23,
            '9NO. GRADO B'    => 24,
            '1ER. CURSO BC A' => 25,
            '1ER. CURSO BC B' => 26,
            '2DO. CURSO BC A' => 27,
            '2DO. CURSO BC B' => 28,
            '3ER. CURSO BC A' => 29,
            '3ER. CURSO BC B' => 30,
        ];

        $csvPath = database_path('data/asignaturas_por_colaboradores.csv');

        if (!file_exists($csvPath)) {
            $this->command->error("Archivo no encontrado: {$csvPath}");
            return;
        }

        // Leer todo el contenido y quitar BOM UTF-8 si existe
        $contenido = file_get_contents($csvPath);
        if (str_starts_with($contenido, "\xEF\xBB\xBF")) {
            $contenido = substr($contenido, 3);
        }

        // Escribir en archivo temporal sin BOM para procesarlo con fgetcsv
        $tmpPath = sys_get_temp_dir() . '/asig_colab_tmp.csv';
        file_put_contents($tmpPath, $contenido);

        $handle    = fopen($tmpPath, 'r');
        $header    = fgetcsv($handle, 0, ';');  // <-- separador punto y coma
        $insertados = 0;
        $omitidos   = 0;

        while (($row = fgetcsv($handle, 0, ';')) !== false) {  // <-- separador punto y coma
            $asignaturaId = (int) $row[0];

            if ($asignaturaId === 0) continue;

            foreach ($header as $i => $colNombre) {
                if ($i === 0) continue;

                $colNombre     = trim($colNombre);
                $gradoCursoId  = $columnaGrado[$colNombre] ?? null;
                $colaboradorId = isset($row[$i]) && trim($row[$i]) !== ''
                    ? (int) $row[$i]
                    : null;

                if (!$gradoCursoId || !$colaboradorId) {
                    $omitidos++;
                    continue;
                }

                DB::table('asignaturas_colaboradores')->updateOrInsert(
                    ['asignatura_id'  => $asignaturaId, 'grado_curso_id' => $gradoCursoId],
                    ['colaborador_id' => $colaboradorId, 'created_at' => now(), 'updated_at' => now()]
                );
                $insertados++;
            }
        }

        fclose($handle);
        unlink($tmpPath);

        $this->command->info("Seeder completado: {$insertados} registros insertados/actualizados, {$omitidos} omitidos.");
    }
}
