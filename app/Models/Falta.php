<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Falta extends Model
{
    protected $table = 'faltas';

    protected $fillable = [
        'fecha',
        'grado_curso_id',
        'alumno_id',
        'asignatura_id',
        'indicador_falta_id',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function gradoCurso()
    {
        return $this->belongsTo(GradoCurso::class, 'grado_curso_id');
    }

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'asignatura_id');
    }

    public function indicadorFalta()
    {
        return $this->belongsTo(IndicadoresFaltas::class, 'indicador_falta_id');
    }

    /**
     * Obtiene el colaborador asignado a esta asignatura+grado_curso
     * desde la tabla asignaturas_colaboradores (sin guardar colaborador_id).
     */
    public function colaborador()
    {
        return $this->hasOneThrough(
            Colaborador::class,
            AsignaturaColaborador::class,
            // FK en asignaturas_colaboradores hacia este modelo:
            // no hay FK directa, así que usamos un accessor en su lugar.
            // Ver: getColaboradorAttribute()
        );
    }

    /**
     * Accessor: devuelve el colaborador asignado a esta asignatura en este grado.
     */
    public function getColaboradorAttribute()
    {
        $reg = AsignaturaColaborador::where('asignatura_id', $this->asignatura_id)
            ->where('grado_curso_id', $this->grado_curso_id)
            ->first();

        return $reg?->colaborador;
    }
}
