<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeriodoLaboral extends Model
{
    protected $table = 'periodos_laborales';

    protected $fillable = [
        'colaborador_id',
        'fecha_ingreso',
        'fecha_egreso',
        'observacion',
    ];

    // Cada período pertenece a un colaborador único
    public function colaborador(): BelongsTo
    {
        return $this->belongsTo(Colaborador::class, 'colaborador_id');
    }

    /**
     * Calcula la antigüedad en años y meses.
     */
    public function getAntiguedadAttribute(): string
    {
        $inicio = \Carbon\Carbon::parse($this->fecha_ingreso);
        $fin = $this->fecha_egreso ? \Carbon\Carbon::parse($this->fecha_egreso) : \Carbon\Carbon::now();

        $diff = $inicio->diff($fin);

        $anios = $diff->y;
        $meses = $diff->m;

        $res = [];
        if ($anios > 0)
            $res[] = "$anios " . ($anios == 1 ? 'año' : 'años');
        if ($meses > 0)
            $res[] = "$meses " . ($meses == 1 ? 'mes' : 'meses');

        return count($res) > 0 ? implode(', ', $res) : '0 meses';
    }
}