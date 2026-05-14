<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradoCurso extends Model
{
    protected $table = 'grados_cursos';

    protected $fillable = ['gradocurso', 'turno', 'ciclo_id'];

    public function ciclo()
    {
        return $this->belongsTo(Ciclo::class);
    }
}