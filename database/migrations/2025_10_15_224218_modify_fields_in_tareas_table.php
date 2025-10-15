<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->renameColumn('titulo', 'folio'); // Renombrar titulo a folio
            $table->text('actividades')->after('descripcion'); // Agregar campo de actividades
            $table->text('observaciones')->nullable()->after('actividades'); // Agregar campo de observaciones (opcional)
            $table->dropColumn('coordenadas_gps'); // Eliminar campo GPS
        });
    }

    public function down(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->renameColumn('folio', 'titulo');
            $table->dropColumn('actividades');
            $table->dropColumn('observaciones');
            $table->string('coordenadas_gps')->after('descripcion');
        });
    }
};