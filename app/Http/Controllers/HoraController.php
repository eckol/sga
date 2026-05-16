<?php

namespace App\Http\Controllers;

use App\Models\Hora;
use Illuminate\Http\Request;

class HoraController extends Controller
{
    public function index()
    {
        $horas = Hora::orderBy('hora_inicio', 'asc')->get();
        return view('configuracion.horas.index', compact('horas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'modulo' => 'required|string|max:10',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
        ]);

        Hora::create($request->all());
        return redirect()->back()->with('success', 'Módulo horario creado.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'modulo' => 'required|string|max:10',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
        ]);

        $hora = Hora::findOrFail($id);
        $hora->update($request->all());
        return redirect()->back()->with('success', 'Módulo actualizado.');
    }

    public function destroy($id)
    {
        $hora = Hora::findOrFail($id);
        $hora->delete();
        return redirect()->back()->with('success', 'Módulo eliminado.');
    }
}