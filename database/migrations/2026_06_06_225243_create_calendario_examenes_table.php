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
        Schema::create('calendario_examenes', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('etapa');
            $table->string('tipo_prueba');
            $table->foreignId('grado_curso_id')->constrained('grados_cursos');
            $table->foreignId('asignatura1')->nullable()->constrained('asignaturas');
            $table->foreignId('asignatura2')->nullable()->constrained('asignaturas');
            $table->foreignId('asignatura3')->nullable()->constrained('asignaturas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendario_examenes');
    }
}
;
