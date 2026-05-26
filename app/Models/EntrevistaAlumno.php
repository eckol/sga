<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntrevistaAlumno extends Model
{
    protected $table = 'entrevistas_alumnos';

    protected $fillable = [
        'fecha',
        'alumno_id',
        'colaborador_id',
        'motivo',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function entrevistador(): BelongsTo
    {
        return $this->belongsTo(Colaborador::class, 'colaborador_id');
    }
}