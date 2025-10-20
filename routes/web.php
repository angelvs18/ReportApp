<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TareaController;

// Ruta de Bienvenida
Route::get('/', function () {
    return view('welcome');
});

// Rutas que requieren autenticación
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Ruta del Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rutas del Perfil de Usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para los Reportes (Tareas)

    // Ruta específica para actualizar el estado (DEBE IR ANTES del resource)
    Route::patch('/tareas/{tarea}/status', [TareaController::class, 'updateStatus'])
        ->name('tareas.updateStatus');

    // Ruta específica para descargar el PDF (DEBE IR ANTES del resource)
    Route::get('/tareas/{tarea}/pdf', [TareaController::class, 'downloadPDF'])
        ->name('tareas.pdf');

    // Rutas CRUD estándar para tareas (index, create, store, show, edit, update, destroy)
    // Solo necesitamos definirla UNA VEZ y dentro del middleware auth.
    Route::resource('tareas', TareaController::class); 

}); // Fin del grupo de middleware 'auth', 'verified'

// Archivo de rutas de autenticación (login, register, etc.)
require __DIR__.'/auth.php';