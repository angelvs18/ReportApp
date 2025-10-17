<?php

namespace App\Http\Controllers;

// Importaciones de Clases de Laravel
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

// Importaciones de tus Modelos
use App\Models\Tarea;
use App\Models\GeneradorDetalle;
use App\Models\VehiculoDetalle;
use Barryvdh\DomPDF\Facade\Pdf;

class TareaController extends Controller
{
    /**
     * Muestra una lista de todos los reportes, con filtros.
     */
    public function index(Request $request)
    {
        $query = Tarea::query()->with('user'); // Carga la relación con el usuario

        // Filtro por búsqueda de folio o descripción
        if ($request->filled('busqueda')) {
            $query->where(function($q) use ($request) {
                $q->where('folio', 'like', '%' . $request->busqueda . '%')
                  ->orWhere('descripcion', 'like', '%' . $request->busqueda . '%');
            });
        }

        // Filtro por tipo de reporte
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // NUEVO: Filtro por estado/etapa
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Obtenemos tareas del usuario logueado, ordenadas de más nuevas a más viejas
        $tareas = $query->where('user_id', auth()->id())
                       ->latest()
                       ->paginate(10) // 10 por página es un buen número para una lista
                       ->withQueryString(); // IMPORTANTE: Mantiene los filtros en los enlaces de paginación

        // NUEVO: Obtenemos el modo de vista (grid por defecto)
        $view = $request->get('view', 'grid');

        // Pasamos las tareas y el modo de vista al 'index'
        return view('tareas.index', compact('tareas', 'view'));
    }

    /**
     * Muestra el formulario para crear un nuevo reporte.
     */
    public function create()
    {
        return view('tareas.create');
    }

    /**
     * Guarda un nuevo reporte en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Validación de todos los campos posibles
        $validatedData = $request->validate($this->getValidationRules());

        // 2. Procesar y guardar las firmas
        $instaladorPath = $this->saveSignature($request, 'instalador');
        $clientePath = $this->saveSignature($request, 'cliente');

        // 3. Preparamos los datos para la tarea principal
        $dataToSave = array_merge($validatedData, [
            'user_id' => auth()->id(),
            'estado' => 'pendiente', // Estado inicial
            'instalador_nombre' => $request->instalador_nombre,
            'instalador_firma_path' => $instaladorPath,
            'cliente_nombre' => $request->cliente_nombre,
            'cliente_firma_path' => $clientePath,
        ]);
        
        // 4. Creamos la tarea
        $tarea = Tarea::create($dataToSave);

        // 5. Guardar detalles específicos del tipo
        if ($tarea->tipo === 'generadores') {
            $generadorData = $request->validate([
                'cantidad_equipos' => 'required|integer|min:0|max:20',
                'numeros_economicos' => 'required_if:cantidad_equipos,>,0|array',
                'numeros_economicos.*' => 'required|string|max:255',
            ]);
            $tarea->generadorDetalle()->create(['numeros_economicos' => $generadorData['numeros_economicos'] ?? []]);
        } 
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

        // 6. Guardar las fotos
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $path = $foto->store('evidencias', 'public');
                $tarea->fotos()->create([
                    'path' => $path,
                    'etapa_subida' => $tarea->estado // Guarda 'pendiente'
                ]);
            }
        }

        // 7. Redirigimos
        return redirect()->route('tareas.index')->with('success', '¡Reporte creado exitosamente!');
    }

    /**
     * Muestra la vista de detalles de un reporte específico.
     */
    public function show(Tarea $tarea)
    {
        // Carga todas las relaciones que necesitamos para la vista de detalles
        $tarea->load('user', 'generadorDetalle', 'vehiculoDetalle', 'fotos');

        return view('tareas.show', compact('tarea'));
    }

    /**
     * Muestra el formulario para editar un reporte existente.
     */
    public function edit(Tarea $tarea)
    {
        // Carga las relaciones para que el formulario 'edit' tenga los datos
        $tarea->load('generadorDetalle', 'vehiculoDetalle');
        return view('tareas.edit', compact('tarea'));
    }

