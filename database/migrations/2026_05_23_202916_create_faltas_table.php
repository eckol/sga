<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('faltas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->unsignedBigInteger('grado_curso_id');
            $table->unsignedBigInteger('alumno_id');
            $table->unsignedBigInteger('asignatura_id');
            $table->unsignedBigInteger('indicador_falta_id');
            $table->timestamps();

            $table->foreign('grado_curso_id')->references('id')->on('grados_cursos')->onDelete('cascade');
            $table->foreign('alumno_id')->references('id')->on('alumnos')->onDelete('cascade');
            $table->foreign('asignatura_id')->references('id')->on('asignaturas')->onDelete('cascade');
            $table->foreign('indicador_falta_id')->references('id')->on('indicadores_faltas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faltas');
    }
};
