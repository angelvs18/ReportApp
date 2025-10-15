@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-6 text-gray-100">
    <h1 class="text-3xl font-bold mb-6 text-white">➕ Crear Nuevo Reporte</h1>

    {{-- Bloque para mostrar errores de validación --}}
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

        {{-- CAMPO: Folio --}}
        <div>
            <label for="folio" class="block text-sm font-semibold mb-2">Folio:</label>
            <input 
                type="text" 
                id="folio" 
                name="folio" 
                value="{{ old('folio') }}"
                class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg focus:ring focus:ring-blue-500 text-gray-100"
                placeholder="Ejemplo: KUA00199"
                required
            >
        </div>

        {{-- CAMPO: Tipo de reporte --}}
        <div>
            <label for="tipo" class="block text-sm font-semibold mb-2">Tipo de reporte:</label>
            <select 
                id="tipo" 
                name="tipo"
                class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg focus:ring focus:ring-blue-500 text-gray-100"
                required
            >
                <option value="">Seleccionar tipo...</option>
                <option value="vehiculos" @selected(old('tipo') == 'vehiculos')>🚗 Vehículos</option>
                <option value="generadores" @selected(old('tipo') == 'generadores')>⚙️ Generadores</option>
                <option value="instalaciones_red" @selected(old('tipo') == 'instalaciones_red')>🌐 Instalaciones de red</option>
            </select>
        </div>

        {{-- CAMPO: Descripción --}}
        <div>
            <label for="descripcion" class="block text-sm font-semibold mb-2">Descripción:</label>
            <textarea 
                id="descripcion" 
                name="descripcion" 
                rows="3" 
                class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg focus:ring focus:ring-blue-500 text-gray-100"
                placeholder="Describe brevemente la situación del reporte..."
                required
            >{{ old('descripcion') }}</textarea>
        </div>

        {{-- CAMPO: Actividades --}}
        <div>
            <label for="actividades" class="block text-sm font-semibold mb-2">Actividades Realizadas:</label>
            <textarea 
                id="actividades" 
                name="actividades" 
                rows="5" 
                class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg focus:ring focus:ring-blue-500 text-gray-100"
                placeholder="Detalla las actividades que se llevaron a cabo..."
                required
            >{{ old('actividades') }}</textarea>
        </div>

        {{-- CAMPO: Observaciones --}}
        <div>
            <label for="observaciones" class="block text-sm font-semibold mb-2">Observaciones (Opcional):</label>
            <textarea 
                id="observaciones" 
                name="observaciones" 
                rows="3" 
                class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg focus:ring focus:ring-blue-500 text-gray-100"
                placeholder="Añade cualquier nota o comentario adicional..."
            >{{ old('observaciones') }}</textarea>
        </div>

        {{-- Botones de Acción --}}
        <div class="flex justify-end space-x-4 pt-4">
            <a href="{{ route('tareas.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                ⬅️ Cancelar
            </a>
            <button 
                type="submit" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition"
            >
                💾 Guardar Reporte
            </button>
        </div>
    </form>
</div>
@endsection