    /**
     * Actualiza un reporte existente en la base de datos.
     */
    public function update(Request $request, Tarea $tarea)
    {
        // 1. Validación de todos los campos
        $validatedData = $request->validate($this->getValidationRules());
        
        // 2. Procesar y guardar firmas (pasamos la ruta antigua para borrarla)
        $instaladorPath = $this->saveSignature($request, 'instalador', $tarea->instalador_firma_path);
        $clientePath = $this->saveSignature($request, 'cliente', $tarea->cliente_firma_path);

        // 3. Preparamos los datos para actualizar
        $dataToUpdate = array_merge($validatedData, [
            'instalador_nombre' => $request->instalador_nombre,
            'cliente_nombre' => $request->cliente_nombre,
        ]);
        
        // Solo actualizamos la ruta si se subió una nueva
        if ($instaladorPath) {
            $dataToUpdate['instalador_firma_path'] = $instaladorPath;
        }
        if ($clientePath) {
            $dataToUpdate['cliente_firma_path'] = $clientePath;
        }

        // 4. Actualizamos la tarea
        $tarea->update($dataToUpdate);
        
        // 5. Lógica para detalles de generadores/vehículos
        if ($tarea->tipo === 'generadores') {
            $generadorData = $request->validate([
                'cantidad_equipos' => 'required|integer|min:0|max:20',
                'numeros_economicos' => 'required_if:cantidad_equipos,>,0|array',
                'numeros_economicos.*' => 'required|string|max:255',
            ]);
            $tarea->generadorDetalle()->updateOrCreate(
                ['tarea_id' => $tarea->id],
                ['numeros_economicos' => $generadorData['numeros_economicos'] ?? []]
            );
            if ($tarea->vehiculoDetalle) $tarea->vehiculoDetalle->delete(); // Limpia datos de otros tipos
        } 
        elseif ($tarea->tipo === 'vehiculos') {
            $vehiculoData = $request->validate([
                'gps_marca' => 'nullable|string|max:255',
                // ... (todas las demás reglas de vehiculo)
            ]);
            $tarea->vehiculoDetalle()->updateOrCreate(['tarea_id' => $tarea->id], $vehiculoData);
            if ($tarea->generadorDetalle) $tarea->generadorDetalle->delete(); // Limpia datos de otros tipos
        } else {
            // Si es 'instalaciones_red' o cualquier otro, borramos los detalles que ya no aplican
            if ($tarea->generadorDetalle) $tarea->generadorDetalle->delete();
            if ($tarea->vehiculoDetalle) $tarea->vehiculoDetalle->delete();
        }

        // 6. Guardar nuevas fotos
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $path = $foto->store('evidencias', 'public');
                $tarea->fotos()->create([
                    'path' => $path,
                    'etapa_subida' => $tarea->estado // Guarda el estado actual (pendiente, en_proceso, etc.)
                ]);
            }
        }

        // 7. Redirigimos
        return redirect()->route('tareas.index')->with('success', '¡Reporte actualizado exitosamente!');
    }

    /**
     * Actualiza el estado de un reporte (ciclo: pendiente -> en_proceso -> completada).
     */
    public function updateStatus(Tarea $tarea)
    {
        $nextStatus = match($tarea->estado) {
            'pendiente' => 'en_proceso',
            'en_proceso' => 'completada',
            'completada' => 'pendiente',
        };

        $tarea->update(['estado' => $nextStatus]);

        return back()->with('success', 'Estado del reporte actualizado a: ' . Str::title(str_replace('_', ' ', $nextStatus)));
    }

    /**
     * Elimina un reporte de la base de datos.
     */
    public function destroy(Tarea $tarea)
    {
        // (Opcional pero recomendado) Borrar archivos asociados
        // Borrar fotos de evidencia
        foreach ($tarea->fotos as $foto) {
            Storage::disk('public')->delete($foto->path);
        }
        
        // Borrar firmas
        if ($tarea->instalador_firma_path) {
            Storage::disk('public')->delete($tarea->instalador_firma_path);
        }
        if ($tarea->cliente_firma_path) {
            Storage::disk('public')->delete($tarea->cliente_firma_path);
        }

        // Borrar la tarea (los detalles se borran en cascada por la BD)
        $tarea->delete();

        return redirect()->route('tareas.index')->with('success', 'Reporte eliminado exitosamente.');
    }

    // ====================================================================
    // MÉTODOS PRIVADOS (AYUDANTES)
    // ====================================================================

    /**
     * Reglas de validación compartidas para store() y update().
     */
    private function getValidationRules(): array
    {
        return [
            'folio' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'actividades' => 'required|string',
            'observaciones' => 'nullable|string',
            'tipo' => 'required|in:vehiculos,generadores,instalaciones_red',
            'fotos' => 'nullable|array',
            'fotos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'instalador_nombre' => 'nullable|string|max:255',
            'instalador_firma_file' => 'nullable|image|max:1024',
            'instalador_firma_data' => 'nullable|string',
            'cliente_nombre' => 'nullable|string|max:255',
            'cliente_firma_file' => 'nullable|image|max:1024',
            'cliente_firma_data' => 'nullable|string',
        ];
    }

    /**
     * Procesa y guarda una firma (subida o trazada).
     */
    private function saveSignature(Request $request, string $prefix, ?string $existingPath = null): ?string
    {
        $fileKey = "{$prefix}_firma_file";
        $dataKey = "{$prefix}_firma_data";
        $newPath = null;

        // Opción 1: El usuario subió un archivo
        if ($request->hasFile($fileKey)) {
            $newPath = $request->file($fileKey)->store('firmas', 'public');
        } 
        // Opción 2: El usuario trazó la firma (recibimos Base64)
        elseif ($request->filled($dataKey)) {
            $data = $request->input($dataKey); // Data: "data:image/png;base64,iVBORw..."
            
            // Extraer los datos puros del Base64
            @list($type, $data) = explode(';', $data);
            @list(, $data) = explode(',', $data);
            
            if ($data) {
                $data = base64_decode($data);
                $filename = 'firmas/' . Str::uuid() . '.png';
                Storage::disk('public')->put($filename, $data);
                $newPath = $filename;
            }
        }

        // Si guardamos una nueva firma y ya existía una, borramos la antigua
        if ($newPath && $existingPath) {
            Storage::disk('public')->delete($existingPath);
        }

        // Si no se subió una firma nueva, devolvemos la ruta existente (para que no se borre)
        // OJO: Esta lógica está en el método 'update', aquí solo devolvemos la NUEVA ruta o null.
        return $newPath;
    }
    public function downloadPDF(Tarea $tarea)
    {
        // Carga todas las relaciones necesarias para el PDF
        $tarea->load('user', 'generadorDetalle', 'vehiculoDetalle', 'fotos');

        // Preparamos los datos para la vista, incluyendo el logo codificado
        $data = [
            'tarea' => $tarea,
            'logoPath' => public_path('images/kuantiva_logo.png'), // Ruta absoluta al logo
            // Codificamos las imágenes a Base64 para incrustarlas directamente en el PDF
            'instaladorFirmaBase64' => $this->getImageBase64($tarea->instalador_firma_path),
            'clienteFirmaBase64' => $this->getImageBase64($tarea->cliente_firma_path),
            'fotosBase64' => $tarea->fotos->map(function ($foto) {
                return $this->getImageBase64($foto->path);
            })->filter() // Quitamos posibles nulos si alguna foto no se encuentra
        ];

        // Carga la vista 'pdf_template' con los datos y genera el PDF
        $pdf = Pdf::loadView('tareas.pdf_template', $data);

        // Descarga el PDF con un nombre de archivo descriptivo
        return $pdf->download('reporte-' . $tarea->folio . '.pdf');
    }

    /**
     * Función auxiliar para obtener la imagen como Base64.
     */
    private function getImageBase64(?string $path): ?string
    {
        if (!$path || !Storage::disk('public')->exists($path)) {
            return null;
        }
        $fullPath = Storage::disk('public')->path($path);
        $type = pathinfo($fullPath, PATHINFO_EXTENSION);
        try {
            $data = file_get_contents($fullPath);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        } catch (\Exception $e) {
            return null; // Devuelve null si hay error al leer el archivo
        }
    }
}