<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Alumno extends Model
{
    protected $table = 'alumnos';

    protected $fillable = [
        'apellidos',
        'nombres',
        'nacionalidad_id',
        'cid',
        'fnac',
        'sexo_id',
        'direccion',
        'barrio',
        'ciudad_id',
        'gmaps',
        'telefono',
        'email',
        'passwd',
        'activo',
        'vivecon_id',
        'salud',
        'foto',
        'observaciones',
        'cid_madre',
        'cid_padre',
        'cid_encargado',
        'parentesco_id'
    ];

    // ----------------------------------------------------------------
    // Relaciones para el Index, Formularios y Reportes
    // ----------------------------------------------------------------

    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class);
    }

    public function nacionalidad(): BelongsTo
    {
        return $this->belongsTo(Nacionalidad::class);
    }

    public function sexo(): BelongsTo
    {
        return $this->belongsTo(Sexo::class);
    }

    public function vivecon(): BelongsTo
    {
        return $this->belongsTo(ViveCon::class, 'vivecon_id', 'id');
    }

    public function parentesco(): BelongsTo
    {
        return $this->belongsTo(Parentesco::class);
    }

    // ----------------------------------------------------------------
    // Relación con Inscripciones e Historial Académico
    // ----------------------------------------------------------------

    public function inscripciones(): HasMany
    {
        // Un alumno tiene muchas inscripciones, vinculadas por el CID (String)
        return $this->hasMany(Inscripcion::class, 'alumno_cid', 'cid');
    }

    // ----------------------------------------------------------------
    // Relaciones con los Responsables Familiares
    // ----------------------------------------------------------------

    public function madre(): BelongsTo
    {
        return $this->belongsTo(Madre::class, 'cid_madre', 'cid');
    }

    public function padre(): BelongsTo
    {
        return $this->belongsTo(Padre::class, 'cid_padre', 'cid');
    }

    public function encargado(): BelongsTo
    {
        return $this->belongsTo(Encargado::class, 'cid_encargado', 'cid');
    }

    // ----------------------------------------------------------------
    // Relación con Incidentes, Disciplina y Asistencia
    // ----------------------------------------------------------------

    public function faltas(): HasMany
    {
        return $this->hasMany(Falta::class, 'alumno_id', 'id');
    }

    /**
     * Asistencias del alumno a través de sus inscripciones.
     * Uso: $alumno->asistencias
     */
    public function asistencias(): HasManyThrough
    {
        return $this->hasManyThrough(
            \App\Models\Asistencia::class,  // Modelo final
            \App\Models\Inscripcion::class, // Modelo intermedio
            'alumno_cid',                   // FK en inscripciones -> alumnos.cid
            'inscripcion_id',               // FK en asistencias   -> inscripciones.id
            'cid',                          // Local key en alumnos
            'id'                            // Local key en inscripciones
        );
    }

    // ----------------------------------------------------------------
    // NUEVAS RELACIONES: Bitácora de Orientación y Entrevistas
    // ----------------------------------------------------------------

    /**
     * Obtiene el historial de entrevistas directas que ha tenido el alumno.
     * Uso: $alumno->entrevistasAlumnos
     */
    public function entrevistasAlumnos(): HasMany
    {
        return $this->hasMany(EntrevistaAlumno::class, 'alumno_id', 'id');
    }

    /**
     * Obtiene las actas de entrevistas realizadas con los responsables de este alumno.
     * Uso: $alumno->entrevistasResponsables
     */
    public function entrevistasResponsables(): HasMany
    {
        return $this->hasMany(EntrevistaResponsable::class, 'alumno_id', 'id');
    }

    public function registrosAnecdoticos()
    {
        return $this->hasMany(RegistroAnecdotico::class);
    }
}