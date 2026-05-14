<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('grados_cursos', function (Blueprint $table) {
            $table->id();
            $table->string('gradocurso');
            $table->string('turno', 1); // M o T
            $table->foreignId('ciclo_id')->constrained('ciclos');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grados_cursos');
    }
};
