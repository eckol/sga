<?php

namespace App\Http\Controllers;

use App\Models\Colaborador;
use App\Models\Nacionalidad;
use App\Models\Sexo;
use App\Models\EstadoCivil;
use App\Models\Ciudad;
use App\Models\TipoColaborador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ColaboradorController extends Controller
{
    public function index()
    {
        $colaboradores = Colaborador::with(['nacionalidad', 'sexo', 'estadoCivil', 'ciudad', 'tipoColaborador'])->get();
        $nacionalidades = Nacionalidad::all();
        $sexos = Sexo::all();
        $estadosciviles = EstadoCivil::all();
        $ciudades = Ciudad::all();
        $tipos = TipoColaborador::all();

        return view('rrhh.colaboradores.index', compact('colaboradores', 'nacionalidades', 'sexos', 'estadosciviles', 'ciudades', 'tipos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'apellidos' => 'required',
            'nombres' => 'required',
            'cid' => 'required|unique:colaboradores,cid',
            'fnac' => 'required|date',
            'nacionalidad_id' => 'required',
            'sexo_id' => 'required',
            'estado_civil_id' => 'required',
            'direccion' => 'required',
            'barrio' => 'nullable',
            'ciudad_id' => 'required',
            'ubicacion' => 'nullable',
            'telefono' => 'nullable',
            'email_particular' => 'nullable|email',
            'email_institucional' => 'nullable|email',
            'passwd' => 'nullable',
            'activo' => 'required',
            'tipo_colaborador_id' => 'required',
            'titulo1' => 'nullable',
            'titulo2' => 'nullable',
            'titulo3' => 'nullable',
            'anios_servicio' => 'nullable|integer',
            'seguro' => 'nullable',
            'gsangre' => 'nullable',
            'enf_cronica' => 'required',
            'reloj' => 'nullable',
            'passwd_mec' => 'nullable',
            'observaciones' => 'nullable',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $fileName = $data['cid'] . '.' . $request->file('foto')->getClientOriginalExtension();
            $request->file('foto')->move(public_path('img/colaboradores'), $fileName);
            $data['foto'] = $fileName;
        }

        Colaborador::create($data);

        return redirect()->route('colaboradores.index')->with('success', 'Colaborador creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $colaborador = Colaborador::findOrFail($id);

        $data = $request->validate([
            'apellidos' => 'required',
            'nombres' => 'required',
            'cid' => 'required|unique:colaboradores,cid,' . $id,
            'fnac' => 'required|date',
            'nacionalidad_id' => 'required',
            'sexo_id' => 'required',
            'estado_civil_id' => 'required',
            'direccion' => 'required',
            'barrio' => 'nullable',
            'ciudad_id' => 'required',
            'ubicacion' => 'nullable',
            'telefono' => 'nullable',
            'email_particular' => 'nullable|email',
            'email_institucional' => 'nullable|email',
            'passwd' => 'nullable',
            'activo' => 'required',
            'tipo_colaborador_id' => 'required',
            'titulo1' => 'nullable',
            'titulo2' => 'nullable',
            'titulo3' => 'nullable',
            'anios_servicio' => 'nullable|integer',
            'seguro' => 'nullable',
            'gsangre' => 'nullable',
            'enf_cronica' => 'required',
            'reloj' => 'nullable',
            'passwd_mec' => 'nullable',
            'observaciones' => 'nullable',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // Borrar foto vieja si existe
            if ($colaborador->foto && file_exists(public_path('img/colaboradores/' . $colaborador->foto))) {
                @unlink(public_path('img/colaboradores/' . $colaborador->foto));
            }
            $fileName = $data['cid'] . '.' . $request->file('foto')->getClientOriginalExtension();
            $request->file('foto')->move(public_path('img/colaboradores'), $fileName);
            $data['foto'] = $fileName;
        }

        $colaborador->update($data);

        return redirect()->route('colaboradores.index')->with('success', 'Colaborador actualizado correctamente.');
    }

    public function destroy($id)
    {
        $colaborador = Colaborador::findOrFail($id);
        if ($colaborador->foto && file_exists(public_path('img/colaboradores/' . $colaborador->foto))) {
            @unlink(public_path('img/colaboradores/' . $colaborador->foto));
        }
        $colaborador->delete();

        return redirect()->route('colaboradores.index')->with('success', 'Colaborador eliminado correctamente.');
    }
}
