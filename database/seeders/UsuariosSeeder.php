<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usamos firstOrCreate: Si ya existe el nombre, no lo crea, solo lo recupera
        $adminRole = \App\Models\Rol::firstOrCreate(['rol' => 'admin']);
        \App\Models\Rol::firstOrCreate(['rol' => 'directivo']);
        \App\Models\Rol::firstOrCreate(['rol' => 'profeguia']);
        \App\Models\Rol::firstOrCreate(['rol' => 'evaluador']);
        \App\Models\Rol::firstOrCreate(['rol' => 'orientador']);
        \App\Models\Rol::firstOrCreate(['rol' => 'responsable']);

        // Para el usuario, hacemos lo mismo con el email
        \App\Models\User::firstOrCreate(
            ['email' => 'hlviola@cst.edu.py'], // Si este email existe...
            [
                'name' => 'Hector Luis Viola',
                'password' => \Illuminate\Support\Facades\Hash::make('Sga2026!'),
                'role_id' => $adminRole->id,
            ]
        );
    }
}
