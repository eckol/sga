<?php

namespace App\Http\Controllers\Academica;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\GradoCurso;
use App\Models\Inscripcion;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    // ── Vista principal ──────────────────────────────────────────────

    public function index(Request $request)
    {
        $grados = GradoCurso::orderBy('id')->get();
        $anio_actual = (int) date('Y');

        // Parámetros del filtro (por defecto: primer grado, mes y año actuales)
        $selectedGradoId = $request->input('grado_id', $grados->first()?->id);
        $selectedMes = (int) $request->input('mes', date('n'));
        $selectedAnio = (int) $request->input('anio', $anio_actual);

        $selectedGrado = GradoCurso::find($selectedGradoId);

        // Inscripciones matriculadas para el grado y año seleccionados
        $inscripciones = Inscripcion::with(['alumno'])
            ->where('grado_curso_id', $selectedGradoId)
            ->where('anio_lectivo', $selectedAnio)
            ->where('estado', 'Matriculado')
            ->get()
            ->sortBy(fn($ins) => $ins->alumno?->apellidos . ' ' . $ins->alumno?->nombres)
            ->values();

        // Asistencias del mes para esas inscripciones
        $inscripcionIds = $inscripciones->pluck('id');
        $asistencias = Asistencia::whereIn('inscripcion_id', $inscripcionIds)
            ->whereYear('fecha', $selectedAnio)
            ->whereMonth('fecha', $selectedMes)
            ->get()
            ->groupBy('inscripcion_id')  // [ inscripcion_id => Collection ]
            ->map(fn($col) => $col->keyBy(fn($a) => Carbon::parse($a->fecha)->day));
        // Resultado: [ inscripcion_id => [ dia => Asistencia ] ]

        // Días hábiles del mes (lunes a viernes, sin feriados PY)
        $diasMes = Carbon::create($selectedAnio, $selectedMes)->daysInMonth;
        $feriadosPY = $this->feriadosParaguay($selectedAnio);

        $anios = range($anio_actual - 1, $anio_actual + 1);

        return view('academica.asistencia.index', compact(
            'grados',
            'selectedGradoId',
            'selectedGrado',
            'selectedMes',
            'selectedAnio',
            'inscripciones',
            'asistencias',
            'diasMes',
            'feriadosPY',
            'anios'
        ));
    }

    // ── Guardar / actualizar asistencia de un alumno ─────────────────

    /**
     * POST  academica/asistencias
     * Body: { inscripcion_id, fecha, estado, observacion? }
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'inscripcion_id' => 'required|exists:inscripciones,id',
            'fecha' => 'required|date',
            'estado' => 'required|in:Presente,Ausente,Justificado,Tardanza',
            'observacion' => 'nullable|string|max:255',
        ]);

        $asistencia = Asistencia::updateOrCreate(
            [
                'inscripcion_id' => $request->inscripcion_id,
                'fecha' => $request->fecha,
            ],
            [
                'estado' => $request->estado,
                'observacion' => $request->observacion,
                'registrado_por' => auth()->id(),
            ]
        );

        return response()->json([
            'success' => true,
            'asistencia' => $asistencia,
            'badge_class' => Asistencia::badgeClass($asistencia->estado),
        ]);
    }

    /**
     * POST  academica/asistencias/guardar-grilla
     * Guarda el mes completo de un grado en un solo request (upsert masivo).
     * Body: { registros: [ {inscripcion_id, fecha, estado}, ... ] }
     */
    public function guardarGrilla(Request $request): JsonResponse
    {
        $request->validate([
            'registros' => 'required|array',
            'registros.*.inscripcion_id' => 'required|exists:inscripciones,id',
            'registros.*.fecha' => 'required|date',
            'registros.*.estado' => 'required|in:Presente,Ausente,Justificado,Tardanza',
        ]);

        $userId = auth()->id();
        $now = now();

        $rows = collect($request->registros)->map(fn($r) => [
            'inscripcion_id' => $r['inscripcion_id'],
            'fecha' => $r['fecha'],
            'estado' => $r['estado'],
            'observacion' => $r['observacion'] ?? null,
            'registrado_por' => $userId,
            'created_at' => $now,
            'updated_at' => $now,
        ])->toArray();

        Asistencia::upsert(
            $rows,
            ['inscripcion_id', 'fecha'],        // columnas únicas
            ['estado', 'observacion', 'registrado_por', 'updated_at'] // columnas a actualizar
        );

        return response()->json(['success' => true, 'total' => count($rows)]);
    }

    // ── Detalle de asistencias de un alumno (para tab del modal) ─────

    /**
     * GET  academica/asistencias/{alumno_id}/por-alumno?mes=5&anio=2025
     */
    public function porAlumno(int $alumnoId, Request $request): JsonResponse
    {
        $mes = (int) $request->input('mes', date('n'));
        $anio = (int) $request->input('anio', date('Y'));

        // Buscamos la inscripción del alumno para el año solicitado
        $inscripcion = Inscripcion::where(
            'alumno_cid',
            \App\Models\Alumno::findOrFail($alumnoId)->cid
        )
            ->where('anio_lectivo', $anio)
            ->where('estado', 'Matriculado')
            ->first();

        if (!$inscripcion) {
            return response()->json(['asistencias' => [], 'resumen' => []]);
        }

        $asistencias = Asistencia::where('inscripcion_id', $inscripcion->id)
            ->whereYear('fecha', $anio)
            ->whereMonth('fecha', $mes)
            ->get()
            ->map(fn($a) => [
                'dia' => Carbon::parse($a->fecha)->day,
                'fecha' => Carbon::parse($a->fecha)->format('d/m/Y'),
                'estado' => $a->estado,
                'badge_class' => Asistencia::badgeClass($a->estado),
                'observacion' => $a->observacion,
            ]);

        // Resumen del año completo (para la vista de calendario anual)
        $resumenAnio = Asistencia::where('inscripcion_id', $inscripcion->id)
            ->whereYear('fecha', $anio)
            ->selectRaw('MONTH(fecha) as mes, estado, COUNT(*) as total')
            ->groupBy('mes', 'estado')
            ->get();

        return response()->json([
            'inscripcion_id' => $inscripcion->id,
            'asistencias' => $asistencias,
            'resumen_anio' => $resumenAnio,
        ]);
    }

    // ── Eliminar un registro ─────────────────────────────────────────

    public function destroy(int $id): JsonResponse
    {
        Asistencia::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    // ── Helper: feriados nacionales de Paraguay ──────────────────────

    private function feriadosParaguay(int $anio): array
    {
        return [
            "$anio-01-01", // Año Nuevo
            "$anio-03-01", // Día de los Héroes
            "$anio-05-01", // Día del Trabajador
            "$anio-05-14", // Independencia (víspera)
            "$anio-05-15", // Independencia
            "$anio-06-12", // Paz del Chaco
            "$anio-08-15", // Fundación de Asunción
            "$anio-09-29", // Victoria de Boquerón
            "$anio-12-08", // Virgen de Caacupé
            "$anio-12-25", // Navidad
        ];
        // Semana Santa varía cada año — se puede ampliar con Carbon::easter()
    }
}