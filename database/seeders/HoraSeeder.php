<?php

namespace database\seeders;

use App\Models\Hora;
use Illuminate\Database\Seeder;

class HoraSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = database_path('data/horas.csv');
        $fileHandle = fopen($csvFile, 'r');

        while (($row = fgetcsv($fileHandle, 1000, ';')) !== FALSE) {
            // Limpiamos espacios en blanco o caracteres raros de las horas
            $inicio = trim($row[2]);
            $fin = trim($row[3]);

            // Si viene como "06:50" o "6:50", lo transformamos a "06:50:00"
            $hora_inicio = date("H:i:s", strtotime($inicio));
            $hora_fin = date("H:i:s", strtotime($fin));

            Hora::create([
                'id' => intval($row[0]), // Nos aseguramos de que sea un entero puro
                'modulo' => trim($row[1]),
                'hora_inicio' => $hora_inicio,
                'hora_fin' => $hora_fin,
            ]);
        }

        fclose($fileHandle);
    }
}