@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- CABECERA Y BOTÓN DE CREAR --}}
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

    {{-- BARRA DE FILTROS MEJORADA --}}
    <div class="mb-6">
        <form method="GET" action="{{ route('tareas.index') }}" class="bg-gray-800 p-4 rounded-xl shadow-lg">
            
            {{-- Fila 1: Búsqueda y Botones de Vista --}}
            <div class="flex flex-col md:flex-row gap-4 mb-4">
                {{-- Campo de Búsqueda --}}
                <div class="relative flex-grow">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                    </div>
                    <input 
                        type="text" 
                        name="busqueda" 
                        value="{{ request('busqueda') }}" 
                        placeholder="Buscar por folio o descripción..." 
                        class="w-full pl-10 pr-4 py-2 rounded-lg text-gray-100 bg-gray-900 border border-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>
                
                {{-- NUEVO: Botones de Toggle de Vista --}}
                <div class="flex-shrink-0 flex gap-2">
                    {{-- El helper request()->fullUrlWithQuery(...) mantiene los filtros actuales al cambiar de vista --}}
                    <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}" 
                       class="p-2 rounded-lg border {{ $view === 'grid' ? 'bg-blue-600 border-blue-500 text-white' : 'bg-gray-700 border-gray-600 text-gray-400 hover:bg-gray-600' }}"
                       title="Vista de Tarjetas">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2zM11 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2z" /></svg>
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}" 
                       class="p-2 rounded-lg border {{ $view === 'list' ? 'bg-blue-600 border-blue-500 text-white' : 'bg-gray-700 border-gray-600 text-gray-400 hover:bg-gray-600' }}"
                       title="Vista de Lista">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" /></svg>
                    </a>
                </div>
            </div>
            
            {{-- Fila 2: Filtros Select --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Campo de Tipo de Reporte --}}
                <select name="tipo" class="w-full rounded-lg px-3 py-2 text-gray-100 bg-gray-900 border border-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los Tipos</option>
                    <option value="vehiculos" @selected(request('tipo') == 'vehiculos')>🚗 Vehículos</option>
                    <option value="generadores" @selected(request('tipo') == 'generadores')>⚙️ Generadores</option>
                    <option value="instalaciones_red" @selected(request('tipo') == 'instalaciones_red')>🌐 Instalaciones</option>
                </select>
                
                {{-- NUEVO: Campo de Estado/Etapa --}}
                <select name="estado" class="w-full rounded-lg px-3 py-2 text-gray-100 bg-gray-900 border border-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todas las Etapas</option>
                    <option value="pendiente" @selected(request('estado') == 'pendiente')>🟡 Pendiente</option>
                    <option value="en_proceso" @selected(request('estado') == 'en_proceso')>🔵 En Proceso</option>
                    <option value="completada" @selected(request('estado') == 'completada')>🟢 Completada</option>
                </select>
            </div>
            
            {{-- Botón de Aplicar Filtros --}}
            <div class="flex justify-end mt-4">
                <button 
                    type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg"
                >
                    Aplicar Filtros
                </button>
            </div>
        </form>
    </div>

    {{-- LÓGICA DE VISTA: GRID O LISTA --}}
    @if ($view === 'list')
        {{-- Incluimos la nueva vista de lista --}}
        @include('tareas.partials._list-view', ['tareas' => $tareas])
    @else
        {{-- Mantenemos la vista de tarjetas (grid) --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($tareas as $tarea)
                {{-- Este es el código de tu tarjeta que ya tenías --}}
                <div class="flex flex-col bg-gray-800 border-l-4 {{ 
                    match($tarea->tipo) {
                        'vehiculos' => 'border-blue-500',
                        'generadores' => 'border-yellow-500',
                        'instalaciones_red' => 'border-green-500',
                        default => 'border-gray-600'
                    } 
                }} rounded-r-lg shadow-lg overflow-hidden transition hover:shadow-2xl hover:-translate-y-1">
                    
                    <div class="p-5 flex-grow">
                        <div class="flex items-start justify-between">
                            <h2 class="text-xl font-semibold text-white mb-2">{{ $tarea->folio }}</h2>
                            <span class="text-2xl opacity-80">{{ 
                                match($tarea->tipo) {
                                    'vehiculos' => '🚗', 'generadores' => '⚙️', 'instalaciones_red' => '🌐', default => '📋'
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

                    <footer class="bg-gray-900/50 px-5 py-3 flex items-center justify-between">
                         <form action="{{ route('tareas.updateStatus', $tarea) }}" method="POST">
                            @csrf
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
    @endif

    {{-- Paginación --}}
    <div class="mt-8 text-gray-400">
        {{ $tareas->links() }}
    </div>
</div>
@endsection