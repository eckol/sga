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

    public function update(Request $request, $id)
    {
        $request->validate([
            'parentesco' => 'required|string|max:50',
        ]);

        $parentesco = Parentesco::findOrFail($id);
        $parentesco->update($request->only('parentesco'));

        return redirect()->route('parentescos.index')
            ->with('success', 'Parentesco actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $parentesco = Parentesco::findOrFail($id);
        $parentesco->delete();

        return redirect()->route('parentescos.index')
            ->with('success', 'Parentesco eliminado exitosamente.');
    }
}
