<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id();
            $table->string('apellidos');
            $table->string('nombres');
            $table->foreignId('nacionalidad_id')->constrained('nacionalidades');
            $table->integer('cid')->unique();
            $table->date('fnac');
            $table->foreignId('sexo_id')->constrained('sexos');
            $table->string('direccion');
            $table->string('barrio')->nullable();
            $table->foreignId('ciudad_id')->constrained('ciudades');
            $table->text('gmaps')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('passwd', 8)->nullable();
            $table->string('activo', 2)->default('Sí');
            $table->string('matriculado', 2)->default('No');
            $table->foreignId('vivecon_id')->constrained('vivecon');
            $table->text('salud')->nullable();
            $table->string('foto')->nullable();
            $table->text('observaciones')->nullable();
            $table->integer('cid_madre')->nullable();
            $table->integer('cid_padre')->nullable();
            $table->integer('cid_encargado')->nullable();
            $table->foreignId('parentesco_id')->nullable()->constrained('parentescos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};
