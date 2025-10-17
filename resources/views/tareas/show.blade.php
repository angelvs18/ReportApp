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
                    'vehiculos' => '🚗', 'generadores' => '⚙️', 'instalaciones_red' => '🌐', default => '📋'
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

        {{-- =================== SECCIÓN DETALLES DE GENERADORES =================== --}}
        @if ($tarea->tipo === 'generadores' && $tarea->generadorDetalle)
            <div class="pt-4 border-t border-yellow-500">
                <h3 class="font-semibold text-lg text-yellow-300 mb-3">Números Económicos de Generadores</h3>
                <ul class="list-disc list-inside space-y-1 text-gray-300">
                    @foreach ($tarea->generadorDetalle->numeros_economicos as $numero)
                        <li>{{ $numero }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- =================== (NUEVO) SECCIÓN DETALLES DE VEHÍCULOS =================== --}}
        @if ($tarea->tipo === 'vehiculos' && $tarea->vehiculoDetalle)
            <div class="pt-4 border-t border-blue-500">
                <h3 class="font-semibold text-lg text-blue-300 mb-4">Detalles de Vehículo y GPS</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    
                    {{-- Columna GPS --}}
                    <div class="space-y-3">
                        <h4 class="font-semibold text-gray-300 border-b border-gray-700 pb-1">Datos del GPS</h4>
                        <div><span class="text-gray-400">Marca:</span> {{ $tarea->vehiculoDetalle->gps_marca ?? 'N/A' }}</div>
                        <div><span class="text-gray-400">Modelo:</span> {{ $tarea->vehiculoDetalle->gps_modelo ?? 'N/A' }}</div>
                        <div><span class="text-gray-400">IMEI:</span> {{ $tarea->vehiculoDetalle->gps_imei ?? 'N/A' }}</div>
                    </div>

                    {{-- Columna Vehículo --}}
                    <div class="space-y-3">
                        <h4 class="font-semibold text-gray-300 border-b border-gray-700 pb-1">Datos del Vehículo</h4>
                        <div><span class="text-gray-400">Marca:</span> {{ $tarea->vehiculoDetalle->vehiculo_marca ?? 'N/A' }}</div>
                        <div><span class="text-gray-400">Modelo:</span> {{ $tarea->vehiculoDetalle->vehiculo_modelo ?? 'N/A' }}</div>
                        <div><span class="text-gray-400">Matrícula:</span> {{ $tarea->vehiculoDetalle->vehiculo_matricula ?? 'N/A' }}</div>
                        <div><span class="text-gray-400">No. Económico:</span> {{ $tarea->vehiculoDetalle->vehiculo_numero_economico ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        @endif

        {{-- =================== (NUEVO) SECCIÓN DE EVIDENCIA FOTOGRÁFICA =================== --}}
        @if ($tarea->fotos->isNotEmpty())
            <div class="pt-4 border-t border-gray-700">
                <h3 class="font-semibold text-lg text-gray-300 mb-4">Evidencia Fotográfica</h3>
                
                {{-- Agrupamos las fotos por la etapa en que se subieron --}}
                @foreach ($tarea->fotos->groupBy('etapa_subida') as $etapa => $fotosEtapa)
                    <h4 class="text-md font-semibold text-gray-200 capitalize mb-3">{{ str_replace('_', ' ', $etapa) }}</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                        @foreach ($fotosEtapa as $foto)
                            {{-- Storage::url() crea el enlace público a tu foto --}}
                            <a href="{{ Storage::url($foto->path) }}" target="_blank" class="block rounded-lg overflow-hidden shadow-md group">
                                <img src="{{ Storage::url($foto->path) }}" alt="Evidencia de {{ $etapa }}" class="w-full h-32 object-cover transform transition-transform duration-300 group-hover:scale-110">
                            </a>
                        @endforeach
                    </div>
                @endforeach
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

        {{-- BOTONES --}}
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