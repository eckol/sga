<?php

namespace App\Http\Controllers;

use App\Models\Parentesco;
use Illuminate\Http\Request;

class ParentescoController extends Controller
{
    public function index()
    {
        $parentescos = Parentesco::all();
        return view('configuracion.parentescos.index', compact('parentescos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'parentesco' => 'required|string|max:50',
        ]);

        Parentesco::create($request->all());

        return redirect()->route('parentescos.index')
            ->with('success', 'Parentesco creado exitosamente.');
    }

    public function update(Request $request, Parentesco $parentesco)
    {
        $request->validate([
            'parentesco' => 'required|string|max:50',
        ]);

        $parentesco->update($request->all());

        return redirect()->route('parentescos.index')
            ->with('success', 'Parentesco actualizado exitosamente.');
    }

    public function destroy(Parentesco $parentesco)
    {
        $parentesco->delete();

        return redirect()->route('parentescos.index')
            ->with('success', 'Parentesco eliminado exitosamente.');
    }
}
