<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalle del Reporte: {{ $tarea->titulo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <h3 class="font-bold text-lg">Título</h3>
                        <p>{{ $tarea->titulo }}</p>
                    </div>
                    <div class="mb-4">
                        <h3 class="font-bold text-lg">Descripción</h3>
                        <p>{{ $tarea->descripcion }}</p>
                    </div>
                    <div class="mb-4">
                        <h3 class="font-bold text-lg">Coordenadas GPS</h3>
                        <p>{{ $tarea->coordenadas_gps }}</p>
                    </div>
                    <div class="mb-4">
                        <h3 class="font-bold text-lg">Estado</h3>
                        <p class="capitalize">{{ $tarea->estado }}</p>
                    </div>
                     <div class="mb-4">
                        <h3 class="font-bold text-lg">Fecha de Creación</h3>
                        <p>{{ $tarea->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('tareas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Volver a la lista
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>