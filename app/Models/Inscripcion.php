<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table = 'inscripciones';

    protected $fillable = [
        'fecha',
        'anio_lectivo',
        'alumno_cid',
        'grado_curso_id',
        'procede',
        'fpago',
        'firmante_nombre',
        'firmante_rol',
        'monto_matricula',
        'monto_anualidad',
        'aut_mochila',
        'aut_foto'
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_cid', 'cid');
    }
    public function grado()
    {
        return $this->belongsTo(GradoCurso::class, 'grado_curso_id');
    }
}