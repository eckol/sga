<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles'; // Fuerza a usar la tabla en plural
    protected $fillable = ['rol'];
}
