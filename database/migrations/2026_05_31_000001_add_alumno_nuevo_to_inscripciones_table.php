<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('inscripciones', function (Blueprint $table) {
            $table->boolean('alumno_nuevo')->default(false)->after('alumno_cid');
        });
    }

    public function down(): void
    {
        Schema::table('inscripciones', function (Blueprint $table) {
            $table->dropColumn('alumno_nuevo');
        });
    }
};
