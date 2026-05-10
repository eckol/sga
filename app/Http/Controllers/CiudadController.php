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
        Ciudad::create($request->all());
        return redirect()->back()->with('success', 'Ciudad creada.');
    }

    public function update(Request $request, Ciudad $ciudad)
    {
        $request->validate(['ciudad' => 'required|string|max:255']);
        $ciudad->update($request->all());
        return redirect()->back()->with('success', 'Ciudad actualizada.');
    }

    public function destroy(Ciudad $ciudad)
    {
        $ciudad->delete();
        return redirect()->back()->with('success', 'Ciudad eliminada.');
    }
}
