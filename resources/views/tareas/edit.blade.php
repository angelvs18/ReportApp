@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-6 text-gray-100">
    <h1 class="text-3xl font-bold mb-6 text-white">✏️ Editando Reporte: {{ $tarea->folio }}</h1>

    <form method="POST" action="{{ route('tareas.update', $tarea) }}" class="bg-gray-800 rounded-2xl p-6 shadow-xl space-y-6">
        @csrf
        @method('PUT')

        {{-- CAMPO: Folio --}}
        <div>
            <label for="folio" class="block text-sm font-semibold mb-2">Folio:</label>
            <input type="text" id="folio" name="folio" value="{{ old('folio', $tarea->folio) }}" class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg" required>
        </div>

        {{-- CAMPO: Tipo de reporte --}}
        <div>
            <label for="tipo" class="block text-sm font-semibold mb-2">Tipo de reporte:</label>
            <select id="tipo" name="tipo" class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg" required>
                <option value="vehiculos" @selected(old('tipo', $tarea->tipo) == 'vehiculos')>🚗 Vehículos</option>
                <option value="generadores" @selected(old('tipo', $tarea->tipo) == 'generadores')>⚙️ Generadores</option>
                <option value="instalaciones_red" @selected(old('tipo', $tarea->tipo) == 'instalaciones_red')>🌐 Instalaciones de red</option>
            </select>
        </div>

        {{-- CAMPO: Descripción --}}
        <div>
            <label for="descripcion" class="block text-sm font-semibold mb-2">Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="3" class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg" required>{{ old('descripcion', $tarea->descripcion) }}</textarea>
        </div>

        {{-- CAMPO: Actividades --}}
        <div>
            <label for="actividades" class="block text-sm font-semibold mb-2">Actividades Realizadas:</label>
            <textarea id="actividades" name="actividades" rows="5" class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg" required>{{ old('actividades', $tarea->actividades) }}</textarea>
        </div>

        {{-- CAMPO: Observaciones --}}
        <div>
            <label for="observaciones" class="block text-sm font-semibold mb-2">Observaciones (Opcional):</label>
            <textarea id="observaciones" name="observaciones" rows="3" class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg">{{ old('observaciones', $tarea->observaciones) }}</textarea>
        </div>
        
        {{-- Botones de Acción --}}
        <div class="flex justify-end space-x-4 pt-4">
            <a href="{{ route('tareas.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                ⬅️ Cancelar
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg">
                💾 Actualizar Reporte
            </button>
        </div>
    </form>
</div>
@endsection