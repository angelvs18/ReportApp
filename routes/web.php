<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TareaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --- Grupo de rutas que requieren autenticación ---
Route::middleware('auth')->group(function () {
    // Rutas del perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Rutas de Tareas ---

    // Ruta específica para actualizar el estado (DEBE IR ANTES del resource)
    Route::patch('/tareas/{tarea}/status', [TareaController::class, 'updateStatus'])
        ->name('tareas.updateStatus');

    // Ruta específica para descargar el PDF (DEBE IR ANTES del resource)
    Route::get('/tareas/{tarea}/pdf', [TareaController::class, 'downloadPDF'])
        ->name('tareas.pdf');

    // Rutas CRUD estándar (index, create, store, show, edit, update, destroy)
    Route::resource('tareas', TareaController::class);

}); // --- Fin del grupo auth ---

require __DIR__.'/auth.php';