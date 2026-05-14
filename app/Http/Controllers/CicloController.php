<?php

namespace App\Http\Controllers;

use App\Models\Ciclo;
use Illuminate\Http\Request;

class CicloController extends Controller
{
    public function index()
    {
        $ciclos = Ciclo::all(); // DataTables se encarga del resto
        return view('configuracion.ciclos.index', compact('ciclos'));
    }

    public function store(Request $request)
    {
        $request->validate(['ciclo' => 'required|string|max:255']);
        Ciclo::create($request->all());
        return redirect()->back()->with('success', 'Ciclo creado.');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['ciclo' => 'required|string|max:255']);
        $ciclo = Ciclo::findOrFail($id);
        $ciclo->update($request->only('ciclo'));
        return redirect()->back()->with('success', 'Ciclo actualizado.');
    }

    public function destroy($id)
    {
        $ciclo = Ciclo::findOrFail($id);
        $ciclo->delete();
        return redirect()->back()->with('success', 'Ciclo eliminado.');
    }
}
