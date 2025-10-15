@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6 text-gray-100">
    <h1 class="text-3xl font-bold mb-6 text-white">📄 Detalle del Reporte</h1>

    <div class="bg-gray-800 rounded-2xl p-6 shadow-xl space-y-4">
        {{-- SECCIÓN: Folio y Tipo --}}
        <div class="flex justify-between items-start">
            <div>
                <h3 class="font-semibold text-gray-400">Folio</h3>
                <p class="text-2xl font-bold">{{ $tarea->folio }}</p>
            </div>
            <span class="text-2xl opacity-80">{{ 
                match($tarea->tipo) {
                    'vehiculos' => '🚗',
                    'generadores' => '⚙️',
                    'instalaciones_red' => '🌐',
                    default => '📋'
                } 
            }}</span>
        </div>

        {{-- SECCIÓN: Descripción --}}
        <div class="pt-4 border-t border-gray-700">
            <h3 class="font-semibold text-gray-400">Descripción</h3>
            <p>{{ $tarea->descripcion }}</p>
        </div>
        
        {{-- SECCIÓN: Actividades --}}
        <div class="pt-4 border-t border-gray-700">
            <h3 class="font-semibold text-gray-400">Actividades Realizadas</h3>
            <p>{{ $tarea->actividades }}</p>
        </div>
        
        {{-- SECCIÓN: Observaciones --}}
        @if($tarea->observaciones)
        <div class="pt-4 border-t border-gray-700">
            <h3 class="font-semibold text-gray-400">Observaciones</h3>
            <p>{{ $tarea->observaciones }}</p>
        </div>
        @endif

        {{-- SECCIÓN: Estado y Fecha --}}
        <div class="pt-4 border-t border-gray-700 grid grid-cols-2 gap-4">
            <div>
                <h3 class="font-semibold text-gray-400">Estado</h3>
                <p class="capitalize">{{ $tarea->estado }}</p>
            </div>
            <div>
                <h3 class="font-semibold text-gray-400">Fecha de Creación</h3>
                <p>{{ $tarea->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        {{-- Botones de Acción --}}
        <div class="flex justify-end space-x-4 pt-6">
            <a href="{{ route('tareas.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                ⬅️ Volver
            </a>
            <a href="{{ route('tareas.edit', $tarea) }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg">
                ✏️ Editar
            </a>
        </div>
    </div>
</div>
@endsection