<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colaborador extends Model
{
    protected $table = 'colaboradores';
    protected $fillable = [
        'apellidos',
        'nombres',
        'cid',
        'fnac',
        'nacionalidad_id',
        'sexo_id',
        'estado_civil_id',
        'direccion',
        'barrio',
        'ciudad_id',
        'ubicacion',
        'telefono',
        'email_particular',
        'email_institucional',
        'passwd',
        'activo',
        'tipo_colaborador_id',
        'titulo1',
        'titulo2',
        'titulo3',
        'anios_servicio',
        'seguro',
        'gsangre',
        'enf_cronica',
        'reloj',
        'passwd_mec',
        'observaciones',
        'foto',
    ];

    public function nacionalidad()
    {
        return $this->belongsTo(Nacionalidad::class);
    }

    public function sexo()
    {
        return $this->belongsTo(Sexo::class);
    }

    public function estadoCivil()
    {
        return $this->belongsTo(EstadoCivil::class, 'estado_civil_id');
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class);
    }

    public function tipoColaborador()
    {
        return $this->belongsTo(TipoColaborador::class, 'tipo_colaborador_id');
    }

    public function periodosLaborales()
    {
        return $this->hasMany(PeriodoLaboral::class, 'colaborador_id');
    }

    public function getEsActivoAttribute()
    {
        $ultimoPeriodo = $this->periodosLaborales->last();
        return $ultimoPeriodo && is_null($ultimoPeriodo->fecha_egreso);
    }
}
