<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentoController extends Controller
{
    /**
     * Procesa y sube los documentos desde el formulario
     */
    public function store(Request $request, Alumno $alumno)
    {
        // Validar que lo que venga en el array sean imágenes jpg/jpeg
        $request->validate([
            'documentos.*' => 'nullable|image|mimes:jpeg,jpg|max:3072',
        ]);

        $guardados = 0;

        if ($request->hasFile('documentos')) {
            foreach ($request->file('documentos') as $tipo_documento_id => $file) {

                // Definir el nombre interno con tu nomenclatura: "cedula_tipo.jpg"
                $prefijo = $alumno->cid ?? 'alumno_' . $alumno->id;
                $nombreFisico = $prefijo . '_id' . $tipo_documento_id . '.' . $file->getClientOriginalExtension();

                // Guardar en storage/app/private/documentos con el nombre formateado
                $rutaDestino = $file->storeAs('documentos', $nombreFisico);

                // Guardar o actualizar el registro en la BD (por si vuelven a subir el mismo tipo)
                $alumno->documentos()->updateOrCreate(
                    ['tipo_documento_id' => $tipo_documento_id],
                    [
                        'nombre_original' => $file->getClientOriginalName(),
                        'ruta_almacenamiento' => $rutaDestino,
                    ]
                );

                $guardados++;
            }
        }

        // Si la petición es AJAX, devolver JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Se guardaron {$guardados} documento(s).",
                'documentos' => $alumno->fresh()->documentos->map(fn($d) => [
                    'id' => $d->id,
                    'tipo_documento_id' => $d->tipo_documento_id,
                    'nombre_original' => $d->nombre_original,
                ]),
            ]);
        }

        return redirect()->back()->with('success', 'Documentación actualizada.');
    }

    /**
     * Descarga o muestra el archivo de forma segura comprobando autenticación
     */
    public function show(Documento $documento): StreamedResponse
    {
        if (!Storage::exists($documento->ruta_almacenamiento)) {
            abort(404, 'El archivo físico no existe.');
        }

        // Retorna el archivo directamente al navegador de forma segura
        return Storage::response($documento->ruta_almacenamiento);
    }
}
