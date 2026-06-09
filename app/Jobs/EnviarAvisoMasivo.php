<?php

namespace App\Jobs;

use App\Mail\AvisoMasivo;
use App\Models\Aviso;
use App\Models\Inscripcion;
use App\Models\GradoCurso;
use App\Models\Ciclo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EnviarAvisoMasivo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Aviso $aviso)
    {
    }

    public function handle(): void
    {
        $aviso = $this->aviso;
        $aviso->update(['estado' => 'procesando']);

        // 1. Determinar los grado_curso IDs según destino_tipo
        $gradoCursoIds = match ($aviso->destino_tipo) {
            'colegio_completo' => GradoCurso::pluck('id'),
            'ciclo' => GradoCurso::where('ciclo_id', $aviso->destino_id)->pluck('id'),
            'grado_curso' => collect([$aviso->destino_id]),
        };

        // 2. Obtener inscripciones activas del año actual para esos grados
        $anio = now()->year;
        $inscripciones = Inscripcion::with(['alumno.madre', 'alumno.padre', 'alumno.encargado'])
            ->whereIn('grado_curso_id', $gradoCursoIds)
            ->where('anio', $anio)
            ->where('estado', 'activo')
            ->get();

        // 3. Recolectar emails únicos de responsables
        $emails = collect();
        foreach ($inscripciones as $inscripcion) {
            $alumno = $inscripcion->alumno;
            if (!$alumno)
                continue;

            foreach ($alumno->getResponsablesEmails() as $email) {
                if ($email)
                    $emails->push($email);
            }
        }

        $emailsUnicos = $emails->unique()->values();
        $enviados = 0;

        // 4. Enviar un mail por cada responsable único
        foreach ($emailsUnicos as $email) {
            try {
                Mail::to($email)->queue(new AvisoMasivo($aviso));
                $enviados++;
            } catch (\Exception $e) {
                Log::error("AvisoMasivo: error enviando a {$email} — " . $e->getMessage());
            }
        }

        $aviso->update([
            'estado' => 'enviado',
            'total_enviados' => $enviados,
        ]);
    }

    public function failed(\Throwable $e): void
    {
        $this->aviso->update(['estado' => 'error']);
        Log::error('Job EnviarAvisoMasivo falló: ' . $e->getMessage());
    }
}