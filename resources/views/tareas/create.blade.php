@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-6 text-gray-100" x-data="{ tipoReporte: '{{ old('tipo', '') }}', cantidadEquipos: {{ old('cantidad_equipos', 0) }} }">
    <h1 class="text-3xl font-bold mb-6 text-white">➕ Crear Nuevo Reporte</h1>
    
    <form method="POST" action="{{ route('tareas.store') }}" class="bg-gray-800 rounded-2xl p-6 shadow-xl space-y-6">
        @csrf

        {{-- CAMPOS GENERALES --}}
        <div>
            <label for="folio" class="block text-sm font-semibold mb-2">Folio:</label>
            <input type="text" id="folio" name="folio" value="{{ old('folio') }}" class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg" required>
        </div>

        <div>
            <label for="tipo" class="block text-sm font-semibold mb-2">Tipo de reporte:</label>
            <select id="tipo" name="tipo" x-model="tipoReporte" class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg" required>
                <option value="">Seleccionar tipo...</option>
                <option value="vehiculos">🚗 Vehículos</option>
                <option value="generadores">⚙️ Generadores</option>
                <option value="instalaciones_red">🌐 Instalaciones de red</option>
            </select>
        </div>
        
        {{-- ... otros campos generales como descripción, actividades, etc. ... --}}

        {{-- CAMPOS ESPECÍFICOS PARA GENERADORES (AQUÍ OCURRE LA MAGIA) --}}
        <div x-show="tipoReporte === 'generadores'" x-transition class="bg-gray-900/50 p-4 rounded-lg border border-yellow-500 space-y-4">
            <h3 class="font-semibold text-lg text-yellow-300">Detalles de Generadores</h3>
            
            <div>
                <label for="cantidad_equipos" class="block text-sm font-semibold mb-2">Cantidad de Equipos (máx. 20):</label>
                <input 
                    type="number" 
                    id="cantidad_equipos" 
                    name="cantidad_equipos" 
                    x-model.number="cantidadEquipos"
                    min="0" 
                    max="20"
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg"
                >
            </div>

            <div x-show="cantidadEquipos > 0">
                <label class="block text-sm font-semibold mb-2">Números Económicos (Series):</label>
                <div class="space-y-2">
                    <template x-for="i in Math.min(cantidadEquipos, 20)" :key="i">
                        <input 
                            type="text"
                            :name="'numeros_economicos[' + (i-1) + ']'"
                            :placeholder="'Número de serie del equipo ' + i"
                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg"
                        >
                    </template>
                </div>
            </div>
        </div>

        {{-- Botones de Acción --}}
        <div class="flex justify-end space-x-4 pt-4">
            <a href="{{ route('tareas.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Cancelar</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg">Guardar Reporte</button>
        </div>
    </form>
</div>
@endsection