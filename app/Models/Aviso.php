<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aviso extends Model
{
    protected $fillable = [
        'fecha',
        'titulo',
        'mensaje',
        'archivo_adjunto',
        'destino_tipo',
        'destino_id',
        'colaborador_id',
        'estado',
        'total_enviados',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class);
    }

    /**
     * Devuelve el label legible del destino para mostrar en la vista.
     */
    public function getDestinoLabelAttribute(): string
    {
        return match ($this->destino_tipo) {
            'colegio_completo' => 'Todo el Colegio',
            'ciclo' => optional(Ciclo::find($this->destino_id))->ciclo ?? 'Ciclo desconocido',
            'grado_curso' => optional(GradoCurso::find($this->destino_id))->gradocurso ?? 'Grado desconocido',
            default => '-',
        };
    }
}