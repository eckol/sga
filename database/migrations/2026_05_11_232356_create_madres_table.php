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
        Schema::create('madres', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('cid')->unique();
            $table->string('profesion')->nullable();
            $table->string('direccion');
            $table->string('barrio')->nullable(); // Detectado en el CSV
            $table->foreignId('ciudad_id')->constrained('ciudades');
            $table->string('telefono1');
            $table->string('telefono2')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('lugartrabajo')->nullable();
            $table->string('ruc')->nullable();
            $table->char('dv', 1)->nullable();
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('madres');
    }
};
