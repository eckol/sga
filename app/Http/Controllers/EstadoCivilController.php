<?php

namespace App\Http\Controllers;
use App\Models\EstadoCivil;
use Illuminate\Http\Request;

class EstadoCivilController extends Controller
{
    public function index()
    {
        $estadosciviles = EstadoCivil::all(); // DataTables se encarga del resto
        return view('configuracion.estadosciviles.index', compact('estadosciviles'));
    }

    public function store(Request $request)
    {
        $request->validate(['estado_civil' => 'required|string|max:10']);
        // Solo creamos con el campo 'estado_civil', ignorando el token
        EstadoCivil::create($request->only('estado_civil'));
        return redirect()->back()->with('success', 'Estado civil creado.');
    }

    // Aquí está el truco: usamos el ID directamente para no pelear con el nombre que Laravel inventa
    public function update(Request $request, $id)
    {
        $request->validate(['estado_civil' => 'required|string|max:10']);
        $estado_civil = EstadoCivil::findOrFail($id);
        $estado_civil->update($request->only('estado_civil'));
        return redirect()->back()->with('success', 'Estado civil actualizado.');
    }

    public function destroy($id)
    {
        $estado_civil = EstadoCivil::findOrFail($id);
        $estado_civil->delete();
        return redirect()->back()->with('success', 'Estado civil eliminado.');
    }
}
