<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('registros_anecdoticos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->foreignId('alumno_id')->constrained('alumnos');
            $table->foreignId('asignatura_id')->constrained('asignaturas');
            $table->foreignId('grado_curso_id')->constrained('grados_cursos');
            $table->text('detalle');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registros_anecdoticos');
    }
};
