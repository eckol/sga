<?php

namespace App\Http\Controllers;

use App\Models\GradoCurso;
use App\Models\Ciclo;
use Illuminate\Http\Request;

class GradoCursoController extends Controller
{
    public function index()
    {
        $gradoscursos = GradoCurso::with('ciclo')->get();
        $ciclos = Ciclo::all();
        return view('configuracion.gradoscursos.index', compact('gradoscursos', 'ciclos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'gradocurso' => 'required',
            'turno' => 'required',
            'ciclo_id' => 'required|exists:ciclos,id'
        ]);
        GradoCurso::create($request->all());
        return redirect()->back()->with('success', 'Registro creado.');
    }

    public function update(Request $request, $id)
    {
        $gc = GradoCurso::findOrFail($id);
        $gc->update($request->all());
        return redirect()->back()->with('success', 'Registro actualizado.');
    }

    public function destroy($id)
    {
        GradoCurso::destroy($id);
        return redirect()->back()->with('success', 'Registro eliminado.');
    }
}