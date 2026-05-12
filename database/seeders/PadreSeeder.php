<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Padre;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PadreSeeder extends Seeder
{
    public function run(): void
    {
        $archivo = database_path('seeders/csv/padres.csv');

        if (!file_exists($archivo)) {
            $this->command->error("El archivo no existe en: $archivo");
            return;
        }

        $handle = fopen($archivo, 'r');

        // Buscamos el rol responsable
        $rolResponsable = Rol::where('rol', 'responsable')->first();
        $rolId = $rolResponsable ? $rolResponsable->id : 6;

        DB::beginTransaction();

        try {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

                // 1. Limpieza del nombre y del BOM invisible de Excel
                $nombre = trim(str_replace("\xEF\xBB\xBF", "", $data[0]));

                $cid = trim($data[1]);

                // Saltamos si la línea está vacía o no tiene cédula
                if (empty($nombre) || empty($cid))
                    continue;

                $email = !empty($data[7]) ? trim($data[7]) : null;
                $userId = null;

                // 2. Creación de Usuario del Sistema
                if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $user = User::firstOrCreate(
                        ['email' => $email],
                        [
                            'name' => $nombre,
                            'password' => Hash::make($cid), // La cédula es la clave inicial
                            'role_id' => $rolId
                        ]
                    );
                    $userId = $user->id;
                }

                // 3. Creación del registro en la tabla Padres
                Padre::create([
                    'nombre' => $nombre,
                    'cid' => $cid,
                    'profesion' => $data[8] ?? null,
                    'direccion' => !empty($data[2]) ? $data[2] : 'Sin dirección',
                    'barrio' => $data[3] ?? null,
                    'ciudad_id' => is_numeric($data[4]) ? $data[4] : 1,
                    'telefono1' => $data[5] ?? '0',
                    'telefono2' => $data[6] ?? null,
                    'email' => $email,
                    'lugartrabajo' => $data[9] ?? null,
                    'ruc' => $data[10] ?? null,
                    'dv' => isset($data[11]) ? substr(trim($data[11]), 0, 1) : null,
                    'user_id' => $userId
                ]);
            }

            DB::commit();
            $this->command->info('¡Tabla de Padres y Usuarios cargada perfectamente!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error cargando los datos: ' . $e->getMessage());
        }

        fclose($handle);
    }
}