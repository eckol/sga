<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gc extends Model
{
    protected $table = 'gc';

    // Tip de socio: esto te servirá luego para "estirar" el nombre del ciclo
    public function ciclo()
    {
        return $this->belongsTo(Ciclo::class);
    }
}
