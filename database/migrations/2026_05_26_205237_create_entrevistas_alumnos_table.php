<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('entrevistas_alumnos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');
            // Quién entrevista (Orientadora, Directora, etc.)
            $table->foreignId('colaborador_id')->constrained('colaboradores')->onDelete('cascade');
            $table->string('motivo');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entrevistas_alumnos');
    }
};