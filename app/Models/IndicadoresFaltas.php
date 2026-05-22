<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndicadoresFaltas extends Model
{
    protected $table = 'indicadores_faltas';

    // Lista de campos que se pueden llenar masivamente
    protected $fillable = ['tipo_falta'];
}
