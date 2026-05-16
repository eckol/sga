<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asignatura;

class AsignaturaController extends Controller
{
    public function index()
    {
        $asignaturas = Asignatura::orderBy('asignatura', 'asc')->get();
        return view('configuracion.asignaturas.index', compact('asignaturas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'asignatura' => 'required|string|max:50',
            'abreviacion' => 'required|string|max:50',
        ]);

        Asignatura::create($request->all());
        return redirect()->back()->with('success', 'Asignatura creada.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'asignatura' => 'required|string|max:50',
            'abreviacion' => 'required|string|max:50',
        ]);

        $asignatura = Asignatura::findOrFail($id);
        $asignatura->update($request->all());
        return redirect()->back()->with('success', 'Asignatura actualizada.');
    }

    public function destroy($id)
    {
        $asignatura = Asignatura::findOrFail($id);
        $asignatura->delete();
        return redirect()->back()->with('success', 'Asignatura eliminada.');
    }
}

