<?php

namespace App\Http\Controllers;

use App\Models\TipoDocumento;
use Illuminate\Http\Request;

class TipoDocumentoController extends Controller
{
    public function index()
    {
        $tipos_documentos = TipoDocumento::orderBy('id', 'asc')->get();
        return view('configuracion.tipos_documentos.index', compact('tipos_documentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_documento' => 'required|string|max:50',
        ]);

        TipoDocumento::create($request->all());
        return redirect()->back()->with('success', 'Tipo de documento creado.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tipo_documento' => 'required|string|max:50',
        ]);

        $tipoDocumento = TipoDocumento::findOrFail($id);
        $tipoDocumento->update($request->all());
        return redirect()->back()->with('success', 'Tipo de documento actualizado.');
    }

    public function destroy($id)
    {
        $tipoDocumento = TipoDocumento::findOrFail($id);
        $tipoDocumento->delete();
        return redirect()->back()->with('success', 'Tipo de documento eliminado.');
    }
}
