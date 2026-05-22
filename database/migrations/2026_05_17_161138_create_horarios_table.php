<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('dia'); // 1 al 5
            $table->foreignId('hora_id')->constrained('horas')->onDelete('cascade');
            $table->foreignId('grado_curso_id')->constrained('grados_cursos')->onDelete('cascade');
            $table->foreignId('asignatura_id')->nullable()->constrained('asignaturas')->onDelete('set null');
            $table->timestamps();

            // Evitar duplicados: un grado no puede tener dos asignaturas en el mismo día y hora
            $table->unique(['dia', 'hora_id', 'grado_curso_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
