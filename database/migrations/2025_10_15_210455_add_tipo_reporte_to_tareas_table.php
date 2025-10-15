<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('tareas', function (Blueprint $table) {
        $table->enum('tipo_reporte', ['vehiculos', 'generadores', 'instalaciones'])
              ->default('vehiculos')
              ->after('coordenadas_gps');
    });
}

public function down(): void
{
    Schema::table('tareas', function (Blueprint $table) {
        $table->dropColumn('tipo_reporte');
    });
}

};
