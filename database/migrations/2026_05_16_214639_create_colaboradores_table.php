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
        Schema::create('colaboradores', function (Blueprint $table) {
            $table->id();
            $table->string('apellidos');
            $table->string('nombres');
            $table->integer('cid')->unique();
            $table->date('fnac');
            $table->foreignId('nacionalidad_id')->constrained('nacionalidades');
            $table->foreignId('sexo_id')->constrained('sexos');
            $table->foreignId('estado_civil_id')->constrained('estados_civiles');
            $table->string('direccion');
            $table->string('barrio')->nullable();
            $table->foreignId('ciudad_id')->constrained('ciudades');
            $table->text('ubicacion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email_particular')->nullable();
            $table->string('email_institucional')->nullable();
            $table->string('passwd')->nullable();
            $table->string('activo', 2)->default('Sí');
            $table->foreignId('tipo_colaborador_id')->constrained('tipos_colaboradores');
            $table->string('titulo1')->nullable();
            $table->string('titulo2')->nullable();
            $table->string('titulo3')->nullable();
            $table->integer('anios_servicio')->nullable();
            $table->string('seguro')->nullable();
            $table->string('gsangre', 5)->nullable();
            $table->string('enf_cronica', 2)->default('No');
            $table->string('reloj')->nullable();
            $table->string('passwd_mec')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colaboradors');
    }
};
