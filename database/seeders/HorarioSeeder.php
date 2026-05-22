<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Horario;
use Illuminate\Support\Facades\DB;

class HorarioSeeder extends Seeder
{
    public function run(): void
    {
        // Mapeo de columnas del CSV (índice 2 al 13) a grado_curso_id real en la BD
        $mapeoGradosCursos = [
            2 => 19, // 7MO. GRADO A
            3 => 20, // 7MO. GRADO B
            4 => 21, // 8VO. GRADO A
            5 => 22, // 8VO. GRADO B
            6 => 23, // 9NO. GRADO A
            7 => 24, // 9NO. GRADO B
            8 => 25, // 1ER. CURSO BC A
            9 => 26, // 1ER. CURSO BC B
            10 => 27, // 2DO. CURSO BC A
            11 => 28, // 2DO. CURSO BC B
            12 => 29, // 3ER. CURSO BC A
            13 => 30, // 3ER. CURSO BC B
        ];

        // Mapeo de módulo (texto del CSV) a hora_id real en la BD
        $mapeoHoras = [
            '1ra.' => 1,
            '2da.' => 2,
            '3ra.' => 3,
            '4ta.' => 4,
            '5ta.' => 5,
            '6ta.' => 6,
            '7ma.' => 7,
            '8va.' => 8,
        ];

        $archivo = database_path('data/horarios_clase.csv');

        if (!file_exists($archivo)) {
            $this->command->error("No se encontró el archivo en: $archivo");
            return;
        }

        $handle = fopen($archivo, 'r');

        // Saltar la fila de encabezado
        fgetcsv($handle, 1000, ';');

        DB::beginTransaction();

        try {
            while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {

                // Ignorar filas vacías
                if (empty(trim($data[0])))
                    continue;

                $dia = intval($data[0]);
                $modulo = trim($data[1]);
                $hora_id = $mapeoHoras[$modulo] ?? null;

                if (!$hora_id)
                    continue;

                foreach ($mapeoGradosCursos as $colIndex => $gradoCursoId) {
                    $asignaturaId = isset($data[$colIndex]) && trim($data[$colIndex]) !== ''
                        ? intval($data[$colIndex])
                        : null;

                    Horario::create([
                        'dia' => $dia,
                        'hora_id' => $hora_id,
                        'grado_curso_id' => $gradoCursoId,
                        'asignatura_id' => $asignaturaId,
                    ]);
                }
            }

            DB::commit();
            $this->command->info('Tabla de horarios poblada con éxito.');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error en el seeder: ' . $e->getMessage());
        }

        fclose($handle);
    }
}