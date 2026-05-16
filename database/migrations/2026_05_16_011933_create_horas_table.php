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
        Schema::create('horas', function (Blueprint $table) {
            $table->id();
            $table->string('modulo', 10); // Para "1ra.", "2da.", etc.
            $table->time('hora_inicio');  // Guarda hh:mm:ss
            $table->time('hora_fin');     // Guarda hh:mm:ss
            $table->timestamps();         // Laravel 12 los mantiene por defecto
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horas');
    }
};
