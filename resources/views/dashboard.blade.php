@extends('layouts.app')

@section('content')
<div class="p-8 bg-[#1E293B] min-h-screen text-gray-100">
    <h1 class="text-3xl font-bold mb-8 text-center text-white">📊 Dashboard de Tareas</h1>

    <!-- Tarjetas de KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-[#334155] rounded-xl shadow-lg p-5 text-center hover:scale-105 transition">
            <p class="text-gray-300 text-sm uppercase">Pendientes</p>
            <p class="text-4xl font-extrabold text-yellow-400 mt-2">{{ $tareasPendientes }}</p>
        </div>
        <div class="bg-[#334155] rounded-xl shadow-lg p-5 text-center hover:scale-105 transition">
            <p class="text-gray-300 text-sm uppercase">En Proceso</p>
            <p class="text-4xl font-extrabold text-blue-400 mt-2">{{ $tareasProceso }}</p>
        </div>
        <div class="bg-[#334155] rounded-xl shadow-lg p-5 text-center hover:scale-105 transition">
            <p class="text-gray-300 text-sm uppercase">Realizadas</p>
            <p class="text-4xl font-extrabold text-green-400 mt-2">{{ $tareasRealizadas }}</p>
        </div>
        <div class="bg-[#334155] rounded-xl shadow-lg p-5 text-center hover:scale-105 transition">
            <p class="text-gray-300 text-sm uppercase">Total</p>
            <p class="text-4xl font-extrabold text-indigo-400 mt-2">
                {{ $tareasPendientes + $tareasProceso + $tareasRealizadas }}
            </p>
        </div>
    </div>

    <!-- Botón para ir a la vista de tareas -->
    <div class="text-center mb-10">
        <a href="{{ url('/tareas') }}" 
           class="inline-block bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-3 px-6 rounded-lg shadow-md transition">
           Ver Tareas →
        </a>
    </div>

    <!-- Actividades recientes -->
    <div class="bg-[#334155] rounded-xl shadow-lg p-6">
        <h2 class="text-2xl font-semibold mb-4 text-white">Actividades Recientes</h2>
        <ul class="divide-y divide-gray-600">
            @forelse($actividades as $actividad)
                <li class="py-3 flex justify-between items-center">
                    <span class="text-gray-100">{{ $actividad->descripcion }}</span>
                    <span class="text-sm text-gray-400">{{ ucfirst($actividad->estado) }}</span>
                </li>
            @empty
                <li class="py-3 text-gray-400 text-center">No hay actividades recientes.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
