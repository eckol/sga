<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('periodos_laborales', function (Blueprint $table) {
            $table->id();
            // Conexión física con la tabla colaboradores usando integridad referencial
            $table->foreignId('colaborador_id')->constrained('colaboradores')->onDelete('cascade');
            $table->date('fecha_ingreso');
            $table->date('fecha_egreso')->nullable(); // NULL si continúa activo en la institución
            $table->string('observacion', 255)->nullable();
            $table->timestamps(); // Activados por defecto para consistencia
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periodos_laborales');
    }
};