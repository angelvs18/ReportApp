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
    Schema::create('fotos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tarea_id')->constrained()->onDelete('cascade');
        $table->string('path'); // Aquí guardaremos la ruta de la imagen
        $table->string('etapa_subida')->default('pendiente'); // Guardamos la etapa del reporte al subir
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fotos');
    }
};
