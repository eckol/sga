<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EntrevistaResponsable extends Model
{
    protected $table = 'entrevistas_responsables';

    protected $fillable = [
        'fecha',
        'alumno_id',
        'colaborador_id',
        'madre_cid',
        'padre_cid',
        'encargado_cid',
        'parentesco_id',
        'motivo',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function entrevistador(): BelongsTo
    {
        return $this->belongsTo(Colaborador::class, 'colaborador_id');
    }

    // Relación Many-to-Many para los testigos
    public function testigos(): BelongsToMany
    {
        return $this->belongsToMany(
            Colaborador::class,
            'entrevista_responsable_testigo',
            'entrevista_res_id',
            'colaborador_id'
        )->withTimestamps();
    }

    // Conexiones con responsables basados en CID
    public function madre(): BelongsTo
    {
        return $this->belongsTo(Madre::class, 'madre_cid', 'cid');
    }
    public function padre(): BelongsTo
    {
        return $this->belongsTo(Padre::class, 'padre_cid', 'cid');
    }
    public function encargado(): BelongsTo
    {
        return $this->belongsTo(Encargado::class, 'encargado_cid', 'cid');
    }
    public function parentesco(): BelongsTo
    {
        return $this->belongsTo(Parentesco::class);
    }
}