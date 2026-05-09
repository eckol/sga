<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ParametrosBaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ciudades
        $this->importarCsv('ciudades', 'ciudades.csv', ['id', 'ciudad']);

        // 2. Nacionalidades
        $this->importarCsv('nacionalidades', 'nacionalidades.csv', ['id', 'nacionalidad']);

        // 3. Parentescos
        $this->importarCsv('parentescos', 'parentescos.csv', ['id', 'parentesco']);

        // 4. Sexos
        $this->importarCsv('sexos', 'sexos.csv', ['id', 'sexo']);

        // 5. Vivecon
        $this->importarCsv('vivecon', 'vivecon.csv', ['id', 'vive_con']);

        // 6. Ciclos
        $this->importarCsv('ciclos', 'ciclos.csv', ['id', 'ciclo']);

        // 7. Grados y Cursos
        $this->importarCsv('gc', 'gc.csv', ['id', 'grado_curso', 'turno', 'ciclo_id']);
    }

    private function importarCsv($tabla, $archivo, $columnas)
    {
        $ruta = database_path("data/$archivo");

        if (!File::exists($ruta)) {
            $this->command->warn("Archivo $archivo no encontrado. Saltando...");
            return;
        }

        $lineas = file($ruta, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lineas as $linea) {
            $fila = str_getcsv($linea, ",");
            if (count($fila) < count($columnas)) {
                $fila = str_getcsv($linea, ";");
            }

            if (empty($fila[0]))
                continue;

            // Limpieza de datos
            $registro = [];
            foreach ($columnas as $index => $columna) {
                $valor = isset($fila[$index]) ? trim($fila[$index]) : null;

                // Si la columna es 'id', nos aseguramos que sea un entero puro
                if ($columna === 'id') {
                    $valor = (int) $valor;
                }
                $registro[$columna] = $valor;
            }

            $registro['created_at'] = now();
            $registro['updated_at'] = now();

            // Usamos insertOrIgnore para evitar conflictos con los IDs autoincrementales
            DB::table($tabla)->updateOrInsert(['id' => $registro['id']], $registro);
        }

        $this->command->info("Tabla [$tabla] poblada exitosamente.");
    }
}
