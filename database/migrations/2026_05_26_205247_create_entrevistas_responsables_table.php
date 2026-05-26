<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('entrevistas_responsables', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');
            $table->foreignId('colaborador_id')->constrained('colaboradores')->onDelete('cascade'); // Entrevistador principal

            // Los responsables pueden ser nulos si solo fue uno de ellos
            $table->string('madre_cid', 20)->nullable();
            $table->string('padre_cid', 20)->nullable();
            $table->string('encargado_cid', 20)->nullable();
            $table->foreignId('parentesco_id')->nullable()->constrained('parentescos');

            $table->string('motivo');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            // Claves foráneas compuestas o basadas en strings (según tu estructura de Alumno.php que usa 'cid')
            $table->foreign('madre_cid')->references('cid')->on('madres')->onDelete('set null');
            $table->foreign('padre_cid')->references('cid')->on('padres')->onDelete('set null');
            $table->foreign('encargado_cid')->references('cid')->on('encargados')->onDelete('set null');
        });

        // Tabla intermedia para TESTIGOS (Muchos a Muchos entre Entrevista y Colaboradores)
        Schema::create('entrevista_responsable_testigo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrevista_res_id')
                ->constrained('entrevistas_responsables')
                ->onDelete('cascade')
                ->name('fk_entrevista_testigo_id');
            $table->foreignId('colaborador_id')
                ->constrained('colaboradores')
                ->onDelete('cascade')
                ->name('fk_colaborador_testigo_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entrevista_responsable_testigo');
        Schema::dropIfExists('entrevistas_responsables');
    }
};