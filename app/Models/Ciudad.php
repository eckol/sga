<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    protected $table = 'ciudades';

    // Lista de campos que se pueden llenar masivamente
    protected $fillable = ['ciudad'];
}