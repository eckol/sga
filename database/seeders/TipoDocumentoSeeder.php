<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = database_path('data/tipos_documentos.csv');
        if (!file_exists($csvFile)) {
            return;
        }

        $file = fopen($csvFile, 'r');
        $header = fgetcsv($file, 0, ';'); // Read header

        while (($data = fgetcsv($file, 0, ';')) !== FALSE) {
            \App\Models\TipoDocumento::updateOrCreate(
                ['id' => $data[0]],
                ['tipo_documento' => $data[1]]
            );
        }

        fclose($file);
    }
}
