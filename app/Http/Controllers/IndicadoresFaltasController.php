<?php

namespace App\Http\Controllers;
use App\Models\IndicadoresFaltas;
use Illuminate\Http\Request;

class IndicadoresFaltasController extends Controller
{
    public function index()
    {
        $faltas = IndicadoresFaltas::all(); // DataTables se encarga del resto
        return view('configuracion.indicadores_faltas.index', compact('faltas'));
    }

    public function store(Request $request)
    {
        $request->validate(['tipo_falta' => 'required|string|max:255']);
        IndicadoresFaltas::create($request->only('tipo_falta'));
        return redirect()->back()->with('success', 'Falta creada.');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['tipo_falta' => 'required|string|max:255']);
        $falta = IndicadoresFaltas::findOrFail($id);
        $falta->update($request->only('tipo_falta'));
        return redirect()->back()->with('success', 'Falta actualizada.');
    }

    public function destroy($id)
    {
        $falta = IndicadoresFaltas::findOrFail($id);
        $falta->delete();
        return redirect()->back()->with('success', 'Falta eliminada.');
    }
}
