@extends('layouts.app')

@section('content')
{{-- 
    Usamos Alpine.js para la interactividad.
    1. 'tipoReporte': Guarda el tipo de reporte seleccionado.
    2. 'cantidadEquipos': Guarda el número del campo "Cantidad". Lo inicializamos contando los números de serie que ya existen.
    3. 'numerosExistentes': Pasamos los números de serie guardados a una variable de JavaScript.
--}}
<div class="max-w-3xl mx-auto py-10 px-6 text-gray-100" 
     x-data="{ 
        tipoReporte: '{{ old('tipo', $tarea->tipo) }}', 
        cantidadEquipos: {{ old('cantidad_equipos', $tarea->generadorDetalle ? count($tarea->generadorDetalle->numeros_economicos) : 0) }},
        numerosExistentes: {{ json_encode(old('numeros_economicos', $tarea->generadorDetalle->numeros_economicos ?? [])) }}
     }">

    <h1 class="text-3xl font-bold mb-6 text-white">✏️ Editando Reporte: {{ $tarea->folio }}</h1>

    <form method="POST" action="{{ route('tareas.update', $tarea) }}" class="bg-gray-800 rounded-2xl p-6 shadow-xl space-y-6" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- =================== CAMPOS GENERALES (SIEMPRE VISIBLES) =================== --}}
        
        <div>
            <label for="folio" class="block text-sm font-semibold mb-2">Folio:</label>
            <input type="text" id="folio" name="folio" value="{{ old('folio', $tarea->folio) }}" class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg" required>
        </div>

        <div>
            <label for="tipo" class="block text-sm font-semibold mb-2">Tipo de reporte:</label>
            <select id="tipo" name="tipo" x-model="tipoReporte" class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg" required>
                <option value="vehiculos" @selected(old('tipo', $tarea->tipo) == 'vehiculos')>🚗 Vehículos</option>
                <option value="generadores" @selected(old('tipo', $tarea->tipo) == 'generadores')>⚙️ Generadores</option>
                <option value="instalaciones_red" @selected(old('tipo', $tarea->tipo) == 'instalaciones_red')>🌐 Instalaciones de red</option>
            </select>
        </div>

        <div>
            <label for="descripcion" class="block text-sm font-semibold mb-2">Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="3" class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg" required>{{ old('descripcion', $tarea->descripcion) }}</textarea>
        </div>

        <div>
            <label for="actividades" class="block text-sm font-semibold mb-2">Actividades Realizadas:</label>
            <textarea id="actividades" name="actividades" rows="5" class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg" required>{{ old('actividades', $tarea->actividades) }}</textarea>
        </div>

        <div>
            <label for="observaciones" class="block text-sm font-semibold mb-2">Observaciones (Opcional):</label>
            <textarea id="observaciones" name="observaciones" rows="3" class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg">{{ old('observaciones', $tarea->observaciones) }}</textarea>
        </div>

        {{-- =================== CAMPOS ESPECÍFICOS PARA GENERADORES =================== --}}
        <div x-show="tipoReporte === 'generadores'" x-transition class="bg-gray-900/50 p-4 rounded-lg border border-yellow-500 space-y-4">
            <h3 class="font-semibold text-lg text-yellow-300">Detalles de Generadores</h3>
            
            <div>
                <label for="cantidad_equipos" class="block text-sm font-semibold mb-2">Cantidad de Equipos (máx. 20):</label>
                <input type="number" id="cantidad_equipos" name="cantidad_equipos" x-model.number="cantidadEquipos" min="0" max="20" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg">
            </div>

            <div x-show="cantidadEquipos > 0">
                <label class="block text-sm font-semibold mb-2">Números Económicos (Series):</label>
                <div class="space-y-2">
                    <template x-for="i in Math.min(cantidadEquipos, 20)" :key="i">
                        <input 
                            type="text"
                            :name="'numeros_economicos[' + (i-1) + ']'"
                            :value="numerosExistentes[i-1] || ''" 
                            :placeholder="'Número de serie del equipo ' + i"
                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg"
                        >
                    </template>
                </div>
            </div>
        </div>
        {{-- CAMPO: Evidencia Fotográfica --}}
        <div class="pt-4 border-t border-gray-700">
            <label for="fotos" class="block text-sm font-semibold mb-2">Evidencia Fotográfica (puedes seleccionar varias):</label>
            <input 
                type="file" 
                id="fotos" 
                name="fotos[]"
                class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700"
                multiple
            >

        {{-- =================== BOTONES DE ACCIÓN =================== --}}
        <div class="flex justify-end space-x-4 pt-4">
            <a href="{{ route('tareas.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Cancelar</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg">Actualizar Reporte</button>
        </div>
    </form>
</div>
@endsection