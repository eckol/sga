<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Padre;
use App\Models\Madre;
use App\Models\Encargado;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ResponsableController extends Controller
{
    // Función privada para saber con qué modelo trabajar según la ruta
    private function getModel($tipo)
    {
        return match ($tipo) {
            'padres' => new Padre(),
            'madres' => new Madre(),
            'encargados' => new Encargado(),
            default => abort(404),
        };
    }

    public function index($tipo)
    {
        $modelo = $this->getModel($tipo);
        // Cargamos la relación de usuario y ordenamos por nombre
        $registros = $modelo::with(['user', 'ciudad'])->orderBy('nombre')->get();

        // Necesitamos las ciudades para el formulario de edición/creación
        $ciudades = \App\Models\Ciudad::orderBy('ciudad')->get();
        $rolResponsable = Rol::where('rol', 'responsable')->first();

        return view("rrhh.responsables.index", [
            'registros' => $registros,
            'tipo' => $tipo,
            'ciudades' => $ciudades,
            'rol_id' => $rolResponsable->id ?? 6
        ]);
    }

    public function store(Request $request, $tipo)
    {
        $modelo = $this->getModel($tipo);

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'cid' => "required|unique:{$tipo},cid",
            'profesion' => 'required',
            'direccion' => 'required',
            'barrio' => 'required',
            'ciudad_id' => 'required|exists:ciudades,id',
            'telefono1' => 'required',
            'telefono2' => 'nullable',
            'email' => 'nullable|email|unique:users,email',
            'lugartrabajo' => 'nullable',
            'ruc' => 'nullable',
            'dv' => 'nullable',
            // Agrega aquí el resto de tus campos (ruc, dv, profesion, etc.)
        ]);

        try {
            DB::beginTransaction();

            // 1. Crear Usuario si tiene email
            if ($request->email) {
                $user = User::create([
                    'name' => $request->nombre,
                    'email' => $request->email,
                    'password' => Hash::make($request->cid), // Clave inicial: Cedula
                    'role_id' => $request->rol_id,
                ]);
                $data['user_id'] = $user->id;
            }

            // 2. Crear el registro en la tabla correspondiente (padres, madres o encargados)
            $modelo::create($data);

            DB::commit();
            return redirect()->back()->with('success', 'Registro creado con éxito.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al procesar: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $tipo, $id)
    {
        $modelo = $this->getModel($tipo);
        $registro = $modelo::findOrFail($id);

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'cid' => "required|unique:{$tipo},cid,{$id}",
            'profesion' => 'nullable',
            'direccion' => 'required',
            'barrio' => 'nullable',
            'ciudad_id' => 'required|exists:ciudades,id',
            'telefono1' => 'required',
            'telefono2' => 'nullable',
            'email' => 'nullable|email',
            'lugartrabajo' => 'nullable',
            'ruc' => 'nullable',
            'dv' => 'nullable',
        ]);

        try {
            DB::beginTransaction();

            // 1. Actualizar o crear usuario si cambió el email
            if ($request->email) {
                if ($registro->user_id) {
                    $registro->user->update([
                        'name' => $request->nombre,
                        'email' => $request->email,
                    ]);
                } else {
                    // Si no tenía usuario y ahora le ponemos email, se lo creamos
                    $user = \App\Models\User::create([
                        'name' => $request->nombre,
                        'email' => $request->email,
                        'password' => \Illuminate\Support\Facades\Hash::make($request->cid),
                        'role_id' => $request->rol_id ?? 6,
                    ]);
                    $data['user_id'] = $user->id;
                }
            }

            // 2. Actualizar el registro (Madre, Padre o Encargado)
            $registro->update($data);

            DB::commit();
            return redirect()->back()->with('success', 'Registro actualizado con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function destroy($tipo, $id)
    {
        $modelo = $this->getModel($tipo);
        $registro = $modelo::findOrFail($id);

        try {
            // Nota: Si borramos al responsable, el usuario queda (por si tiene otros roles)
            // o podrías borrarlo también si quisieras. Por ahora solo borramos el registro.
            $registro->delete();
            return redirect()->back()->with('success', 'Registro eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se puede eliminar: ' . $e->getMessage());
        }
    }
}