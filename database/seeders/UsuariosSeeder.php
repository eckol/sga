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
        // Crear Roles
        $adminRole = \App\Models\Role::create(['rol' => 'admin']);
        \App\Models\Role::create(['rol' => 'directivo']);
        \App\Models\Role::create(['rol' => 'profeguia']);
        \App\Models\Role::create(['rol' => 'evaluador']);
        \App\Models\Role::create(['rol' => 'orientador']);
        \App\Models\Role::create(['rol' => 'responsable']);

        // Crear Usuario Admin
        \App\Models\User::create([
            'name' => 'Hector Luis Viola',
            'email' => 'hlviola@cst.edu.py', // Cambie esto por su correo real
            'password' => Hash::make('Sga2026!'), // Una contraseña segura inicial
            'role_id' => $adminRole->id,
        ]);
    }
}
