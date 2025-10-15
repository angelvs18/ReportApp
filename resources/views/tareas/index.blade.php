@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- CABECERA Y FILTROS --}}
    <header class="md:flex md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white">📍 Reportes de Campo</h1>
            <p class="text-sm text-gray-400 mt-1">Visualiza, filtra y gestiona todos los reportes de GPS.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('tareas.create') }}" 
               class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                Nuevo Reporte
            </a>
        </div>
    </header>

    {{-- BARRA DE FILTROS --}}
    <div class="mb-8">
        <form method="GET" action="{{ route('tareas.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-800 p-4 rounded-xl shadow-lg">
            {{-- Campo de Búsqueda --}}
            <div class="col-span-1 md:col-span-2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                    </div>
                    <input 
                        type="text" 
                        name="busqueda" 
                        value="{{ request('busqueda') }}" 
                        placeholder="Buscar por título o descripción..." 
                        class="w-full pl-10 pr-4 py-2 rounded-lg text-gray-100 bg-gray-900 border border-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>
            </div>
            
            {{-- Campo de Tipo de Reporte --}}
            <div class="col-span-1">
                <select 
                    name="tipo" 
                    class="w-full h-full rounded-lg px-3 py-2 text-gray-100 bg-gray-900 border border-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    onchange="this.form.submit()"
                >
                    <option value="">Todos los tipos</option>
                    <option value="vehiculos" @selected(request('tipo') == 'vehiculos')>🚗 Vehículos</option>
                    <option value="generadores" @selected(request('tipo') == 'generadores')>⚙️ Generadores</option>
                    <option value="instalaciones_red" @selected(request('tipo') == 'instalaciones_red')>🌐 Instalaciones</option>
                </select>
            </div>
        </form>
    </div>

    {{-- LISTA DE REPORTES EN TARJETAS --}}
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tareas as $tarea)
            <div class="flex flex-col bg-gray-800 border-l-4 {{ 
                match($tarea->tipo) {
                    'vehiculos' => 'border-blue-500',
                    'generadores' => 'border-yellow-500',
                    'instalaciones_red' => 'border-green-500',
                    default => 'border-gray-600'
                } 
            }} rounded-r-lg shadow-lg overflow-hidden transition hover:shadow-2xl hover:-translate-y-1">
                
                {{-- Contenido de la tarjeta --}}
                <div class="p-5 flex-grow">
                    <div class="flex items-start justify-between">
                        <h2 class="text-xl font-semibold text-white mb-2">{{ $tarea->folio }}</h2>                        <span class="text-2xl opacity-80">{{ 
                            match($tarea->tipo) {
                                'vehiculos' => '🚗',
                                'generadores' => '⚙️',
                                'instalaciones_red' => '🌐',
                                default => '📋'
                            } 
                        }}</span>
                    </div>

                    <p class="text-gray-400 text-sm mb-4 h-16 overflow-hidden">{{ Str::limit($tarea->descripcion, 100) }}</p>

                    <div class="text-sm text-gray-400 space-y-2">
                        
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10 9a3 3 0 100-6 3 3 0 000 6z" /><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                            <strong>Autor:</strong> {{ $tarea->user->name ?? 'N/A' }}
                        </p>
                    </div>
                </div>

                {{-- Pie de la tarjeta con acciones y fecha --}}
                <footer class="bg-gray-900/50 px-5 py-3 flex items-center justify-between">
    {{-- Formulario para cambiar estado --}}
<form action="{{ route('tareas.updateStatus', $tarea) }}" method="POST">        @csrf
        @method('PATCH')
        <button type="submit" class="text-xs font-semibold px-2 py-1 rounded-full {{ 
            match($tarea->estado) {
                'pendiente' => 'bg-yellow-500 text-yellow-900 hover:bg-yellow-400',
                'en_proceso' => 'bg-blue-500 text-blue-900 hover:bg-blue-400',
                'completada' => 'bg-green-500 text-green-900 hover:bg-green-400',
            } 
        }}">
            {{ Str::title(str_replace('_', ' ', $tarea->estado)) }}
        </button>
    </form>
    
    <div class="flex items-center gap-4">
        <a href="{{ route('tareas.show', $tarea) }}" class="text-indigo-400 hover:text-indigo-300 font-semibold text-sm">Ver</a>
        <a href="{{ route('tareas.edit', $tarea) }}" class="text-green-400 hover:text-green-300 font-semibold text-sm">Editar</a>
    </div>
</footer>
            </div>
        @empty
            <div class="col-span-full bg-gray-800 rounded-xl p-10 text-center">
                <p class="text-3xl mb-2">🤷‍♂️</p>
                <h3 class="text-xl font-semibold text-white">No se encontraron reportes</h3>
                <p class="text-gray-400 mt-1">Intenta con otros filtros o <a href="{{ route('tareas.create') }}" class="text-blue-400 hover:underline">crea un nuevo reporte</a>.</p>
            </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    <div class="mt-8">
        {{ $tareas->links() }}
    </div>
</div>
@endsection