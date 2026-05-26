<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    // Relaciones para el index y reportes
    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class);
    }
    public function nacionalidad()
    {
        return $this->belongsTo(Nacionalidad::class);
    }
    public function sexo()
    {
        return $this->belongsTo(Sexo::class);
    }
    public function vivecon()
    {
        return $this->belongsTo(ViveCon::class, 'vivecon_id', 'id');
    }
    // Relación con las inscripciones
    public function inscripciones()
    {
        // Un alumno tiene muchas inscripciones, vinculadas por el CID
        return $this->hasMany(Inscripcion::class, 'alumno_cid', 'cid');
    }

    // Relaciones con los responsables
    public function madre()
    {
        return $this->belongsTo(Madre::class, 'cid_madre', 'cid');
    }

    public function padre()
    {
        return $this->belongsTo(Padre::class, 'cid_padre', 'cid');
    }

    public function encargado()
    {
        return $this->belongsTo(Encargado::class, 'cid_encargado', 'cid');
    }

    // Relación con las faltas del alumno
    public function faltas()
    {
        return $this->hasMany(Falta::class, 'alumno_id', 'id');
    }

    /**
     * Asistencias del alumno a través de sus inscripciones.
     * Uso: $alumno->asistencias
     */
    public function asistencias()
    {
        return $this->hasManyThrough(
            \App\Models\Asistencia::class,  // Modelo final
            \App\Models\Inscripcion::class, // Modelo intermedio
            'alumno_cid',                   // FK en inscripciones → alumnos.cid
            'inscripcion_id',               // FK en asistencias   → inscripciones.id
            'cid',                          // PK local en alumnos
            'id'                            // PK en inscripciones
        );
    }


}