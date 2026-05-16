<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hora extends Model
{
    protected $table = 'horas';

    protected $fillable = [
        'modulo',
        'hora_inicio',
        'hora_fin',
    ];
}