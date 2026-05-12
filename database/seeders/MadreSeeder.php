<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Madre;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class MadreSeeder extends Seeder
{
    public function run(): void
    {
        $archivo = database_path('seeders/csv/madres.csv');
        if (!file_exists($archivo)) {
            $this->command->error("El archivo no existe en: $archivo");
            return;
        }

        $handle = fopen($archivo, 'r');
        $rolResponsable = Rol::where('rol', 'responsable')->first();
        $rolId = $rolResponsable ? $rolResponsable->id : 6;

        DB::beginTransaction();

        try {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                // Limpieza de nombre y BOM
                $nombre = trim(str_replace("\xEF\xBB\xBF", "", $data[0]));
                $cid = trim($data[1]);

                if (empty($nombre) || empty($cid))
                    continue;

                $email = !empty($data[7]) ? trim($data[7]) : null;
                $userId = null;

                // Creación/Vinculación de Usuario
                if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $user = User::firstOrCreate(
                        ['email' => $email],
                        [
                            'name' => $nombre,
                            'password' => Hash::make($cid),
                            'role_id' => $rolId
                        ]
                    );
                    $userId = $user->id;
                }

                Madre::create([
                    'nombre' => $nombre,
                    'cid' => $cid,
                    'direccion' => $data[2] ?? 'Sin dirección',
                    'barrio' => $data[3] ?? null,
                    'ciudad_id' => is_numeric($data[4]) ? $data[4] : 1,
                    'telefono1' => $data[5] ?? '0',
                    'telefono2' => $data[6] ?? null,
                    'email' => $email,
                    'profesion' => $data[8] ?? null,
                    'lugartrabajo' => $data[9] ?? null,
                    'ruc' => $data[10] ?? null,
                    'dv' => isset($data[11]) ? substr(trim($data[11]), 0, 1) : null,
                    'user_id' => $userId
                ]);
            }

            DB::commit();
            $this->command->info('¡Tabla de Madres poblada con éxito!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error: ' . $e->getMessage());
        }
        fclose($handle);
    }
}