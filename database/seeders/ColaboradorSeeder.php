<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Colaborador;
use App\Models\Nacionalidad;
use App\Models\EstadoCivil;
use App\Models\Ciudad;
use App\Models\TipoColaborador;
use App\Models\Sexo;

class ColaboradorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = base_path('database/data/colaboradores.csv');
        if (!file_exists($csvFile)) {
            return;
        }

        $nacionalidades = Nacionalidad::pluck('id')->toArray();
        $estadosCiviles = EstadoCivil::pluck('id')->toArray();
        $ciudades = Ciudad::pluck('id')->toArray();
        $tiposColaboradores = TipoColaborador::pluck('id')->toArray();

        $handle = fopen($csvFile, 'r');
        // El CSV usa punto y coma como delimitador
        while (($data = fgetcsv($handle, 2000, ";")) !== FALSE) {
            // Verificar si la fila tiene datos (el ID no debe estar vacío)
            if (empty($data[0]))
                continue;

            // Mapeo de sexo 'M' -> 1, 'F' -> 2
            $sexo_id = (trim($data[6]) == 'M') ? 1 : 2;

            // Conversión de fecha DD/MM/YYYY a YYYY-MM-DD
            $fnac = null;
            if (!empty($data[4])) {
                $dateParts = explode('/', trim($data[4]));
                if (count($dateParts) == 3) {
                    $fnac = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];
                }
            }

            // Validar FKs
            $nacionalidad_id = in_array((int) $data[5], $nacionalidades) ? (int) $data[5] : (Nacionalidad::first()->id ?? 1);
            $estado_civil_id = in_array((int) $data[7], $estadosCiviles) ? (int) $data[7] : (EstadoCivil::first()->id ?? 1);
            $ciudad_id = in_array((int) $data[10], $ciudades) ? (int) $data[10] : (Ciudad::first()->id ?? 1);
            $tipo_colaborador_id = in_array((int) $data[17], $tiposColaboradores) ? (int) $data[17] : (TipoColaborador::first()->id ?? 1);

            $anios_servicio = !empty($data[21]) ? (int) $data[21] : 0;

            Colaborador::updateOrCreate(
                ['cid' => trim($data[3])], // Usar CID como clave única
                [
                    'apellidos' => trim($data[1]),
                    'nombres' => trim($data[2]),
                    'fnac' => $fnac,
                    'nacionalidad_id' => $nacionalidad_id,
                    'sexo_id' => $sexo_id,
                    'estado_civil_id' => $estado_civil_id,
                    'direccion' => trim($data[8]),
                    'barrio' => trim($data[9]),
                    'ciudad_id' => $ciudad_id,
                    'ubicacion' => trim($data[11]),
                    'telefono' => trim($data[12]),
                    'email_particular' => trim($data[13]),
                    'email_institucional' => trim($data[14]),
                    'passwd' => trim($data[15]),
                    'activo' => trim($data[16]) ?: 'Sí',
                    'tipo_colaborador_id' => $tipo_colaborador_id,
                    'titulo1' => trim($data[18]),
                    'titulo2' => trim($data[19]),
                    'titulo3' => trim($data[20]),
                    'anios_servicio' => $anios_servicio,
                    'seguro' => trim($data[22]),
                    'gsangre' => trim($data[23]),
                    'enf_cronica' => (trim($data[24]) == 'Sí') ? 'Sí' : 'No',
                    'reloj' => trim($data[25]),
                    'passwd_mec' => trim($data[26]),
                    'observaciones' => trim($data[27]),
                    'foto' => trim($data[28]),
                ]
            );
        }
        fclose($handle);
    }
}
