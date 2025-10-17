{{-- resources/views/tareas/partials/_list-view.blade.php --}}

<div class="bg-gray-800 rounded-xl shadow-lg overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-700">
        <thead class="bg-gray-900/50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Folio</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Tipo</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Estado</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Autor</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Fecha</th>
                <th scope="col" class="relative px-6 py-3">
                    <span class="sr-only">Acciones</span>
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
            @forelse ($tareas as $tarea)
                <tr class="hover:bg-gray-700/50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $tarea->folio }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                        <span class="text-lg mr-1">{{ 
                            match($tarea->tipo) {
                                'vehiculos' => '🚗',
                                'generadores' => '⚙️',
                                'instalaciones_red' => '🌐',
                                default => '📋'
                            } 
                        }}</span>
                        {{-- Esto convierte 'instalaciones_red' a 'Instalaciones Red' --}}
                        {{ Str::title(str_replace('_', ' ', $tarea->tipo)) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{-- Botón de estado (funciona igual que en las tarjetas) --}}
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
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $tarea->user->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $tarea->created_at->isoFormat('D MMM YYYY, h:mm a') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('tareas.show', $tarea) }}" class="text-indigo-400 hover:text-indigo-300 mr-3 font-semibold">Ver</a>
                        <a href="{{ route('tareas.edit', $tarea) }}" class="text-green-400 hover:text-green-300 font-semibold">Editar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                        No se encontraron reportes que coincidan con los filtros.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>