<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rol; // Asegúrate que el modelo se llame Rol (sin la e)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Traemos los usuarios con su rol cargado para evitar consultas extra
        $users = User::with('rol')->get();
        $roles = Rol::all();

        return view('configuracion.usuarios.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Encriptación obligatoria
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->all());

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario)
    {
        // Evitar que el usuario se borre a sí mismo
        if (auth()->id() === $usuario->id) {
            return redirect()->back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado.');
    }
}