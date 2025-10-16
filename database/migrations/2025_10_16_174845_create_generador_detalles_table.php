<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('generador_detalles', function (Blueprint $table) {
            $table->id();
            // Creamos la conexión con la tabla principal de tareas
            $table->foreignId('tarea_id')->unique()->constrained()->onDelete('cascade');
            // Usamos un campo JSON para guardar la lista de números de serie
            $table->json('numeros_economicos')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generador_detalles');
    }
};