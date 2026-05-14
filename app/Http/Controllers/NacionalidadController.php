<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nacionalidad;

class NacionalidadController extends Controller
{
    public function index()
    {
        $nacionalidades = Nacionalidad::all(); // DataTables se encarga del resto
        return view('configuracion.nacionalidades.index', compact('nacionalidades'));
    }

    public function store(Request $request)
    {
        $request->validate(['nacionalidad' => 'required|string|max:255']);
        Nacionalidad::create($request->all());
        return redirect()->back()->with('success', 'Nacionalidad creada.');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nacionalidad' => 'required|string|max:255']);
        $nacionalidad = Nacionalidad::findOrFail($id);
        $nacionalidad->update($request->only('nacionalidad'));
        return redirect()->back()->with('success', 'Nacionalidad actualizada.');
    }

    public function destroy($id)
    {
        $nacionalidad = Nacionalidad::findOrFail($id);
        $nacionalidad->delete();
        return redirect()->back()->with('success', 'Nacionalidad eliminada.');
    }
}
