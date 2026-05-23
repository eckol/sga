<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignaturaColaborador extends Model
{
    protected $table = 'asignaturas_colaboradores';

    protected $fillable = [
        'asignatura_id',
        'grado_curso_id',
        'colaborador_id',
    ];

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'asignatura_id');
    }

    public function gradoCurso()
    {
        return $this->belongsTo(GradoCurso::class, 'grado_curso_id');
    }

    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class, 'colaborador_id');
    }
}
