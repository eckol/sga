<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use Illuminate\Http\Request;

class CiudadController extends Controller
{
    public function index()
    {
        $ciudades = Ciudad::all(); // DataTables se encarga del resto
        return view('configuracion.ciudades.index', compact('ciudades'));
    }

    public function store(Request $request)
    {
        $request->validate(['ciudad' => 'required|string|max:255']);
        // Solo creamos con el campo 'ciudad', ignorando el token
        Ciudad::create($request->only('ciudad'));
        return redirect()->back()->with('success', 'Ciudad creada.');
    }

    // Aquí está el truco: usamos el ID directamente para no pelear con el nombre que Laravel inventa
    public function update(Request $request, $id)
    {
        $request->validate(['ciudad' => 'required|string|max:255']);
        $ciudad = Ciudad::findOrFail($id);
        $ciudad->update($request->only('ciudad'));
        return redirect()->back()->with('success', 'Ciudad actualizada.');
    }

    public function destroy($id)
    {
        $ciudad = Ciudad::findOrFail($id);
        $ciudad->delete();
        return redirect()->back()->with('success', 'Ciudad eliminada.');
    }
}
