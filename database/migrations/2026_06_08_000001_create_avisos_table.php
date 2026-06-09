<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('avisos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('titulo');
            $table->text('mensaje')->nullable();
            $table->string('archivo_adjunto')->nullable(); // ruta relativa en storage
            $table->enum('destino_tipo', ['colegio_completo', 'ciclo', 'grado_curso']);
            $table->unsignedBigInteger('destino_id')->nullable(); // ciclo_id o grado_curso_id según destino_tipo
            $table->foreignId('colaborador_id')->nullable()->constrained('colaboradores');
            $table->enum('estado', ['pendiente', 'procesando', 'enviado', 'error'])->default('pendiente');
            $table->unsignedInteger('total_enviados')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avisos');
    }
};