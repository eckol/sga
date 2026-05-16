<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoColaborador;

class TipoColaboradorController extends Controller
{
    public function index()
    {
        $tiposcolaboradores = TipoColaborador::all(); // DataTables se encarga del resto
        return view('configuracion.tiposcolaboradores.index', compact('tiposcolaboradores'));
    }

    public function store(Request $request)
    {
        $request->validate(['tipo_colaborador' => 'required|string|max:30']);
        // Solo creamos con el campo 'tipo_colaborador', ignorando el token
        TipoColaborador::create($request->only('tipo_colaborador'));
        return redirect()->back()->with('success', 'Tipo de Colaborador creado.');
    }

    // Aquí está el truco: usamos el ID directamente para no pelear con el nombre que Laravel inventa
    public function update(Request $request, $id)
    {
        $request->validate(['tipo_colaborador' => 'required|string|max:30']);
        $tipo_colaborador = TipoColaborador::findOrFail($id);
        $tipo_colaborador->update($request->only('tipo_colaborador'));
        return redirect()->back()->with('success', 'Tipo de Colaborador actualizado.');
    }

    public function destroy($id)
    {
        $tipo_colaborador = TipoColaborador::findOrFail($id);
        $tipo_colaborador->delete();
        return redirect()->back()->with('success', 'Tipo de Colaborador eliminado.');
    }
}
