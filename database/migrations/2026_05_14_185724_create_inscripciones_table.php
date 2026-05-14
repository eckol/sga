<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->integer('anio_lectivo');
            $table->integer('alumno_cid');
            $table->foreignId('grado_curso_id')->constrained('grados_cursos');
            $table->string('procede')->nullable();
            $table->string('fpago')->default('Mensual');

            // Datos del firmante para el histórico del contrato
            $table->string('firmante_nombre')->nullable();
            $table->string('firmante_rol')->nullable();

            // Montos capturados de la tabla aranceles
            $table->bigInteger('monto_matricula');
            $table->bigInteger('monto_anualidad');

            $table->string('aut_mochila', 2)->default('No');
            $table->string('aut_foto', 2)->default('No');

            $table->foreign('alumno_cid')->references('cid')->on('alumnos');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripciones');
    }
};