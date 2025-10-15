@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-6 text-gray-100">
    <h1 class="text-3xl font-bold mb-6 text-white">➕ Crear nuevo reporte</h1>

    {{-- Mensajes de error --}}
    @if ($errors->any())
        <div class="bg-red-900/50 border border-red-600 text-red-200 px-4 py-3 rounded-xl mb-6">
            <strong>⚠️ Ocurrieron algunos errores:</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('tareas.store') }}" class="bg-gray-800 rounded-2xl p-6 shadow-xl space-y-6">
        @csrf

        {{-- Título --}}
        <div>
            <label for="titulo" class="block text-sm font-semibold mb-2">Título:</label>
            <input 
                type="text" 
                id="titulo" 
                name="titulo" 
                value="{{ old('titulo') }}"
                class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg focus:ring focus:ring-blue-500 text-gray-100"
                placeholder="Ejemplo: KUA00199"
                required
            >
        </div>

        {{-- Descripción --}}
        <div>
            <label for="descripcion" class="block text-sm font-semibold mb-2">Descripción:</label>
            <textarea 
                id="descripcion" 
                name="descripcion" 
                rows="4" 
                class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg focus:ring focus:ring-blue-500 text-gray-100"
                placeholder="Describe brevemente el reporte..."
                required
            >{{ old('descripcion') }}</textarea>
        </div>

        {{-- Coordenadas GPS --}}
        <div>
            <label for="coordenadas_gps" class="block text-sm font-semibold mb-2">Coordenadas GPS:</label>
            <input 
                type="text" 
                id="coordenadas_gps" 
                name="coordenadas_gps" 
                value="{{ old('coordenadas_gps') }}"
                class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg focus:ring focus:ring-blue-500 text-gray-100"
                placeholder="Ejemplo: 19.4326, -99.1332"
                required
            >
        </div>

        {{-- Tipo de reporte --}}
        <div>
            <label for="tipo" class="block text-sm font-semibold mb-2">Tipo de reporte:</label>
            <select 
                id="tipo" 
                name="tipo"
                class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg focus:ring focus:ring-blue-500 text-gray-100"
                required
            >
                <option value="">Seleccionar tipo...</option>
                <option value="vehiculos" {{ old('tipo') == 'vehiculos' ? 'selected' : '' }}>🚗 Vehículos</option>
                <option value="generadores" {{ old('tipo') == 'generadores' ? 'selected' : '' }}>⚙️ Generadores</option>
                <option value="instalaciones" {{ old('tipo') == 'instalaciones' ? 'selected' : '' }}>🌐 Instalaciones de red</option>
            </select>
        </div>

        {{-- Botones --}}
        <div class="flex justify-end space-x-4 pt-4">
            <a href="{{ route('tareas.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                ⬅️ Cancelar
            </a>
            <button 
                type="submit" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition"
            >
                💾 Guardar tarea
            </button>
        </div>
    </form>
</div>
@endsection
