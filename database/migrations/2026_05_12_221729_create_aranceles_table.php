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
        Schema::create('aranceles', function (Blueprint $table) {
            $table->id();
            $table->integer('anio_lect'); // Año lectivo
            $table->foreignId('ciclo_id')->constrained('ciclos'); // Relación con ciclos
            $table->bigInteger('monto_matricula');
            $table->bigInteger('monto_anualidad');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aranceles');
    }
};
