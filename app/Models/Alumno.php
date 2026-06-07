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

    /**
     * Relación HasManyThrough para traer las asistencias del alumno desde
     * sus inscripciones.
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

    /**
     * Relación con las inscripciones del alumno.
     * Requerida por EntrevistaController para filtrar alumnos matriculados.
     */
    public function inscripciones(): HasMany
    {
        return $this->hasMany(\App\Models\Inscripcion::class, 'alumno_cid', 'cid');
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

    public function registrosAnecdoticos(): HasMany
    {
        return $this->hasMany(RegistroAnecdotico::class);
    }

    public function faltas(): HasMany
    {
        return $this->hasMany(Falta::class, 'alumno_id', 'id');
    }

    // ----------------------------------------------------------------
    // RELACIONES CON RESPONSABLES (Madre, Padre, Encargado) Y EMAILS
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

    /**
     * Reúne todos los emails disponibles de los responsables de este alumno.
     */
    public function getResponsablesEmails(): array
    {
        $emails = [];

        if ($this->madre && $this->madre->user && $this->madre->user->email) {
            $emails[] = $this->madre->user->email;
        }
        if ($this->padre && $this->padre->user && $this->padre->user->email) {
            $emails[] = $this->padre->user->email;
        }
        if ($this->encargado && $this->encargado->user && $this->encargado->user->email) {
            $emails[] = $this->encargado->user->email;
        }

        // Respaldo secundario por si el correo está directamente en la tabla del responsable
        if (empty($emails)) {
            if ($this->madre && $this->madre->email) {
                $emails[] = $this->madre->email;
            }
            if ($this->padre && $this->padre->email) {
                $emails[] = $this->padre->email;
            }
            if ($this->encargado && $this->encargado->email) {
                $emails[] = $this->encargado->email;
            }
        }

        return array_unique(array_filter($emails));
    }
}