<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = 'horarios';

    protected $fillable = [
        'dia',
        'hora_id',
        'grado_curso_id',
        'asignatura_id',
    ];

    public function hora()
    {
        return $this->belongsTo(Hora::class);
    }

    public function gradoCurso()
    {
        return $this->belongsTo(GradoCurso::class);
    }

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }
}