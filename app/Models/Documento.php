<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Documento extends Model
{
    protected $fillable = [
        'alumno_id',
        'tipo_documento_id',
        'nombre_original',
        'ruta_almacenamiento',
    ];

    // Relación inversa: Un documento pertenece a un alumno
    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    // Relación para saber el tipo de documento de forma simple
    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id');
    }
}