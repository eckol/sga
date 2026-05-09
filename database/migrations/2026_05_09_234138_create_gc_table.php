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
        Schema::create('gc', function (Blueprint $table) {
            $table->id();
            $table->string('grado_curso');
            $table->char('turno', 1); // 'M' o 'T'
            $table->foreignId('ciclo_id')->constrained('ciclos'); // Crea la relación oficial
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gcs');
    }
};
