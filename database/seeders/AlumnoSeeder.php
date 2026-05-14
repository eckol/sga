<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumno;
use Carbon\Carbon;

class AlumnoSeeder extends Seeder
{
    public function run(): void
    {
        $archivo = database_path('data/alumnos.csv');
        if (!file_exists($archivo))
            return;

        $handle = fopen($archivo, 'r');
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            // Limpieza del BOM y validación mínima
            if (empty($data[0]))
                continue;

            // Formatear fecha
            $fecha_sql = Carbon::createFromFormat('d/m/Y', $data[4])->format('Y-m-d');

            Alumno::create([
                'apellidos' => $data[0],
                'nombres' => $data[1],
                'nacionalidad_id' => $data[2],
                'cid' => $data[3],
                'fnac' => $fecha_sql,
                'sexo_id' => $data[5],
                'direccion' => $data[6],
                'ciudad_id' => $data[7],
                'barrio' => $data[8] ?: null,
                'gmaps' => $data[9] ?: null,
                'telefono' => $data[10] ?: null,
                'email' => $data[11] ?: ($data[3] . '@cst.edu.py'),
                'passwd' => $data[12] ?: null,
                'activo' => $data[13] ?: 'Sí',
                'matriculado' => $data[14] ?: 'No',
                'vivecon_id' => $data[15] ?: 1,
                'salud' => $data[16] ?: null,
                'foto' => $data[17] ?: ($data[3] . '.jpg'),
                'observaciones' => $data[18] ?: null,
                // AQUÍ LA CORRECCIÓN: Si está vacío en el CSV, enviamos null, no ''
                'cid_madre' => !empty($data[19]) ? $data[19] : null,
                'cid_padre' => !empty($data[20]) ? $data[20] : null,
                'cid_encargado' => !empty($data[21]) ? $data[21] : null,
                'parentesco_id' => !empty($data[22]) ? $data[22] : null,
            ]);
        }
        fclose($handle);
    }
}