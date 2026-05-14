<?php

namespace App\Http\Controllers;

use App\Models\Arancel;
use App\Models\Ciclo;
use Illuminate\Http\Request;

class ArancelController extends Controller
{
    public function index()
    {
        // Traemos los aranceles con su ciclo, ordenados por año y ciclo
        $aranceles = Arancel::with('ciclo')->orderBy('anio_lect', 'desc')->orderBy('ciclo_id')->get();
        $ciclos = Ciclo::all();

        return view('configuracion.aranceles.index', compact('aranceles', 'ciclos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'anio_lect' => 'required|integer',
            'ciclo_id' => 'required|exists:ciclos,id',
            'monto_matricula' => 'required|numeric',
            'monto_anualidad' => 'required|numeric',
        ]);

        Arancel::create($data);
        return redirect()->back()->with('success', 'Arancel creado correctamente.');
    }

    public function update(Request $request, Arancel $arancele) // Laravel usa plural para el parámetro por defecto
    {
        $data = $request->validate([
            'anio_lect' => 'required|integer',
            'ciclo_id' => 'required|exists:ciclos,id',
            'monto_matricula' => 'required|numeric',
            'monto_anualidad' => 'required|numeric',
        ]);

        $arancele->update($data);
        return redirect()->back()->with('success', 'Arancel actualizado.');
    }

    public function destroy(Arancel $arancele)
    {
        $arancele->delete();
        return redirect()->back()->with('success', 'Arancel eliminado.');
    }
}