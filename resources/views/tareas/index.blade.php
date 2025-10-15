@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <h1 class="text-3xl font-bold text-gray-100 mb-6">📍 Reportes GPS</h1>

    {{-- Filtro de búsqueda y tipo --}}
    <form method="GET" action="{{ route('tareas.index') }}" class="flex flex-wrap gap-3 mb-6 bg-gray-800 p-4 rounded-xl">
        <input 
            type="text" 
            name="busqueda" 
            value="{{ request('busqueda') }}" 
            placeholder="Buscar por título o descripción..." 
            class="flex-1 rounded-lg px-3 py-2 text-gray-100 bg-gray-900 border border-gray-700 focus:ring focus:ring-blue-500"
        >

        <select 
            name="tipo" 
            class="rounded-lg px-3 py-2 text-gray-100 bg-gray-900 border border-gray-700 focus:ring focus:ring-blue-500"
        >
            <option value="">Todos los tipos</option>
            <option value="vehiculos" {{ request('tipo')=='vehiculos' ? 'selected' : '' }}>Vehículos</option>
            <option value="generadores" {{ request('tipo')=='generadores' ? 'selected' : '' }}>Generadores</option>
            <option value="instalaciones" {{ request('tipo')=='instalaciones' ? 'selected' : '' }}>Instalaciones de red</option>
        </select>

        <button 
            type="submit" 
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg"
        >
            🔍 Filtrar
        </button>

        <a 
            href="{{ route('tareas.create') }}" 
            class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg"
        >
            ➕ Nuevo reporte
        </a>
    </form>

    {{-- Tabla de reportes --}}
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tareas as $tarea)
            <div 
                class="
                    p-5 rounded-2xl shadow-md transition hover:scale-[1.02]
                    @if($tarea->tipo == 'vehiculos') bg-blue-900/50 border border-blue-700 
                    @elseif($tarea->tipo == 'generadores') bg-yellow-900/40 border border-yellow-600 
                    @elseif($tarea->tipo == 'instalaciones') bg-green-900/40 border border-green-700 
                    @endif
                "
            >
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-xl font-semibold text-white">{{ $tarea->titulo }}</h2>
                    <span class="
                        text-sm font-semibold px-3 py-1 rounded-full 
                        @if($tarea->tipo == 'vehiculos') bg-blue-700 text-blue-100 
                        @elseif($tarea->tipo == 'generadores') bg-yellow-700 text-yellow-100 
                        @elseif($tarea->tipo == 'instalaciones') bg-green-700 text-green-100 
                        @endif
                    ">
                        @switch($tarea->tipo)
                            @case('vehiculos') 🚗 Vehículos @break
                            @case('generadores') ⚙️ Generadores @break
                            @case('instalaciones') 🌐 Instalaciones @break
                        @endswitch
                    </span>
                </div>

                

                <p class="text-gray-300 mb-3">{{ $tarea->descripcion }}</p>

                <p class="text-sm text-gray-400 mb-4">
                    📍 <strong>GPS:</strong> {{ $tarea->coordenadas_gps }}
                </p>
                <div class="flex justify-between text-sm text-gray-400">
    <span>👤 {{ $tarea->user->name ?? 'Usuario' }}</span>
    <span>🕒 {{ $tarea->created_at->diffForHumans() }}</span>
</div>

<div class="mt-4 pt-4 border-t border-gray-700 flex items-center justify-end gap-3">
    <a href="{{ route('tareas.show', $tarea) }}" class="text-indigo-400 hover:text-indigo-300 text-sm font-semibold">
        Ver Detalles
    </a>
    <a href="{{ route('tareas.edit', $tarea) }}" class="text-green-400 hover:text-green-300 text-sm font-semibold">
        Editar
    </a>
</div>

                <div class="flex justify-between text-sm text-gray-400">
                    <span>👤 {{ $tarea->user->name ?? 'Usuario' }}</span>
                    <span>🕒 {{ $tarea->created_at->diffForHumans() }}</span>
                </div>
            </div>
            
        @empty
            <div class="col-span-full text-center text-gray-400 py-10">
                No hay reportes que coincidan con la búsqueda.
            </div>
            
        @endforelse
    </div>

    {{-- Paginación --}}
    <div class="mt-8">
        {{ $tareas->links() }}
    </div>
</div>
@endsection
