<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editando Reporte: {{ $tarea->titulo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('tareas.update', $tarea) }}">
                        @csrf
                        @method('PUT') <div>
                            <label for="titulo">Título</label>
                            <input id="titulo" class="block mt-1 w-full" type="text" name="titulo" value="{{ old('titulo', $tarea->titulo) }}" required autofocus />
                        </div>

                        <div class="mt-4">
                            <label for="descripcion">Descripción</label>
                            <textarea id="descripcion" name="descripcion" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>{{ old('descripcion', $tarea->descripcion) }}</textarea>
                        </div>
                        
                        <div class="mt-4">
                            <label for="coordenadas_gps">Coordenadas GPS</label>
                            <input id="coordenadas_gps" class="block mt-1 w-full" type="text" name="coordenadas_gps" value="{{ old('coordenadas_gps', $tarea->coordenadas_gps) }}" required />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Actualizar Tarea
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>