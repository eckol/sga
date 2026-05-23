<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asignaturas_colaboradores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asignatura_id');
            $table->unsignedBigInteger('grado_curso_id');
            $table->unsignedBigInteger('colaborador_id');
            $table->timestamps();

            $table->foreign('asignatura_id')->references('id')->on('asignaturas')->onDelete('cascade');
            $table->foreign('grado_curso_id')->references('id')->on('grados_cursos')->onDelete('cascade');
            $table->foreign('colaborador_id')->references('id')->on('colaboradores')->onDelete('cascade');

            // Un colaborador es único por asignatura+grado_curso
            $table->unique(['asignatura_id', 'grado_curso_id'], 'uq_asig_grado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignaturas_colaboradores');
    }
};
