<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistroAnecdotico extends Model
{
    protected $table = 'registros_anecdoticos';

    protected $fillable = [
        'fecha',
        'alumno_id',
        'asignatura_id',
        'grado_curso_id',
        'detalle'
    ];

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function asignatura(): BelongsTo
    {
        return $this->belongsTo(Asignatura::class);
    }

    public function gradoCurso(): BelongsTo
    {
        return $this->belongsTo(GradoCurso::class, 'grado_curso_id');
    }
}