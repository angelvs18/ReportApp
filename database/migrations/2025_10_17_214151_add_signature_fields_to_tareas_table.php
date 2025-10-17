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
        $table->string('instalador_nombre')->nullable()->after('estado');
        $table->string('instalador_firma_path')->nullable()->after('instalador_nombre');
        $table->string('cliente_nombre')->nullable()->after('instalador_firma_path');
        $table->string('cliente_firma_path')->nullable()->after('cliente_nombre');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            //
        });
    }
};
