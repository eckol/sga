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
        Schema::table('inscripciones', function (Blueprint $table) {
            $table->enum('estado', ['Matriculado', 'Egresado', 'Trasladado', 'Abandono'])
                ->default('Matriculado')
                ->after('aut_foto');
            $table->date('fecha_baja')->nullable()->after('estado');
            $table->text('observaciones')->nullable()->after('fecha_baja');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inscripciones', function (Blueprint $table) {
            $table->dropColumn(['estado', 'fecha_baja', 'observaciones']);
        });
    }
};
