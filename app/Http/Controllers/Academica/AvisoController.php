<?php

namespace App\Http\Controllers\Academica;

use App\Http\Controllers\Controller;
use App\Jobs\EnviarAvisoMasivo;
use App\Models\Aviso;
use App\Models\Ciclo;
use App\Models\Colaborador;
use App\Models\GradoCurso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AvisoController extends Controller
{
    private array $rolesPermitidos = ['admin', 'directivo', 'profeguia', 'secretaria', 'administracion'];

    private function verificarRol(): void
    {
        // User->rol() devuelve el modelo Rol; accedemos al nombre del rol
        $nombreRol = optional(Auth::user()->rol)->rol ?? null;
        if (!in_array($nombreRol, $this->rolesPermitidos)) {
            abort(403, 'No tiene permisos para gestionar avisos.');
        }
    }

    /**
     * Busca el Colaborador cuyo email_institucional coincide con el User logueado.
     * Si no existe (ej: cuenta admin pura), retorna null y el aviso queda sin colaborador.
     */
    private function resolverColaborador(): ?Colaborador
    {
        return Colaborador::where('email_institucional', Auth::user()->email)->first();
    }

    public function index()
    {
        $this->verificarRol();

        $avisos = Aviso::with('colaborador')
            ->orderByDesc('fecha')
            ->orderByDesc('created_at')
            ->paginate(20);

        $ciclos = Ciclo::orderBy('id')->get();
        $gradosCursos = GradoCurso::orderBy('ciclo_id')->orderBy('gradocurso')->get();

        return view('academica.avisos.index', compact('avisos', 'ciclos', 'gradosCursos'));
    }

    public function store(Request $request)
    {
        $this->verificarRol();

        $request->validate([
            'fecha' => 'required|date',
            'titulo' => 'required|string|max:255',
            'mensaje' => 'nullable|string',
            'archivo_adjunto' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf|max:5120',
            'destino_tipo' => 'required|in:colegio_completo,ciclo,grado_curso',
            'destino_id' => 'nullable|required_unless:destino_tipo,colegio_completo|integer',
        ]);

        // Subir archivo si existe
        $rutaArchivo = null;
        if ($request->hasFile('archivo_adjunto')) {
            $rutaArchivo = $request->file('archivo_adjunto')
                ->store('comunicados', 'public');
        }

        // Resolver colaborador_id buscando por email_institucional
        $colaborador = $this->resolverColaborador();
        $colaboradorId = optional($colaborador)->id;

        $aviso = Aviso::create([
            'fecha' => $request->fecha,
            'titulo' => $request->titulo,
            'mensaje' => $request->mensaje,
            'archivo_adjunto' => $rutaArchivo,
            'destino_tipo' => $request->destino_tipo,
            'destino_id' => $request->destino_tipo === 'colegio_completo' ? null : $request->destino_id,
            'colaborador_id' => $colaboradorId,
            'estado' => 'pendiente',
        ]);

        // Despachar job a la cola
        EnviarAvisoMasivo::dispatch($aviso);

        return response()->json([
            'success' => true,
            'message' => 'Aviso creado. Los correos se están enviando en segundo plano.',
        ]);
    }

    public function show(Aviso $aviso)
    {
        $this->verificarRol();

        return response()->json([
            'aviso' => $aviso,
            'destino_label' => $aviso->destino_label,
            'colaborador' => $aviso->colaborador
                ? trim($aviso->colaborador->apellidos . ', ' . $aviso->colaborador->nombres)
                : 'Desconocido',
            'archivo_url' => $aviso->archivo_adjunto
                ? Storage::url($aviso->archivo_adjunto)
                : null,
        ]);
    }
}