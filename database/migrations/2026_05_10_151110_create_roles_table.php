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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('rol')->unique(); // admin, docente, padre, etc.
            $table->timestamps();
        });
        // Agregamos el campo role_id a la tabla users existente
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->constrained('roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
