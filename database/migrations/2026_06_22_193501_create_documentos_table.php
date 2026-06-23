<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            // Relación con alumnos (asumiendo que tu tabla se llama 'alumnos')
            $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');
            // Relación con tipos_documentos
            $table->foreignId('tipo_documento_id')->constrained('tipos_documentos');

            $table->string('nombre_original'); // Ej: "foto_cedula_2026.jpg"
            $table->string('ruta_almacenamiento'); // Ej: "documentos/123456_cid.jpg"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};