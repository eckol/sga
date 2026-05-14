<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arancel extends Model
{
    protected $table = 'aranceles';

    protected $fillable = [
        'anio_lect',
        'ciclo_id',
        'monto_matricula',
        'monto_anualidad'
    ];

    public function ciclo()
    {
        return $this->belongsTo(Ciclo::class);
    }
}
