<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rol;

class RolController extends Controller
{
    public function index()
    {
        $roles = Rol::all();
        return view('configuracion.roles.index', compact('roles'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'rol' => 'required|string|max:50',
        ]);

        Rol::create($request->all());

        return redirect()->route('roles.index')
            ->with('success', 'Rol creado exitosamente.');
    }

    public function update(Request $request, Rol $role)
    {
        $request->validate(['rol' => 'required|string|max:50']);
        $role->update($request->all());
        return redirect()->route('roles.index')->with('success', 'Rol actualizado.');
    }

    public function destroy(Rol $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Rol eliminado.');
    }
}
