<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehiculo_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarea_id')->unique()->constrained()->onDelete('cascade');
            
            // Campos para el GPS
            $table->string('gps_marca')->nullable();
            $table->string('gps_modelo')->nullable();
            $table->string('gps_imei')->nullable();

            // Campos para el Vehículo
            $table->string('vehiculo_marca')->nullable();
            $table->string('vehiculo_modelo')->nullable();
            $table->string('vehiculo_matricula')->nullable();
            $table->string('vehiculo_numero_economico')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehiculo_detalles');
    }
};