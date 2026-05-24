<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table = 'asistencias';

    protected $fillable = [
        'inscripcion_id',
        'fecha',
        'estado',
        'observacion',
        'registrado_por',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // ── Relaciones ──────────────────────────────────────────────────

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class);
    }

    public function registradoPor()
    {
        return $this->belongsTo(\App\Models\User::class, 'registrado_por');
    }

    // ── Helpers ──────────────────────────────────────────────────────

    /**
     * Clases Bootstrap asociadas a cada estado (para badges en vistas).
     */
    public static function badgeClass(string $estado): string
    {
        return match ($estado) {
            'Presente' => 'success',
            'Ausente' => 'danger',
            'Justificado' => 'warning',
            'Tardanza' => 'info',
            default => 'secondary',
        };
    }
}