<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoColaborador extends Model
{
    protected $table = 'tipos_colaboradores';
    protected $fillable = [
        'tipo_colaborador',
    ];
}
