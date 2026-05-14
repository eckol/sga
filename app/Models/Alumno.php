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
        'matriculado',
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
}