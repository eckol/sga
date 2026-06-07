<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CalendarioExamen;
use Carbon\Carbon;

class CalendarioExamenSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar antes de sembrar para evitar duplicados
        CalendarioExamen::truncate();

        $files = ['1.csv', '2.csv', '3.csv', '4.csv'];

        $gradeMap = [
            '7MO. GRADO A' => 19,
            '7MO. GRADO B' => 20,
            '8VO. GRADO A' => 21,
            '8VO. GRADO B' => 22,
            '9NO. GRADO A' => 23,
            '9NO. GRADO B' => 24,
            '1ER. CURSO BC A' => 25,
            '1ER. CURSO BC B' => 26,
            '2DO. CURSO BC A' => 27,
            '2DO. CURSO BC B' => 28,
            '3ER. CURSO BC A' => 29,
            '3ER. CURSO BC B' => 30,
        ];

        foreach ($files as $file) {
            $path = database_path('data/' . $file);
            if (!file_exists($path)) {
                $this->command->warn("File not found: {$path}");
                continue;
            }

            $handle = fopen($path, 'r');

            // Line 1: ciclo;... (skip)
            fgetcsv($handle, 0, ';');

            // Line 2: etapa;1ra.;;;
            $line2 = fgetcsv($handle, 0, ';');
            $etapa = $line2[1] ?? '1ra.';

            // Line 3: tipo_prueba;Parcial;;;
            $line3 = fgetcsv($handle, 0, ';');
            $tipoPrueba = $line3[1] ?? 'Parcial';

            // Line 4: botones para excel... (skip)
            fgetcsv($handle, 0, ';');

            // Line 5: FECHA;7MO. GRADO A;7MO. GRADO A;7MO. GRADO A;...
            $header = fgetcsv($handle, 0, ';');
            $grades = [];
            for ($i = 1; $i < count($header); $i += 3) {
                if (!empty($header[$i])) {
                    $gradeName = trim($header[$i]);
                    if (isset($gradeMap[$gradeName])) {
                        $grades[$i] = $gradeMap[$gradeName];
                    }
                }
            }

            // Data rows
            while (($row = fgetcsv($handle, 0, ';')) !== FALSE) {
                if (empty($row[0]))
                    continue; // Skip empty dates

                try {
                    $fecha = Carbon::createFromFormat('d/m/Y', $row[0])->format('Y-m-d');
                } catch (\Exception $e) {
                    continue; // Skip invalid date formats
                }

                foreach ($grades as $index => $gradeId) {
                    $subject1 = $row[$index] ?? null;
                    $subject2 = $row[$index + 1] ?? null;
                    $subject3 = $row[$index + 2] ?? null;

                    if (!empty($subject1) || !empty($subject2) || !empty($subject3)) {
                        CalendarioExamen::create([
                            'fecha' => $fecha,
                            'etapa' => $etapa,
                            'tipo_prueba' => $tipoPrueba,
                            'grado_curso_id' => $gradeId,
                            'asignatura1' => (!empty($subject1) && is_numeric($subject1)) ? (int) $subject1 : null,
                            'asignatura2' => (!empty($subject2) && is_numeric($subject2)) ? (int) $subject2 : null,
                            'asignatura3' => (!empty($subject3) && is_numeric($subject3)) ? (int) $subject3 : null,
                        ]);
                    }
                }
            }
            fclose($handle);
            $this->command->info("Processed: {$file}");
        }
    }
}
