<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\Tarea;
use App\Http\Controllers\Controller;
use App\Models\GeneradorDetalle;
use App\Models\VehiculoDetalle;

class TareaController extends Controller
{
    public function index(Request $request)
{
   $query = Tarea::where('user_id', auth()->id());

    if ($request->filled('busqueda')) {
        $query->where(function ($q) use ($request) {
            $query->where('folio', 'like', '%' . $request->busqueda . '%')
              ->orWhere('descripcion', 'like', '%' . $request->busqueda . '%');
        });
    }

    if ($request->filled('tipo')) {
        $query->where('tipo', $request->tipo);
    }

    $tareas = $query->paginate(10);

    return view('tareas.index', compact('tareas'));
}

    /**
     * Show the form for creating a new resource.
     */
   public function create()
{
    return view('tareas.create');
}

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    // 1. Validación de los campos generales
    $validatedData = $request->validate([
        'folio' => 'required|string|max:255',
        'descripcion' => 'required|string',
        'actividades' => 'required|string',
        'observaciones' => 'nullable|string',
        'tipo' => 'required|in:vehiculos,generadores,instalaciones_red',
        'fotos' => 'nullable|array', 
        'fotos.*' => 'image|mimes:jpeg,png,jpg|max:2048'
    ]);

    // 2. Creamos la tarea principal
    $dataToSave = array_merge($validatedData, ['user_id' => auth()->id()]);
    $tarea = Tarea::create($dataToSave);

    // 3. Si el reporte es de tipo "generadores", guardamos sus detalles
    if ($tarea->tipo === 'generadores') {
        $generadorData = $request->validate([
            'cantidad_equipos' => 'required|integer|min:1|max:20',
            'numeros_economicos' => 'required|array|min:1',
            'numeros_economicos.*' => 'required|string|max:255',
        ]);
        $tarea->generadorDetalle()->create(['numeros_economicos' => $generadorData['numeros_economicos']]);
    }
    // 4. (NUEVO) Si el reporte es de tipo "vehiculos", guardamos sus detalles
    elseif ($tarea->tipo === 'vehiculos') {
        $vehiculoData = $request->validate([
            'gps_marca' => 'nullable|string|max:255',
            'gps_modelo' => 'nullable|string|max:255',
            'gps_imei' => 'nullable|string|max:255',
            'vehiculo_marca' => 'nullable|string|max:255',
            'vehiculo_modelo' => 'nullable|string|max:255',
            'vehiculo_matricula' => 'nullable|string|max:255',
            'vehiculo_numero_economico' => 'nullable|string|max:255',
        ]);
        $tarea->vehiculoDetalle()->create($vehiculoData);
    }

    if ($request->hasFile('fotos')) {
        foreach ($request->file('fotos') as $foto) {
            // Guarda la imagen en 'storage/app/public/evidencias' y obtiene la ruta
            $path = $foto->store('evidencias', 'public');

            // Crea el registro en la tabla 'fotos'
            $tarea->fotos()->create([
                'path' => $path,
                'etapa_subida' => 'pendiente' // Guarda la etapa actual
            ]);
        }
    }

    // 5. Redirigimos
    return redirect()->route('tareas.index')->with('success', '¡Reporte creado exitosamente!');
}

    /**
     * Display the specified resource.
     */
    public function show(Tarea $tarea)
{
    $tarea->load('generadorDetalle', 'vehiculoDetalle', 'fotos');
    return view('tareas.show', compact('tarea'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tarea $tarea)
{
    return view('tareas.edit', compact('tarea'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tarea $tarea)
    {
        // 1. Validación de los campos generales + FOTOS
        $validatedData = $request->validate([
            'folio' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'actividades' => 'required|string',
            'observaciones' => 'nullable|string',
            'tipo' => 'required|in:vehiculos,generadores,instalaciones_red',
            'fotos' => 'nullable|array', // Valida que 'fotos' sea un array (o nulo)
            'fotos.*' => 'image|mimes:jpeg,png,jpg|max:2048' // Cada foto debe ser imagen de max 2MB
        ]);

        // 2. Actualizamos la tarea principal
        $tarea->update($validatedData);

        // 3. Lógica para detalles de generadores/vehículos
        if ($tarea->tipo === 'generadores') {
            // ... (tu lógica de updateOrCreate para generadores) ...
        } elseif ($tarea->tipo === 'vehiculos') {
            // ... (tu lógica de updateOrCreate para vehiculos) ...
        }

        // 4. (NUEVO) Guardar las fotos si se subieron al editar
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $path = $foto->store('evidencias', 'public');
                
                // Aquí SÍ usamos $tarea->estado, porque la tarea ya existe
                // y conocemos su estado actual.
                $tarea->fotos()->create([
                    'path' => $path,
                    'etapa_subida' => $tarea->estado 
                ]);
            }
        }

        // 5. Redirigimos
        return redirect()->route('tareas.index')->with('success', '¡Reporte actualizado exitosamente!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
public function updateStatus(Tarea $tarea)
{
    $nextStatus = match($tarea->estado) {
        'pendiente' => 'en_proceso',
        'en_proceso' => 'completada',
        'completada' => 'pendiente',
    };

    $tarea->update(['estado' => $nextStatus]);

    return back()->with('success', 'Estado del reporte actualizado.');
}
    
}
