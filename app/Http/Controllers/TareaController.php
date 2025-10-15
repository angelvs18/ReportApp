<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\Tarea;
use App\Http\Controllers\Controller;

class TareaController extends Controller
{
    public function index(Request $request)
{
   $query = Tarea::where('user_id', auth()->id());

    if ($request->filled('busqueda')) {
        $query->where(function ($q) use ($request) {
            $q->where('titulo', 'like', '%' . $request->busqueda . '%')
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
    // 1. Valida que los campos no vengan vacíos
    $validatedData = $request->validate([
        'titulo' => 'required|string|max:255',
        'descripcion' => 'required|string',
        'coordenadas_gps' => 'required|string|max:255',
        'tipo' => 'required|in:vehiculos,generadores,instalaciones_red'
    ]);

    // 2. Crea el reporte y lo asocia automáticamente con el usuario que inició sesión
    $request->user()->tareas()->create($validatedData);

    // 3. Redirige de vuelta a la lista de tareas con un mensaje de éxito
    return redirect()->route('tareas.index')->with('success', '¡Reporte de tarea creado exitosamente!');
}

    /**
     * Display the specified resource.
     */
    public function show(Tarea $tarea)
{
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
    // 1. Validación
    $validatedData = $request->validate([
    'titulo' => 'required|string|max:255',
    'descripcion' => 'required|string',
    'coordenadas_gps' => 'required|string|max:255',
    'tipo' => 'required|in:vehiculos,generadores,instalaciones',
]);


    // 2. Actualización
    $tarea->update($validatedData);

    // 3. Redirección
    return redirect()->route('tareas.index')->with('success', '¡Reporte actualizado exitosamente!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    
}
