<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Madre extends Model
{
    // Forzamos el nombre de la tabla para evitar problemas de pluralización
    protected $table = 'madres';

    protected $fillable = [
        'nombre',
        'cid',
        'profesion',
        'direccion',
        'barrio',
        'ciudad_id',
        'telefono1',
        'telefono2',
        'email',
        'lugartrabajo',
        'ruc',
        'dv',
        'user_id'
    ];

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}