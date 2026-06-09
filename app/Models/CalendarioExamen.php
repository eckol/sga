<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarioExamen extends Model
{
    protected $table = 'calendario_examenes';

    protected $fillable = [
        'fecha',
        'etapa',
        'tipo_prueba',
        'grado_curso_id',
        'asignatura1',
        'asignatura2',
        'asignatura3',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function grado_curso()
    {
        return $this->belongsTo(GradoCurso::class, 'grado_curso_id');
    }

    public function asignatura1_rel()
    {
        return $this->belongsTo(Asignatura::class, 'asignatura1');
    }

    public function asignatura2_rel()
    {
        return $this->belongsTo(Asignatura::class, 'asignatura2');
    }

    public function asignatura3_rel()
    {
        return $this->belongsTo(Asignatura::class, 'asignatura3');
    }
}

