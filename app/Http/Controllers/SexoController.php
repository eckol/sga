<?php

namespace App\Http\Controllers;

use App\Models\Sexo;
use Illuminate\Http\Request;


class SexoController extends Controller
{
    public function index()
    {
        $sexos = Sexo::all(); // DataTables se encarga del resto
        return view('configuracion.sexos.index', compact('sexos'));
    }

    public function store(Request $request)
    {
        $request->validate(['sexo' => 'required|string|max:255']);
        Sexo::create($request->all());
        return redirect()->back()->with('success', 'Sexo creado.');
    }

    public function update(Request $request, Sexo $sexo)
    {
        $request->validate(['sexo' => 'required|string|max:255']);
        $sexo->update($request->all());
        return redirect()->back()->with('success', 'Sexo actualizado.');
    }

    public function destroy(Sexo $sexo)
    {
        $sexo->delete();
        return redirect()->back()->with('success', 'Sexo eliminado.');
    }
}