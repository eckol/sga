<?php

namespace App\Http\Controllers;

use App\Models\Vivecon;
use Illuminate\Http\Request;

class ViveconController extends Controller
{
    public function index()
    {
        $vivecon = Vivecon::orderBy('id')->get();
        return view('configuracion.vivecon.index', compact('vivecon'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vive_con' => 'required|string|max:100',
        ]);

        Vivecon::create($request->all());

        return redirect()->route('vivecon.index')
            ->with('success', 'Vivecon creado exitosamente.');
    }

    public function update(Request $request, Vivecon $vivecon)
    {
        $request->validate([
            'vive_con' => 'required|string|max:100',
        ]);

        $vivecon->update($request->all());

        return redirect()->route('vivecon.index')
            ->with('success', 'Vivecon actualizado exitosamente.');
    }

    public function destroy(Vivecon $vivecon)
    {
        $vivecon->delete();

        return redirect()->route('vivecon.index')
            ->with('success', 'Vivecon eliminado exitosamente.');
    }
}
