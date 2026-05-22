<?php

namespace App\Http\Controllers;

use App\Models\PeriodoLaboral;
use App\Models\Colaborador;
use Illuminate\Http\Request;

class PeriodoLaboralController extends Controller
{
    public function index()
    {
        // Eager loading para traer los nombres y apellidos del colaborador sin generar N+1 queries
        $periodos = PeriodoLaboral::with('colaborador')->get();
        return view('rrhh.colaboradores.periodos_laborales.index', compact('periodos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cid' => 'required|string',
            'fecha_ingreso' => 'required|date',
            'fecha_egreso' => 'nullable|date',
            'observacion' => 'nullable|string|max:255'
        ]);

        // Buscamos al colaborador por cédula en el formulario
        $colaborador = Colaborador::where('cid', $request->cid)->firstOrFail();

        PeriodoLaboral::create([
            'colaborador_id' => $colaborador->id,
            'fecha_ingreso' => $request->fecha_ingreso,
            'fecha_egreso' => $request->fecha_egreso,
            'observacion' => $request->observacion,
        ]);

        return redirect()->back()->with('success', 'Período laboral registrado con éxito.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fecha_ingreso' => 'required|date',
            'fecha_egreso' => 'nullable|date',
            'observacion' => 'nullable|string|max:255'
        ]);

        $periodo = PeriodoLaboral::findOrFail($id);
        $periodo->update($request->only('fecha_ingreso', 'fecha_egreso', 'observacion'));

        return redirect()->back()->with('success', 'Período modificado correctamente.');
    }

    public function destroy($id)
    {
        $periodo = PeriodoLaboral::findOrFail($id);
        $periodo->delete();

        return redirect()->back()->with('success', 'Período eliminado del historial.');
    }
}